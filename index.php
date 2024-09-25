<?php
session_start();

// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
    // Redirige al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - Administración</title>
    <!-- Bootstrap CSS para estilos rápidos y consistentes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                <span class="fs-4 text-white fa-solid fa-wine-bottle">  DEPOSITO</span>
            </a>

            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed  text-white" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                        <i class="fas fa-chevron-down me-2 text-"></i> Características generales
                    </button>
                    <div class="collapse" id="home-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="presentacion.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded  text-white">Presentación</a></li>
                            <li><a href="categoria.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Categoría</a></li>
                            <li><a href="marca.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Marca</a></li>
                        </ul>
                    </div>
                </li>

                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed  text-white" data-bs-toggle="collapse" data-bs-target="#comp-collapse" aria-expanded="false">
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
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed  text-white" data-bs-toggle="collapse" data-bs-target="#pro-collapse" aria-expanded="false">
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

        <!-- Contenido principal -->
        <div class="content p-4">
            <h1 class="mb-4">Panel de Control</h1>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Gestión de Presentación</h5>
                            <p class="card-text">Administra las presentaciones de productos.</p>
                            <a href="presentacion.php" class="stretched-link card-link btn btn-outline-dark">Ir a Presentación</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Gestión de Categoría</h5>
                            <p class="card-text">Administra las categorías de productos.</p>
                            <a href="categoria.php" class="stretched-link card-link btn btn-outline-dark">Ir a Categoría</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Gestión de Marca</h5>
                            <   p class="card-text">Administra las marcas de productos.</p>
                            <a href="marca.php" class="stretched-link card-link btn btn-outline-dark">Ir a Marca</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Compras</h5>
                            <p class="card-text">Ver y realizar compras.</p>
                            <a href="mostrar_compras.php" class="stretched-link card-link btn btn-outline-dark">Ver Compras</a>
                            <a href="compras.php" class="stretched-link card-link btn btn-outline-dark">Realizar Compra</a>
                            <a href="proveedor.php" class="stretched-link card-link btn btn-outline-dark">Proveedores</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Productos</h5>
                            <p class="card-text">Administra los productos disponibles.</p>
                            <a href="producto.php" class="stretched-link card-link btn btn-outline-dark">Agregar Producto</a>
                            <a href="existencias.php" class="stretched-link card-link btn btn-outline-dark">Ver Existencias</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Usuarios</h5>
                            <p class="card-text">Administra los usuarios del sistema.</p>
                            <a href="usuarios.php" class="stretched-link card-link btn btn-outline-dark">Ir a Usuarios</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS y FontAwesome para íconos -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/af31879e24.js" crossorigin="anonymous"></script>
    </main>
</body>
</html>
