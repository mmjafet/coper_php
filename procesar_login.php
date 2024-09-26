<?php
// Inicia la sesión
session_start();

// Verifica si se enviaron datos mediante el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Incluye el archivo de conexión a la base de datos
    require_once 'conexion/conectar-mysql.php';

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
            exit();
        } elseif ($id_rol == 2) {
            // Redirige al archivo que muestra los productos como cards
            header("Location: mostrar_productos.php");
            exit();
        } else {
            // Otro rol, redirige a alguna página de error o muestra un mensaje adecuado
            header("Location: login.php");
            exit();
        }
    } else {
        // Credenciales incorrectas, muestra un mensaje de error con SweetAlert2
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>Error de inicio de sesión</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Usuario o contraseña incorrectos.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redirige de vuelta al formulario de inicio de sesión
                    window.location.href = 'login.php';
                });
            </script>
        </body>
        </html>
        <?php
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
