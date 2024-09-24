<?php
session_start();

// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
    // Redirige al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}
?>
<?php
require_once 'conexion/conectar-mysql.php';

// Consulta para obtener los datos de 'producto'
$consulta_producto = "SELECT Codigo_Producto, Nombre_P, Descripcion, Precio, Existencias, Stock_Maximo, Stock_Minimo, Id_Presentacion, Id_Categoria, Id_Marca, Imagen FROM producto WHERE status='1'";
$resultado_producto = mysqli_query($conexion, $consulta_producto);

// Verificación de errores
if (!$resultado_producto) {
    die("Error al obtener existencias: " . mysqli_error($conexion));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Existencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        html, body {
            height: 100%;
            background-color: rgb(230, 234, 240);
        }
        .sidebar {
            height: 100%;
            position: fixed;
           /* width: 250px; /* Anchura fija para la barra lateral */
        }
        .sidebar .nav-link,
        .sidebar .btn-toggle {
            color: #d1e7ff;
        }
        .sidebar .nav-link:hover,
        .sidebar .btn-toggle:hover {
            color: #fff;
        }
        .sidebar .btn-toggle:focus {
            box-shadow: none;
        }
        .card-link {
            text-decoration: none;
            color: #495057;
        }
        .card-link:hover {
            text-decoration: underline;
            color: #007bff;
        }
        .content {
            margin-left: 280px; /* Espacio para la barra lateral */
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
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed text-white" data-bs-toggle="collapse" data-bs-target="#pro-collapse" aria-expanded="true">
                        <i class="fas fa-chevron-down me-2"></i> Productos
                    </button>
                    <div class="collapse show" id="pro-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="producto.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Agregar producto</a></li>
                            <li><a href="existencias.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-dark fw-bold bg-white">Existencias</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item"><a href="ver_ventas.php" class="nav-link text-white"><i class="fas fa-shopping-cart me-2 text-white"></i> Ver Ventas</a></li>
                <li class="border-top my-3"></li>
                <li class="nav-item"><a href="usuarios.php" class="nav-link text-white"><i class="fas fa-user-tie me-2 text-white"></i> Usuarios</a></li>
            </ul>
            <a href="cerrar_sesion.php" class="d-flex align-items-center mb-4 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4 text-white btn btn-danger">Cerrar sesión</span>
            </a>
        </div>
        <!-- Barra lateral para navegación END -->
        <!-- Contenido principal -->
        <div class="content flex-grow-1 p-4">
            <h2>Existencias de Productos</h2>
            <!-- Mostrar productos en tarjetas -->
            <div class="row">
                <?php
                while ($fila = mysqli_fetch_assoc($resultado_producto)) {
                    echo '<div class="col-sm-3 m-3">';
                    echo "<div class='card' style='width: 25rem;'>"; // Tamaño de la tarjeta ampliado
                    if (!empty($fila['Imagen'])) {
                        $imagen_base64 = base64_encode($fila['Imagen']);
                        echo "<img src='data:image/jpeg;base64,$imagen_base64' class='card-img-top p-3' alt='Imagen de producto'>";
                    }
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . htmlspecialchars($fila['Nombre_P']) . "</h5>";
                    echo "<p class='card-text'>" . htmlspecialchars($fila['Descripcion'] ?? '') . "</p>";
                    echo "<p class='card-text'><strong>Código:</strong> " . htmlspecialchars($fila['Codigo_Producto']) . "</p>";
                    echo "<p class='card-text'><strong>Precio:</strong> " . htmlspecialchars($fila['Precio']) . "</p>";
                    echo "<p class='card-text'><strong>Stock minimo:</strong> " . htmlspecialchars($fila['Stock_Minimo']) . "</p>";
                    echo "<p class='card-text'><strong>Existencias:</strong> " . htmlspecialchars($fila['Existencias']) . "</p>";
                    echo "<a href='editar_producto.php?id=" . htmlspecialchars($fila['Codigo_Producto']) . "' class='btn btn-primary'>Editar</a>"; // Botón de editar
                    echo "<a href='eliminar_producto.php?id=" . htmlspecialchars($fila['Codigo_Producto']) . "' class='btn btn-danger' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este producto?\");'>Eliminar</a>"; // Confirmación para evitar errores
                    echo "</div>"; // Cierre del cuerpo de la tarjeta
                    echo "</div>"; // Cierre de la tarjeta
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/20a0f372fc.js" crossorigin="anonymous"></script>
    </main>
</body>
</html>
