<?php
session_start();
// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
    // Redirige al usuario a la página de inicio de sesión
header("Location: login.php");
exit();
}

// Establecer conexión con la base de datos
require_once 'conexion/conectar-mysql.php';

// Consultar la tabla compra
$query_compras = "SELECT compra.Folio_Compra, compra.Total_Pagar, compra.Fecha, proveedor.Nombre AS Nombre_Proveedor 
                  FROM compra
                  LEFT JOIN proveedor ON compra.Id_Proveedor = proveedor.Id_Proveedor
                  WHERE compra.status = '1'
                  ORDER BY compra.Fecha DESC";
$result_compras = mysqli_query($conexion, $query_compras);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        html, body {
            height: 100%;
            background-color: rgb(230, 234, 240);
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
        .content {
            flex-grow: 1;
        }
        .card-link {
            text-decoration: none;
            color: #495057;
        }
        .card-link:hover {
            text-decoration: underline;
            color: #007bff;
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
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 text-white" data-bs-toggle="collapse" data-bs-target="#comp-collapse" aria-expanded="true">
                        <i class="fas fa-chevron-down me-2"></i> Compras
                    </button>
                    <div class="collapse show" id="comp-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="mostrar_compras.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-dark fw-bold bg-white">Ver Compra</a></li>
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
                <li class="nav-item"><a href="ver_ventas.php" class="nav-link text-white"><i class="fas fa-shopping-cart me-2 text-white"></i> Ver Ventas</a></li>
                <li class="border-top my-3"></li>
                <li class="nav-item"><a href="usuarios.php" class="nav-link text-white"><i class="fas fa-user-tie me-2 text-white"></i> Usuarios</a></li>
            </ul>
            <a href="cerrar_sesion.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4 text-white btn btn-danger">Cerrar sesión</span>
            </a>
        </div>


        <div class="container mt-5">
            <h1>Lista de Compras</h1>
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Folio de Compra</th>
                        <th>Total a Pagar</th>
                        <th>Fecha</th>
                        <th>Proveedor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result_compras) > 0) {
                        while ($row_compra = mysqli_fetch_assoc($result_compras)) {
                            echo "<tr>";
                            echo "<td>{$row_compra['Folio_Compra']}</td>";
                            echo "<td>" . number_format($row_compra['Total_Pagar'], 2) . "</td>";
                            echo "<td>{$row_compra['Fecha']}</td>";
                            echo "<td>{$row_compra['Nombre_Proveedor']}</td>";
                            echo "<td><a href='detalles_compra.php?folio={$row_compra['Folio_Compra']}' class='btn btn-primary'>Ver Detalles</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No hay compras registradas.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <script src="https://kit.fontawesome.com/af31879e24.js" crossorigin="anonymous"></script>
</body>
</html>

<?php
// Cerrar conexión
mysqli_close($conexion);
?>
