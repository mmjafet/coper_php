<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: formulario_verificacion.php");
    exit();
}

// Verificar si hay una variable de sesión para el carrito, si no existe, crearla como un array vacío
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Función para calcular el total de la venta
function calcularTotalVenta() {
    $total = 0;
    foreach ($_SESSION['carrito'] as $item) {
        $total += $item['cantidad'] * $item['precio'];
    }
    return $total;
}

// Recalcular el total de la venta al cargar la página
$total_venta = calcularTotalVenta();

// Manejo de la eliminación de un producto del carrito
if (isset($_GET['eliminar'])) {
    $codigo_eliminar = $_GET['eliminar'];
    if (isset($_SESSION['carrito'][$codigo_eliminar])) {
        unset($_SESSION['carrito'][$codigo_eliminar]);
        // Recalcular el total de la venta después de eliminar el producto
        $total_venta = calcularTotalVenta();
        // Redirigir para evitar resubmission de formularios
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

if (isset($_POST['procesar_compra'])) {
    require_once 'conexion/conectar-mysql.php';

    $errores = [];

    // Guardar los detalles del carrito en una variable temporal
    $carrito_detalles = $_SESSION['carrito'];

    foreach ($carrito_detalles as $item) {
        $codigo_producto = $item['codigo_producto'];
        $cantidad = $item['cantidad'];

        $sql_existencias = "SELECT Existencias FROM producto WHERE Codigo_Producto = ?";
        $stmt_existencias = $conexion->prepare($sql_existencias);
        $stmt_existencias->bind_param("s", $codigo_producto);
        $stmt_existencias->execute();
        $stmt_existencias->bind_result($existencias);
        $stmt_existencias->fetch();
        $stmt_existencias->close();

        if ($cantidad > $existencias) {
            $errores[] = "La cantidad solicitada para el producto $codigo_producto excede las existencias disponibles ($existencias).";
        }
    }

    if (empty($errores)) {
        $conexion->begin_transaction();

        try {
            $fecha_hora = date('Y-m-d H:i:s');
            $id_usuario = $_SESSION['usuario_id'];
            $status_venta = '1';

            $sql_venta = "INSERT INTO venta (Folio_Venta, Total_Venta, Fecha_Hora, Id_Usuario, status) VALUES (?, ?, ?, ?, ?)";
            $stmt_venta = $conexion->prepare($sql_venta);
            $folio_venta = uniqid('VEN');
            $stmt_venta->bind_param("sdsis", $folio_venta, $total_venta, $fecha_hora, $id_usuario, $status_venta);
            $stmt_venta->execute();
            $stmt_venta->close();

            foreach ($carrito_detalles as $item) {
                $codigo_producto = $item['codigo_producto'];
                $cantidad = $item['cantidad'];
                $precio_unitario = $item['precio'];
                $total_pagar = $cantidad * $precio_unitario;

                $sql_detalle = "INSERT INTO detalle_venta (Cantidad, Total_Pagar, Folio_Venta, Codigo_Producto) VALUES (?, ?, ?, ?)";
                $stmt_detalle = $conexion->prepare($sql_detalle);
                $stmt_detalle->bind_param("ddss", $cantidad, $total_pagar, $folio_venta, $codigo_producto);
                $stmt_detalle->execute();
                $stmt_detalle->close();

                $sql_update_existencias = "UPDATE producto SET Existencias = Existencias - ? WHERE Codigo_Producto = ?";
                $stmt_update_existencias = $conexion->prepare($sql_update_existencias);
                $stmt_update_existencias->bind_param("is", $cantidad, $codigo_producto);
                $stmt_update_existencias->execute();
                $stmt_update_existencias->close();
            }

            $conexion->commit();

            // Limpiar el carrito después de procesar la compra
            $_SESSION['carrito'] = [];
            
            // Construir el mensaje del correo de confirmación usando los detalles del carrito guardados
            $asunto = "Confirmación de Compra";
            $mensaje = "¡Gracias por tu compra!\n\n";
            $mensaje .= "Detalles de la compra:\n";
            $mensaje .= "Folio de Venta: $folio_venta\n";
            $mensaje .= "Fecha y Hora: $fecha_hora\n";
            $mensaje .= "Total de la Venta: $" . number_format($total_venta, 2) . "\n"; // Mostrar el total de la venta correcto
            $mensaje .= "Detalles de los Productos:\n";

            foreach ($carrito_detalles as $item) {
                $mensaje .= "Producto: " . htmlspecialchars($item['nombre']) . "\n";
                $mensaje .= "Código: " . htmlspecialchars($item['codigo_producto']) . "\n";
                $mensaje .= "Cantidad: " . htmlspecialchars($item['cantidad']) . "\n";
                $mensaje .= "Precio Unitario: $" . number_format($item['precio'], 2) . "\n";
                $mensaje .= "Total: $" . number_format($item['cantidad'] * $item['precio'], 2) . "\n\n";
            }

            $mensaje .= "Gracias por confiar en nosotros.";

            $cabeceras = "From: ventas_bebidas@ventas.com\r\n";
            $cabeceras .= "Content-Type: text/plain; charset=UTF-8";

            $correo_usuario = $_SESSION['usuario_correo']; // Asumimos que el correo del usuario está almacenado en la sesión
            $envio_correo = mail($correo_usuario, $asunto, $mensaje, $cabeceras);

            if ($envio_correo) {
                // Redirigir a una página de compra exitosa
                header("Location: mostrar_productos.php");
                exit();
            } else {
                echo "Error al enviar el correo de confirmación.";
            }
        } catch (Exception $e) {
            $conexion->rollback();
            echo "Error al procesar la compra: " . $e->getMessage();
        }
    } else {
        foreach ($errores as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Carrito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .carrito-item {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        .producto-imagen {
            max-width: 120px;
            height: auto;
            border-radius: 8px;
            margin-right: 15px;
        }
        .btn-eliminar {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Detalle del Carrito</h1>
        <?php
        if (empty($_SESSION['carrito'])) {
            echo '<p class="alert alert-warning">No hay productos en el carrito.</p>';
        } else {
            echo '<form method="post">';
            foreach ($_SESSION['carrito'] as $codigo => $item) {
                echo '<div class="carrito-item d-flex align-items-center">';
                if (!empty($item['imagen'])) {
                    $imagen_base64 = base64_encode($item['imagen']);
                    echo '<img src="data:image/jpeg;base64,' . $imagen_base64 . '" class="producto-imagen" alt="Imagen del producto">';
                }
                echo '<div>';
                echo '<p><strong>Código:</strong> ' . htmlspecialchars($item['codigo_producto']) . '</p>';
                echo '<p><strong>Nombre:</strong> ' . htmlspecialchars($item['nombre']) . '</p>';
                echo '<p><strong>Precio:</strong> $' . htmlspecialchars($item['precio']) . '</p>';
                echo '<p><strong>Cantidad:</strong> ' . htmlspecialchars($item['cantidad']) . '</p>';
                echo '<a href="?eliminar=' . $codigo . '" class="btn btn-danger btn-eliminar">Eliminar</a>';
                echo '</div>';
                echo '</div>';
            }
            // Mostrar el total de la venta calculado
            echo '<div class="mt-4">';
            echo '<p class="h5">Total de la venta: $' . number_format($total_venta, 2) . '</p>';
            echo '<button type="submit" name="procesar_compra" class="btn btn-success">Procesar Compra</button>';
            echo '</div>';
            echo '</form>';
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
