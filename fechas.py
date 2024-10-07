from datetime import datetime, timedelta

# Obtener la fecha y hora actual
ahora = datetime.now()
print("Fecha y hora actual:", ahora)

# Formatear una fecha
formato = ahora.strftime("%d/%m/%Y, %H:%M:%S")
print("Fecha formateada:", formato)

# Operaciones con fechas
nueva_fecha = ahora + timedelta(days=10)
print("Fecha dentro de 10 días:", nueva_fecha)
