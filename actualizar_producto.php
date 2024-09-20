<?php
session_start();
// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
    // Redirige al usuario a la página de inicio de sesión
header("Location: login.php");
exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'conexion/conectar-mysql.php';

// Iniciar el buffer de salida para evitar problemas de encabezados
ob_start();

// Función para validar datos
function validate_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Variables del formulario con validación
$codigo_producto = validate_input($_POST['codigo_producto']);
$nombre_p = validate_input($_POST['nombre_p']);
$descripcion = validate_input($_POST['descripcion']);
$precio = (float) ($_POST['precio'] ?? 0);
$existencias = (int) ($_POST['existencias'] ?? 0);
$stock_maximo = (int) ($_POST['stock_maximo'] ?? 0);
$stock_minimo = (int) ($_POST['stock_minimo'] ?? 0);

// Consulta para actualizar los datos del producto
$consulta_actualizacion = "UPDATE producto 
    SET Nombre_P = ?, Descripcion = ?, Precio = ?, Existencias = ?, Stock_Maximo = ?, Stock_Minimo = ?
    WHERE Codigo_Producto = ?";

$stmt = mysqli_prepare($conexion, $consulta_actualizacion);

if (!$stmt) {
    die("Error al preparar la consulta: " . mysqli_error($conexion));
}

// Vincular parámetros a la consulta
$types = 'ssdiiss';
$params = [$nombre_p, $descripcion, $precio, $existencias, $stock_maximo, $stock_minimo, $codigo_producto];

mysqli_stmt_bind_param($stmt, $types, ...$params);

if (!mysqli_stmt_execute($stmt)) {
    die("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
}

// Limpiar el buffer para evitar problemas de encabezados
ob_end_clean();

// Redirigir después del éxito
header("Location: existencias.php?mensaje=exito");
exit();

// Cerrar la declaración y la conexión
mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>
