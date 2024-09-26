<?php
session_start();

// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
    // Redirige al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

require_once 'conexion/conectar-mysql.php';

// Obtener el ID del proveedor de la solicitud
$idProveedor = isset($_GET['id_proveedor']) ? $_GET['id_proveedor'] : null;

// Verificar si se proporcionó un ID de proveedor válido
if ($idProveedor) {
    // Preparar la consulta para obtener productos relacionados con el proveedor
    $query = "SELECT Codigo_Producto, Nombre_P FROM producto WHERE Id_Proveedor = ? AND status = '1'";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idProveedor);
    $stmt->execute();
    $result = $stmt->get_result();

    // Crear un array para almacenar los productos
    $productos = array();

    // Obtener los resultados de la consulta
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }

    // Enviar la lista de productos como respuesta JSON
    header('Content-Type: application/json');
    echo json_encode($productos);
} else {
    // Si no se proporcionó un ID de proveedor válido, enviar una respuesta de error
    http_response_code(400);
    echo json_encode(array('error' => 'ID de proveedor no válido'));
}
?>
