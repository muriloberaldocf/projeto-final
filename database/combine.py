import os

output_file = r'c:\xampp\htdocs\2025\projeto-final\database\theory_quimica.php'
parts = [
    r'c:\xampp\htdocs\2025\projeto-final\database\part1.php',
    r'c:\xampp\htdocs\2025\projeto-final\database\part2.php',
    r'c:\xampp\htdocs\2025\projeto-final\database\part3.php',
    r'c:\xampp\htdocs\2025\projeto-final\database\part4.php'
]

with open(output_file, 'w', encoding='utf-8') as outfile:
    outfile.write("<?php\n// Resumos teóricos completos — Química\nreturn [\n")
    for p in parts:
        with open(p, 'r', encoding='utf-8') as infile:
            outfile.write(infile.read())
    outfile.write("];\n")
