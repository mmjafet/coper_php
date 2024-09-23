<?php
session_start();
// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
    // Redirige al usuario a la página de inicio de sesión
header("Location: login.php");
exit();
}

require_once 'conexion/conectar-mysql.php';

// Verificar si se ha proporcionado el ID del producto a editar
if (!isset($_GET['id'])) {
    die("Error: No se ha proporcionado un ID de producto.");
}

$id_producto = (int) $_GET['id']; // Asegúrate de convertirlo a entero para evitar inyecciones SQL

// Consulta para obtener los detalles del producto
$consulta_producto = "SELECT * FROM producto WHERE Codigo_Producto = ?";
$stmt = mysqli_prepare($conexion, $consulta_producto);
mysqli_stmt_bind_param($stmt, 'i', $id_producto); // Vincular el ID del producto
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) == 0) {
    die("Error: Producto no encontrado.");
}

$producto = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Editar Producto</h2>
        <form action="actualizar_producto.php" method="POST" enctype="multipart/form-data">
            <!-- Código del Producto (oculto, porque no debería cambiar) -->
            <input type="hidden" name="codigo_producto" value="<?php echo htmlspecialchars($producto['Codigo_Producto']); ?>">

            <!-- Nombre del Producto -->
            <div class="form-group">
                <label para="nombre_p">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre_p" name="nombre_p" value="<?php echo htmlspecialchars($producto['Nombre_P']); ?>" required>
            </div>

            <!-- Descripción del Producto -->
            <div class="form-group">
                <label para="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion"><?php echo htmlspecialchars($producto['Descripcion']); ?></textarea>
            </div>

            <!-- Precio -->
            <div class="form-group">
                <label para="precio">Precio</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo $producto['Precio']; ?>">
            </div>

            <!-- Existencias -->
            <div class="form-group">
                <label para="existencias">Existencias</label>
                <input type="number" class="form-control" id="existencias" name="existencias" value="<?php echo $producto['Existencias']; ?>">
            </div>

            <!-- Stock Máximo -->
            <div class="form-group">
                <label para="stock_maximo">Stock Máximo</label>
                <input type="number" class="form-control" id="stock_maximo" name="stock_maximo" value="<?php echo $producto['Stock_Maximo']; ?>">
            </div>

            <!-- Stock Mínimo -->
            <div class="form-group">
                <label para="stock_minimo">Stock Mínimo</label>
                <input type="number" class="form-control" id="stock_minimo" name="stock_minimo" value="<?php echo $producto['Stock_Minimo']; ?>">
            </div>

            <!-- Imagen -->
            <div class="form-group">
                <label para="imagen">Imagen del Producto (opcional)</label>
                <input type="file" class="form-control" id="imagen" name="imagen">
            </div>

            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
        </form>
    </div>
</body>
</html>
