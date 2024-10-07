# Abrir y cerrar archivos automáticamente con 'with'
with open('archivo.txt', 'w') as archivo:
    archivo.write("Este archivo se cierra automáticamente al salir del bloque 'with'.")
