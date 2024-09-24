<?php
session_start();

// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Redirige al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}
// Verifica si se recibió el parámetro 'id' en la URL
if(isset($_GET['id'])) {
    // Incluye el archivo de conexión a la base de datos
    require_once 'conexion/conectar-mysql.php';

    // Obtiene el ID del usuario de la URL
    $id = $_GET['id'];

    // Consulta SQL para eliminar el usuario con el ID proporcionado
    $sql = "UPDATE usuarios SET status = '0' WHERE IdUsuario = $id";

    // Ejecuta la consulta
    if ($conexion->query($sql) === TRUE) {
        // Redirige a la página de usuarios después de eliminar el usuario
        header("Location: usuarios.php");
        exit();
    } else {
        echo "Error al eliminar el usuario: " . $conexion->error;
    }

    // Cierra la conexión a la base de datos
    $conexion->close();
} else {
    // Si no se proporciona el parámetro 'id' en la URL, redirige a la página de usuarios
    header("Location: usuarios.php");
    exit();
}
?>
