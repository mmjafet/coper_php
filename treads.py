import threading
import time

# Función para ser ejecutada por un hilo
def contar():
    for i in range(5):
        print(f"Contando: {i}")
        time.sleep(1)

# Crear y lanzar un hilo
hilo = threading.Thread(target=contar)
hilo.start()

# El programa continúa ejecutándose mientras el hilo corre en segundo plano
print("El programa principal sigue...")
hilo.join()  # Espera a que el hilo termine
