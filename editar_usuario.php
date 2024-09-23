<?php
session_start();

// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
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

    // Consulta SQL para obtener los datos del usuario con el ID proporcionado
    $sql = "SELECT * FROM usuarios WHERE IdUsuario = $id";
    $result = $conexion->query($sql);

    // Verifica si se encontró el usuario
    if ($result->num_rows > 0) {
        // Obtiene los datos del usuario
        $usuario = $result->fetch_assoc();
        $nombre = $usuario["Nombre"];
        $apPaterno = $usuario["Apellido1"];
        $apMaterno = $usuario["Apellido1"];
        $email = $usuario["Email"];
    } else {
        // Si no se encuentra el usuario, redirige a la página de usuarios
        header("Location: usuarios.php");
        exit();
    }

    // Cierra la conexión a la base de datos
    $conexion->close();
} else {
    // Si no se proporciona el parámetro 'id' en la URL, redirige a la página de usuarios
    header("Location: usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <!-- Bootstrap CSS para estilos rápidos y consistentes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Usuario</h1>
        <form action="actualizar_usuario.php" method="POST">
        <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value=<?php echo($nombre)?> required>
            </div>
            <div class="form-group">
                <label for="ap_paterno">Apellido Paterno:</label>
                <input type="text" class="form-control" id="ap_paterno" name="ap_paterno" value=<?php echo($apPaterno)?> required>
            </div>
            <div class="form-group">
                <label for="ap_materno">Apellido Materno:</label>
                <input type="text" class="form-control" id="ap_materno" name="ap_materno" value=<?php echo($apMaterno)?> required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value=<?php echo($email)?> required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña nueva:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
