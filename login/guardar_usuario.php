<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../conexion/conectar-mysql.php';

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$ap_paterno = $_POST['ap_paterno'];
$ap_materno = $_POST['ap_materno'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encriptar la contraseña
$estatus = '1'; // Valor por defecto
$id_rol = $_POST['rol'];

// Manejo del archivo de la foto
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto = file_get_contents($_FILES['foto']['tmp_name']);
} else {
    $foto = NULL;
}

// Insertar los datos en la tabla
$stmt = $conexion->prepare("INSERT INTO usuarios (Nombre, Apellido1, Apellido2, Email, Contrasenia, Foto, status, Id_Rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssbsi", $nombre, $ap_paterno, $ap_materno, $email, $password, $foto, $estatus, $id_rol);

// Ejecutar la consulta
$response = [];
if ($stmt->execute()) {
    
    $response['success'] = true;
    $response['redirectUrl'] = 'usuarios.php'; // Cambia esto a la URL que desees
} else {
    $response['success'] = false;
    $response['error'] = $stmt->error;
}

$stmt->close();
$conexion->close();

// Enviar respuesta
echo json_encode($response);
?>
