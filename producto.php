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

// Función para verificar errores en las consultas
function check_query($resultado, $conexion, $error_message) {
    if (!$resultado) {
        die("Error: " . $error_message . " - " . mysqli_error($conexion));
    }
}

// Consultas para obtener opciones para las listas desplegables
$consulta_presentacion = "SELECT Id_Presentacion, Descripcion FROM presentacion";
$resultado_presentacion = mysqli_query($conexion, $consulta_presentacion);
check_query($resultado_presentacion, $conexion, "Error al obtener datos de Presentación");

$consulta_categoria = "SELECT Id_Categoria, Nombre_Cat FROM categoria";
$resultado_categoria = mysqli_query($conexion, $consulta_categoria);
check_query($resultado_categoria, $conexion, "Error al obtener datos de Categoría");

$consulta_marca = "SELECT Id_Marca, Nombre_Marca FROM marca";
$resultado_marca = mysqli_query($conexion, $consulta_marca);
check_query($resultado_marca, $conexion, "Error al obtener datos de Marca");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
       /* Ajustar altura y color de fondo */
html, body {
    height: 100%;
    background-color: rgb(230, 234, 240);
}

/* Configuración de la barra lateral */
.sidebar {
    height: 100%;
    width: 250px; /* Ancho fijo para la barra lateral */
    position: fixed;
    top: 0;
    left: 0;
    background-color: #343a40; /* Color oscuro de fondo */
    padding-top: 20px;
}

/* Estilos para los enlaces en la barra lateral */
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

/* Espacio para el contenido principal */
.content {
    margin-left: 270px; /* Margen suficiente para no solaparse con la barra lateral */
    padding: 20px;
}

/* Estilos para los enlaces de las tarjetas */
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
                            <li><a href="producto.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-dark fw-bold bg-white">Agregar producto</a></li>
                            <li><a href="existencias.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Existencias</a></li>
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
         <!-- Barra lateral para navegación END-->


    
        <!-- Contenido principal -->
        <div class="content flex-grow-1 p-4">
            <!-- Mostrar mensaje según el resultado de inserción -->
            <?php
            if (isset($_GET['mensaje'])) {
                if ($_GET['mensaje'] == 'exito') {
                    echo "<div class='alert alert-success'>Producto insertado exitosamente.</div>";
                } elseif ($_GET['mensaje'] == 'error') {
                    $detalle = $_GET['detalle'] ?? 'Error desconocido.';
                    echo "<div class='alert alert-danger'>Error al insertar producto: $detalle.</div>";
                }
            }
            ?>

            <!-- Formulario para agregar producto -->
            <div class="container">
                <h2>Agregar Nuevo Producto</h2>
                <form action="insertar_producto.php" method="POST" enctype="multipart/form-data">
                    <!-- Código del Producto -->
                    <div class="form-group my-4 fs-5">
                        <label para="codigo_producto">Código del Producto</label>
                        <input type="text" class="form-control" id="codigo_producto" name="codigo_producto" required>
                    </div>

                    <!-- Nombre del Producto -->
                    <div class="form-group my-4 fs-5">
                        <label para="nombre_p">Nombre del Producto</label>
                        <input type="text" class="form-control" id="nombre_p" name="nombre_p" required>
                    </div>

                    <!-- Descripción del Producto -->
                    <div class="form-group my-4 fs-5">
                                <label para="descripcion">Descripción</label>
                                <input class="form-control" id="descripcion" name="descripcion">
                            </div>

                    <!-- Precio -->
                    <div class="form-group my-4 fs-5">
                        <label para="precio">Precio</label>
                        <input type="number" step="0.01" class="form-control" id="precio" name="precio">
                    </div>

                    <!-- Existencias -->
                    <div class="form-group my-4 fs-5">
                        <label para="existencias">Existencias</label>
                        <input type="number" class="form-control" id="existencias" name="existencias">
                    </div>

                    <!-- Stock Máximo --> 
                    <div class="form-group my-4 fs-5">
                        <label para="stock_maximo">Stock Máximo</label>
                        <input type="number" class="form-control" id="stock_maximo" name="stock_maximo">
                    </div>

                    <!-- Stock Mínimo -->
                    <div class="form-group my-4 fs-5">
                        <label para="stock_minimo">Stock Mínimo</label>
                        <input type="number" class="form-control" id="stock_minimo" name="stock_minimo">
                    </div>

                    <!-- Presentación, Categoría, Marca -->
                    <div class=" my-4 fs-5">
                        <label para="id_presentacion">Presentación</label>
                        <select class="form-control" id="id_presentacion" name="id_presentacion">
                            <!-- Opciones para Presentación -->
                            <?php
                            while ($fila = mysqli_fetch_assoc($resultado_presentacion)) {
                                echo "<option value='" . $fila['Id_Presentacion'] . "'>" . $fila['Descripcion'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class=" my-4 fs-5">
                        <label para="id_categoria">Categoría</label>
                        <select class="form-control" id="id_categoria" name="id_categoria">
                            <!-- Opciones para Categoría -->
                            <?php
                            while ($fila = mysqli_fetch_assoc($resultado_categoria)) {
                                echo "<option value='" . $fila['Id_Categoria'] . "'>" . $fila['Nombre_Cat'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class=" my-4 fs-5">
                        <label para="id_marca">Marca</label>
                        <select class="form-control" id="id_marca" name="id_marca">
                            <!-- Opciones para Marca -->
                            <?php
                            while ($fila = mysqli_fetch_assoc($resultado_marca)) {
                                echo "<option value='" . $fila['Id_Marca'] . "'>" . $fila['Nombre_Marca'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Imagen -->
                    <div class="form-group my-4 fs-5">
                        <label para="imagen">Imagen del Producto</label>
                        <input type="file" class="form-control" id="imagen" name="imagen">
                    </div>


                    <!-- Botón para enviar el formulario -->
                    <button type="submit" class="btn btn-primary mb-4 fs-5">Agregar Producto</button>
                </form>

            </div>
        </div>

        <!-- Scripts para Bootstrap -->
    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/20a0f372fc.js" crossorigin="anonymous"></script>
</body>
</html>

<?php
// Cerrar la conexión
mysqli_close($conexion);
