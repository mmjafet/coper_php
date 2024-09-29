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

// Inicializar carrito si aún no está configurado
if (!isset($_SESSION['carrito']) || !is_array($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Obtener el ID de usuario de la sesión
$idUsuario = $_SESSION['usuario']['IdUsuario'];

// Calcular el total de la venta y contar productos
$totalVenta = 0;
$numProductos = 0;

// Verificar que $_SESSION['carrito'] sea un arreglo antes de iterar
if (is_array($_SESSION['carrito'])) {
    foreach ($_SESSION['carrito'] as $item) {
        $totalVenta += $item['cantidad'] * $item['precio'];
        $numProductos += $item['cantidad'];
    }
} else {
    echo "Error: El carrito de compra no es un arreglo.";
    exit();
}

// Crear folio de venta único (puedes usar algún método para generar un folio único)
$folioVenta = uniqid('VTA');

// Insertar en la tabla venta
$sqlInsertVenta = "INSERT INTO venta (Folio_Venta, Total_Venta, Fecha_Hora, Id_Usuario) VALUES (?, ?, NOW(), ?)";
$stmtInsertVenta = $conexion->prepare($sqlInsertVenta);
$stmtInsertVenta->bind_param("sdi", $folioVenta, $totalVenta, $idUsuario);
$stmtInsertVenta->execute();

// Verificar si la inserción fue exitosa
if ($stmtInsertVenta->affected_rows > 0) {
    // Insertar detalles de venta en la tabla detalle_venta
    $sqlInsertDetalle = "INSERT INTO detalle_venta (Cantidad, Total_Pagar, Folio_Venta, Codigo_Producto) VALUES (?, ?, ?, ?)";
    $stmtInsertDetalle = $conexion->prepare($sqlInsertDetalle);

    foreach ($_SESSION['carrito'] as $item) {
        $cantidad = $item['cantidad'];
        $totalPagar = $item['cantidad'] * $item['precio'];
        $codigoProducto = $item['codigo_producto'];

        $stmtInsertDetalle->bind_param("ddss", $cantidad, $totalPagar, $folioVenta, $codigoProducto);
        $stmtInsertDetalle->execute();
    }

    // Limpiar carrito después de la compra
    $_SESSION['carrito'] = [];

    // Redirigir a una página de confirmación o a donde desees, incluyendo detalles
    header("Location: confirmacion_compra.php?folio=$folioVenta");
    exit();
} else {
    // Manejar error en la inserción de venta
    echo "Error al procesar la venta. Por favor, inténtalo de nuevo.";
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
