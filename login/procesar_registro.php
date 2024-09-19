<?php
// Inicia la sesión
session_start();

// Verifica si se enviaron datos mediante el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluye el archivo de conexión a la base de datos
    require_once '../conexion/conectar-mysql.php';

    // Obtiene los datos del formulario
    $email = $_POST["txtEmail"];
    $password = $_POST["txtPassword"];

    // Consulta SQL para obtener el hash de la contraseña
    $sql = "SELECT Contrasenia, Id_Rol FROM usuarios WHERE Email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($hashed_password, $id_rol);
    $stmt->fetch();
    $stmt->close();

    // Verifica si se encontró algún usuario con el correo electrónico proporcionado
    if ($hashed_password && password_verify($password, $hashed_password)) {
        // La contraseña es válida, inicia la sesión y redirige al panel de control
        $_SESSION['usuario'] = $email;
        $_SESSION['rol'] = $id_rol;
        if ($id_rol == 1) {
            header("Location: index.php");
        } else {
            header("Location: login.php");
        }
        exit();
    } else {
        // Credenciales incorrectas, redirige de vuelta al formulario de inicio de sesión con un mensaje de error
        $_SESSION['error_login'] = "Usuario o contraseña incorrectos.";
        header("Location: login.php");
        exit();
    }

    // Cierra la conexión a la base de datos
    $conexion->close();
} else {
    // Si no se enviaron datos mediante el método POST, redirige al formulario de inicio de sesión
    header("Location: login.php");
    exit();
}
?>
