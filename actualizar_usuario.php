<?php
session_start();

// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
    // Redirige al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

// Verifica si se recibieron datos mediante el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluye el archivo de conexión a la base de datos
    require_once 'conexion/conectar-mysql.php';

    // Obtiene los datos del formulario
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apPaterno = $_POST["ap_paterno"];
    $apMaterno = $_POST["ap_materno"];
    $email = $_POST["email"];
    $password = $_POST['password'];

    // Consulta SQL para actualizar los datos del usuario en la base de datos
    if (empty($password)) {
        $sql = "UPDATE usuarios 
                SET Nombre = '$nombre', 
                    Apellido1 = '$apPaterno', 
                    Apellido2 = '$apMaterno', 
                    Email = '$email'
                WHERE IdUsuario = $id";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE usuarios 
                SET Nombre = '$nombre', 
                    Apellido1 = '$apPaterno', 
                    Apellido2 = '$apMaterno', 
                    Email = '$email', 
                    Contrasenia = '$hashedPassword'
                WHERE IdUsuario = $id";
    }

    // Ejecuta la consulta
    if ($conexion->query($sql) === TRUE) {
        // Redirige a la página de usuarios después de actualizar los datos del usuario
        header("Location: usuarios.php");
        exit();
    } else {
        echo "Error al actualizar el usuario: " . $conexion->error;
    }

    // Cierra la conexión a la base de datos
    $conexion->close();
} else {
    // Si no se recibieron datos mediante el método POST, redirige a la página de usuarios
    header("Location: usuarios.php");
    exit();
}
?>
