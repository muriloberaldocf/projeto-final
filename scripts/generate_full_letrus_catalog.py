# -*- coding: utf-8 -*-
"""
Gerador de Catálogo de 520+ Temas de Redação com Textos Motivadores Completos
Padrão Letrus, Imaginie e Bancas Oficiais do Brasil.
"""

import sys
import json
import random

# Definir seed fixa para consistência
random.seed(42)

# Lista mestra de temas
todos_temas = []

def registrar_tema(titulo, ano, origem, banca, eixo, genero, dificuldade, orientacoes, descricao, textos):
    todos_temas.append({
        'titulo': titulo.strip(),
        'ano': str(ano),
        'origem': origem.strip(),
        'banca': banca.strip(),
        'eixo_tematico': eixo.strip(),
        'genero_textual': genero.strip(),
        'dificuldade': dificuldade.strip(),
        'orientacoes_especificas': orientacoes.strip(),
        'descricao': descricao.strip(),
        'textos_motivadores': textos.strip()
    })

print("Iniciando carregamento das matrizes temáticas...")
