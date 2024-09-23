<?php
session_start();

// Verificar sesión de usuario
if (!isset($_SESSION['usuario'])) {
    // Redirigir o manejar caso donde no hay sesión de usuario
    header("Location: login.php");
    exit();
}

// Incluir archivo de conexión a la base de datos
require_once 'conexion/conectar-mysql.php';

// Obtener el folio de venta desde el parámetro GET
if (isset($_GET['folio'])) {
    $folioVenta = $_GET['folio'];

    // Obtener detalles de la venta
    $sqlDetalles = "SELECT dv.*, p.Nombre_Producto, p.Precio_Unitario, p.Imagen FROM detalle_venta dv
                    INNER JOIN producto p ON dv.Codigo_Producto = p.Codigo_Producto
                    WHERE dv.Folio_Venta = ?";
    $stmtDetalles = $conexion->prepare($sqlDetalles);
    $stmtDetalles->bind_param("s", $folioVenta);
    $stmtDetalles->execute();
    $resultDetalles = $stmtDetalles->get_result();

    // Verificar si hay resultados
    if ($resultDetalles->num_rows > 0) {
        // Mostrar detalles de la venta
        while ($row = $resultDetalles->fetch_assoc()) {
            // Aquí se muestran los detalles, por ejemplo:
            echo "Producto: " . $row['Nombre_Producto'] . "<br>";
            echo "Cantidad: " . $row['Cantidad'] . "<br>";
            echo "Total a pagar: " . $row['Total_Pagar'] . "<br>";
            //echo '<img src="data:image/jpeg;base64,'.base64_encode($row['Imagen']).'"/>';
            echo "<hr>";
        }

        // Aquí podrías permitir editar la cantidad de productos y actualizar el total de la venta
    } else {
        echo "No se encontraron detalles para este folio de venta.";
    }
} else {
    echo "Folio de venta no especificado.";
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
