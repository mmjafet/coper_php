<?php
session_start();
// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    // Redirige al usuario a la página de inicio de sesión
header("Location: login.php");
exit();
}

// Verificar si se reciben los parámetros necesarios
if (isset($_GET['codigo']) && isset($_GET['cantidad'])) {
    $codigo_producto = $_GET['codigo'];
    $cantidad = intval($_GET['cantidad']); // Convertir a entero

    // Validar la cantidad
    if ($cantidad <= 0) {
        // Si la cantidad es menor o igual a cero, eliminar el producto del carrito
        unset($_SESSION['carrito'][$codigo_producto]);
    } else {
        // Actualizar la cantidad del producto en el carrito
        $_SESSION['carrito'][$codigo_producto]['cantidad'] = $cantidad;
    }

    // Redireccionar de vuelta al detalle del carrito
    header("Location: detalle_carrito.php");
    exit();
} else {
    // Si no se reciben los parámetros esperados, redirigir a algún lugar apropiado
    header("Location: index.php"); // Por ejemplo, redirigir a la página principal o a una página de error
    exit();
}
?>
