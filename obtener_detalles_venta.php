<?php
session_start();

// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
    // Redirige al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}
// obtener_detalles_venta.php
require_once 'conexion/conectar-mysql.php'; // Conectar a la base de datos

header('Content-Type: application/json');

if (isset($_GET['folio'])) {
    $folio_venta = $_GET['folio'];

    $sql_detalles = "SELECT dv.Cantidad, dv.Total_Pagar, p.Codigo_Producto, p.Nombre_P FROM detalle_venta dv
                    INNER JOIN producto p ON dv.Codigo_Producto = p.Codigo_Producto
                    WHERE dv.Folio_Venta = ? AND p.status='1'";
    $stmt_detalles = $conexion->prepare($sql_detalles);
    $stmt_detalles->bind_param("s", $folio_venta);
    $stmt_detalles->execute();
    $result_detalles = $stmt_detalles->get_result();

    $detalles = [];
    while ($row = $result_detalles->fetch_assoc()) {
        $detalles[] = $row;
    }
    $stmt_detalles->close();

    // Devolver los detalles en formato JSON
    echo json_encode($detalles);
} else {
    echo json_encode([]);
}
?>
