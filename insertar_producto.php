<?php
session_start();

// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
    // Redirige al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

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
$id_presentacion = (int) ($_POST['id_presentacion'] ?? 0);
$id_categoria = (int) ($_POST['id_categoria'] ?? 0);
$id_marca = (int) ($_POST['id_marca'] ?? 0);

// Obtener la imagen como contenido binario (BLOB)
$imagen_contenido = null;
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
    // Leer el contenido del archivo subido
    $archivo = $_FILES['imagen']['tmp_name'];
    $imagen_contenido = file_get_contents($archivo); // Lee el archivo completo
}

// Función para verificar la existencia de una clave foránea
function foreign_key_exists($conexion, $table, $column, $value) {
    $query = "SELECT 1 FROM $table WHERE $column = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, 'i', $value);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);
    return $exists;
}

if (!foreign_key_exists($conexion, 'marca', 'Id_Marca', $id_marca)) {
    die("Error: Id_Marca $id_marca no existe en la tabla marca.");
}

if (!foreign_key_exists($conexion, 'presentacion', 'Id_Presentacion', $id_presentacion)) {
    die("Error: Id_Presentacion $id_presentacion no existe en la tabla presentacion.");
}

if (!foreign_key_exists($conexion, 'categoria', 'Id_Categoria', $id_categoria)) {
    die("Error: Id_Categoria $id_categoria no existe en la tabla categoria.");
}

// Consulta SQL para insertar datos
$consulta_insercion = "INSERT INTO producto 
    (Codigo_Producto, Nombre_P, Descripcion, Precio, Existencias, Stock_Maximo, Stock_Minimo, Id_Presentacion, Id_Categoria, Id_Marca, Imagen) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conexion, $consulta_insercion);

if (!$stmt) {
    die("Error al preparar la consulta: " . mysqli_error($conexion));
}

// Cadena de tipos y parámetros
$types = 'sssdiidddss'; // Cambiado 's' por 'b' para el campo de imagen y ajustado el orden de los tipos
$params = [$codigo_producto, $nombre_p, $descripcion, $precio, $existencias, $stock_maximo, $stock_minimo, $id_presentacion, $id_categoria, $id_marca, $imagen_contenido];

// Vincular parámetros a la consulta
mysqli_stmt_bind_param($stmt, $types, ...$params);

if (!mysqli_stmt_execute($stmt)) {
    $error = mysqli_stmt_error($stmt);
    die("Error al ejecutar la consulta: $error. Parámetros: " . json_encode($params));
}

// Limpiar el buffer para evitar errores de encabezados
ob_end_clean();

// Redirigir después del éxito
header("Location: existencias.php?mensaje=exito");
exit(); 

// Cerrar la declaración y la conexión
mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>
