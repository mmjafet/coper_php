<?php
session_start();

// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
    // Redirige al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}
require_once 'conexion/conectar-mysql.php';

// Verificar si se ha proporcionado el ID del producto a eliminar
if (!isset($_GET['id'])) {
    die("Error: No se ha proporcionado un ID de producto.");
}

// Obtener y sanitizar el ID del producto
$id_producto = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

if ($id_producto === false) {
    die("Error: El ID de producto proporcionado no es válido.");
}

// Consulta para eliminar el producto
$consulta_eliminacion = "UPDATE producto SET status = '0' WHERE Codigo_Producto = ?";
$stmt = mysqli_prepare($conexion, $consulta_eliminacion);

if (!$stmt) {
    die("Error al preparar la consulta: " . mysqli_error($conexion));
}

// Vincular el parámetro y ejecutar la consulta
mysqli_stmt_bind_param($stmt, 's', $id_producto);

if (!mysqli_stmt_execute($stmt)) {
    die("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
}

// Redirigir después del éxito
header("Location: existencias.php?mensaje=exito");
exit();

// Cerrar la declaración y la conexión
mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>
