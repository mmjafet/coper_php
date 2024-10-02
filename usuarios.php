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
            margin-left: 290px; /* Espacio para la barra lateral */
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
                <li class="nav-item"><a href="ver_ventas.php" class="nav-link text-white"><i class="fas fa-shopping-cart me-2 text-white"></i> Ver Ventas</a></li>
                <li class="border-top my-3"></li>
                <li class="nav-item"><a href="usuarios.php" class="nav-link text-dark fw-bold bg-white"><i class="fas fa-user-tie me-2 text-dark"></i> Usuarios</a></li>
            </ul>
            <a href="cerrar_sesion.php" class="d-flex align-items-center mb-4 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4 text-white btn btn-danger">Cerrar sesión</span>
            </a>
        </div>
        <!-- Barra lateral para navegación END -->


        <div class="content flex-grow-1 mt-4 p-4">
            <h1>Usuarios</h1>
            <a href="agregar_usuario.php" class="btn btn-primary mb-3">Agregar Usuario</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once 'conexion/conectar-mysql.php';

                    $sql = "
    SELECT 
        u.IdUsuario, 
        u.Nombre, 
        u.Apellido1, 
        u.Apellido2, 
        u.Email, 
        r.Rol AS NombreRol
    FROM 
        usuarios u
    INNER JOIN 
        rol r 
    ON 
        u.Id_Rol = r.IdRol
    WHERE 
        u.status = '1'
";
$result = $conexion->query($sql);


                    // Mostrar los datos de los usuarios en la tabla
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["IdUsuario"] . "</td>";
                            echo "<td>" . $row["Nombre"] . "</td>";
                            echo "<td>" . $row["Apellido1"] . "</td>";
                            echo "<td>" . $row["Apellido2"] . "</td>";
                            echo "<td>" . $row["Email"] . "</td>";
                            echo "<td>" . $row["NombreRol"] . "</td>";
                            echo "<td>";
                            echo "<a href='editar_usuario.php?id=" . $row["IdUsuario"] . "' class='btn btn-warning btn-sm'>Editar</a> ";
                            echo "<a href='eliminar_usuario.php?id=" . $row["IdUsuario"] . "' class='btn btn-danger btn-sm'>Eliminar</a>";

                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay usuarios</td></tr>";
                }
                $conexion->close();
                ?>
            </tbody>
        </table>
        <!-- Contenido principal -->
        
    
            <!-- Continúa agregando más secciones para cada tabla según el mismo patrón -->
        </div>
    
        <!-- Bootstrap JS y FontAwesome para íconos -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/20a0f372fc.js" crossorigin="anonymous"></script>
    </main>
</body>
</html>
