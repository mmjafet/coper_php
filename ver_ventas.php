<?php
session_start();
// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
    // Redirige al usuario a la página de inicio de sesión
header("Location: login.php");
exit();
}
// ventas.php
require_once 'conexion/conectar-mysql.php'; // Conectar a la base de datos

// Obtener todas las ventas junto con el nombre y el correo del usuario
$sql_ventas = "
    SELECT 
        v.Folio_Venta, 
        v.Total_Venta, 
        v.Fecha_Hora, 
        v.Id_Usuario, 
        v.status,
        u.Nombre,
        u.Email
    FROM venta v
    JOIN usuarios u ON v.Id_Usuario = u.IdUsuario
";
$result_ventas = $conexion->query($sql_ventas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
           html, body {
            height: 100%;
            background-color: rgb(230, 234, 240);
        }
        .sidebar {
            position: sticky; /* Cambiado a sticky para que se desplace con el contenido */
            top: 0;
            left: 0;
            width: 290px; /* Ancho fijo para la barra lateral */
            z-index: 100; /* Asegura que la barra lateral esté por encima del contenido */
            background-color: #343a40; /* Color de fondo para la barra lateral */
            padding-top: 20px; /* Espaciado superior dentro de la barra lateral */
            padding-bottom: 20px; /* Espaciado inferior dentro de la barra lateral */
        }
        .sidebar .nav-link,
        .sidebar .btn-toggle {
            color: #d1e7ff;
        }
        .sidebar .nav-link:hover,
        .sidebar .btn-toggle:hover {
            color: #fff;
        }
        .card-link {
            text-decoration: none;
            color: #495057;
        }
        .card-link:hover {
            text-decoration: underline;
            color: #007bff;
        }
        .main-content {
            margin-left: 310px; /* Ajuste el margen izquierdo para compensar el ancho de la barra lateral */
            padding: 20px; /* Espaciado dentro del contenido principal */
        }
    </style>
</head>
<body>
    <!-- Barra lateral para navegación -->
    <main class="d-flex flex-nowrap h-100">
        <div class="d-flex flex-column flex-shrink-0 p-4 sidebar bg-dark m-3">
            <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
                <span class="fs-4 text-white fa-solid fa-wine-bottle"> DEPOSITO</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 text-white" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                        <i class="fas fa-chevron-down me-2 text-"></i> Características generales
                    </button>
                    <div class="collapse" id="home-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="presentacion.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Presentación</a></li>
                            <li><a href="categoria.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Categoría</a></li>
                            <li><a href="marca.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Marca</a></li>
                        </ul>
                    </div>
                </li>

                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 text-white" data-bs-toggle="collapse" data-bs-target="#comp-collapse" aria-expanded="false">
                        <i class="fas fa-chevron-down me-2"></i> Compras
                    </button>
                    <div class="collapse" id="comp-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="mostrar_compras.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Ver Compra</a></li>
                            <li><a href="compras.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Realizar compra</a></li>
                            <li><a href="proveedor.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Proveedores</a></li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed text-white" data-bs-toggle="collapse" data-bs-target="#pro-collapse" aria-expanded="false">
                        <i class="fas fa-chevron-down me-2"></i> Productos
                    </button>
                    <div class="collapse" id="pro-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="producto.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Agregar producto</a></li>
                            <li><a href="existencias.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Existencias</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item"><a href="ver_ventas.php" class="nav-link text-dark fw-bold bg-white"><i class="fas fa-shopping-cart me-2 text-dark"></i> Ver Ventas</a></li>
                <li class="border-top my-3"></li>
                <li class="nav-item"><a href="usuarios.php" class="nav-link text-white"><i class="fas fa-user-tie me-2 text-white"></i> Usuarios</a></li>
            </ul>
            <a href="cerrar_sesion.php" class="d-flex align-items-center mb-4 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4 text-white btn btn-danger">Cerrar sesión</span>
            </a>
        </div>
        <!-- Barra lateral para navegación END -->


    <div class="container mt-5">
        <h1>Lista de Ventas</h1>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Folio Venta</th>
                    <th>Total Venta</th>
                    <th>Fecha y Hora</th>
                    <th>Nombre Usuario</th>
                    <th>Correo Usuario</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($venta = $result_ventas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($venta['Folio_Venta']); ?></td>
                        <td><?php echo number_format($venta['Total_Venta'], 2); ?></td>
                        <td><?php echo htmlspecialchars($venta['Fecha_Hora']); ?></td>
                        <td><?php echo htmlspecialchars($venta['Nombre']); ?></td>
                        <td><?php echo htmlspecialchars($venta['Email']); ?></td>
                        <td><?php echo ($venta['status'] == 1) ? 'Activo' : 'Eliminada'; ?></td>

                        <td>
                            <!-- Botón para abrir el modal de detalles -->
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detallesModal" onclick="cargarDetalles('<?php echo $venta['Folio_Venta']; ?>')">Detalles</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para detalles de venta -->
    <div class="modal fade" id="detallesModal" tabindex="-1" aria-labelledby="detallesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detallesModalLabel">Detalles de la Venta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Código Producto</th>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th>Total a Pagar</th>
                            </tr>
                        </thead>
                        <tbody id="detallesVenta">
                            <!-- Aquí se cargarán los detalles de la venta -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        // Función para cargar los detalles de la venta en el modal
        function cargarDetalles(folioVenta) {
            // Solicitar los detalles de la venta al servidor
            fetch('obtener_detalles_venta.php?folio=' + folioVenta)
                .then(response => response.json())
                .then(data => {
                    const detallesVenta = document.getElementById('detallesVenta');
                    detallesVenta.innerHTML = '';
                    data.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.Codigo_Producto}</td>
                            <td>${item.Nombre_P}</td>
                            <td>${item.Cantidad}</td>
                            <td>$${item.Total_Pagar.toFixed(2)}</td>
                        `;
                        detallesVenta.appendChild(row);
                    });
                })
                .catch(error => console.error('Error al cargar los detalles:', error));
        }
    </script>
    <script src="https://kit.fontawesome.com/20a0f372fc.js" crossorigin="anonymous"></script>
</body>
</html>
