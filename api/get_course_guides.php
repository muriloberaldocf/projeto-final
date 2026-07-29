<?php
/**
 * API DO GUIA DE CURSOS E NOTAS DE CORTE - APROVAQUEST
 */
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

$search = trim($_GET['search'] ?? '');
$region = trim($_GET['region'] ?? '');

$sql = "SELECT * FROM course_guides WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (course_name LIKE ? OR university_name LIKE ? OR campus_city LIKE ?)";
    $term = "%$search%";
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
}

if (!empty($region) && $region !== 'todas') {
    $sql .= " AND region = ?";
    $params[] = $region;
}

$sql .= " ORDER BY cutoff_score DESC, course_name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$courses = $stmt->fetchAll();

echo json_encode([
    'success' => true,
    'total' => count($courses),
    'courses' => $courses
]);
