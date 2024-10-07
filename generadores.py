# Definir un generador simple
def contador(maximo):
    num = 0
    while num < maximo:
        yield num  # Genera el valor actual y lo pausa
        num += 1

# Usar el generador
for numero in contador(5):
    print(numero)
