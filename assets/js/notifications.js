/**
 * SISTEMA DE NOTIFICAÇÕES EM TEMPO REAL - HIPOGABARITO
 */
document.addEventListener('DOMContentLoaded', () => {
    fetchNotifications();
    // Atualizar a cada 30 segundos
    setInterval(fetchNotifications, 30000);

    // Fechar dropdown ao clicar fora
    document.addEventListener('click', (e) => {
        const dropdown = document.getElementById('notifDropdown');
        const bellBtn = document.getElementById('notifBellBtn');
        if (dropdown && bellBtn && !dropdown.contains(e.target) && !bellBtn.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
});

function toggleNotifDropdown() {
    const dropdown = document.getElementById('notifDropdown');
    if (!dropdown) return;
    dropdown.classList.toggle('hidden');
    if (!dropdown.classList.contains('hidden')) {
        fetchNotifications();
    }
}

async function fetchNotifications() {
    try {
        const res = await fetch('api/get_notifications.php');
        const data = await res.json();

        if (!data.success) return;

        const notifBadge = document.getElementById('notifBadge');
        const notifCountText = document.getElementById('notifCountText');
        const notifListContainer = document.getElementById('notifListContainer');

        if (notifBadge) {
            if (data.unread_count > 0) {
                notifBadge.textContent = data.unread_count;
                notifBadge.classList.remove('hidden');
            } else {
                notifBadge.classList.add('hidden');
            }
        }

        if (notifCountText) {
            notifCountText.textContent = `${data.notifications.length} notificação(ões)`;
        }

        if (notifListContainer) {
            if (data.notifications.length === 0) {
                notifListContainer.innerHTML = `
                    <div class="text-center py-6 text-slate-400 text-xs">
                        <i class="bi bi-check-circle text-2xl mb-1 block opacity-50"></i>
                        Nenhuma notificação no momento!
                    </div>
                `;
                return;
            }

            notifListContainer.innerHTML = '';
            data.notifications.forEach(n => {
                const item = document.createElement('div');
                item.className = "p-3 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200 transition text-xs space-y-1.5";

                let actionHtml = '';
                if (n.type === 'friend_request') {
                    actionHtml = `
                        <div class="flex items-center gap-1.5 pt-1">
                            <button onclick="acceptFriendNotif(${n.sender_id})" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-[10px] px-2.5 py-1 rounded-xl shadow-sm flex items-center gap-1">
                                <i class="bi bi-check-lg"></i> Aceitar
                            </button>
                            <button onclick="rejectFriendNotif(${n.sender_id})" class="bg-slate-200 hover:bg-rose-100 text-slate-600 hover:text-rose-700 font-bold text-[10px] px-2 py-1 rounded-xl flex items-center gap-1">
                                <i class="bi bi-x-lg"></i> Recusar
                            </button>
                        </div>
                    `;
                }

                item.innerHTML = `
                    <div class="flex items-start gap-2.5">
                        <div class="w-7 h-7 rounded-xl ${n.badge_color || 'bg-indigo-100 text-indigo-600'} flex items-center justify-center text-sm shrink-0 mt-0.5">
                            <i class="bi ${n.icon || 'bi-bell-fill'}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1">
                                <span class="font-outfit font-extrabold text-slate-900 text-xs truncate">${n.title}</span>
                                <span class="text-[10px] text-slate-400 font-medium shrink-0">${n.time}</span>
                            </div>
                            <p class="text-[11px] text-slate-600 leading-tight mb-0">${n.message}</p>
                            ${actionHtml}
                        </div>
                    </div>
                `;
                notifListContainer.appendChild(item);
            });
        }
    } catch (err) {
        console.error('Erro ao buscar notificações:', err);
    }
}

async function acceptFriendNotif(senderId) {
    const formData = new FormData();
    formData.append('action', 'accept_request');
    formData.append('sender_id', senderId);

    const res = await fetch('api/friends.php', { method: 'POST', body: formData });
    const data = await res.json();

    if (data.success) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'Amizade Aceita!', text: data.message, icon: 'success', timer: 1500, showConfirmButton: false });
        }
        fetchNotifications();
        if (typeof loadLeaderboard === 'function') loadLeaderboard();
    }
}

async function rejectFriendNotif(senderId) {
    const formData = new FormData();
    formData.append('action', 'reject_request');
    formData.append('target_id', senderId);

    const res = await fetch('api/friends.php', { method: 'POST', body: formData });
    const data = await res.json();

    if (data.success) {
        fetchNotifications();
    }
}
