<?php
session_start();

// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Redirige al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Validar los datos recibidos (puedes agregar más validaciones según tus necesidades)
    if (empty($correo) || empty($contrasena)) {
        echo "Por favor, completa todos los campos.";
        exit();
    }

    require_once 'conexion/conectar-mysql.php'; // Incluir archivo de conexión a la base de datos

    // Preparar la consulta SQL para obtener el ID de usuario, nombre, correo y contraseña hash
    $sql = "SELECT IdUsuario, Nombre, Email, Contrasenia FROM usuarios WHERE Email = ?";
    $stmt = $conexion->prepare($sql);

    // Verificar si la preparación de la consulta tuvo éxito
    if ($stmt === false) {
        die('Error al preparar la consulta: ' . $conexion->error);
    }

    // Bind parameter y ejecutar la consulta
    $stmt->bind_param("s", $correo);
    $stmt->execute();

    // Obtener el resultado de la consulta
    $stmt->bind_result($id_usuario, $nombre_usuario, $email_usuario, $contrasenia_hash);

    // Verificar si se encontró un usuario con el correo proporcionado
    if ($stmt->fetch()) {
        // Verificar si la contraseña proporcionada coincide con el hash almacenado
        if (password_verify($contrasena, $contrasenia_hash)) {
            // Si coincide, establecer la sesión con el ID de usuario
            $_SESSION['usuario_id'] = $id_usuario;
            $_SESSION['usuario_nombre'] = $nombre_usuario;
            $_SESSION['usuario_correo'] = $email_usuario;
            
            // Preparar el correo de bienvenida
            $asunto = "Inicio de Sesión Exitoso";
            $mensaje = "¡Hola $nombre_usuario!\n\n";
            $mensaje .= "Te has autenticado correctamente en nuestro sitio web.\n\n";
            $mensaje .= "¡Bienvenido de nuevo!\n\n";
            $mensaje .= "Atentamente,\n";
            $mensaje .= "El equipo de ventas de chelas al mayoreo chachauuuu.";

            // Cabeceras del correo
            $cabeceras = "From: ventas_de_chelas_al_mayoreo.com\r\n";
            $cabeceras .= "Content-Type: text/plain; charset=UTF-8";

            // Enviar correo al usuario
            mail($email_usuario, $asunto, $mensaje, $cabeceras);

            // Redirigir a la página de detalle del carrito
            header("Location: detalle_carrito.php");
            exit();
        } else {
            echo "Correo o contraseña incorrectos.";
        }
    } else {
        echo "Correo o contraseña incorrectos.";
    }

    // Cerrar la consulta
    $stmt->close();
}

// Si se llega aquí sin procesar los datos POST, probablemente sea un acceso no autorizado
echo "Acceso no autorizado.";
?>
