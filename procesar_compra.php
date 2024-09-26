<?php
session_start();

// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
    // Redirige al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}
// Obtener los datos del formulario
$folio_compra = $_POST['folio_compra'];
$fecha = $_POST['fecha'];
$id_proveedor = $_POST['id_proveedor'];
$status = $_POST['status'];
$cantidad_productos = $_POST['cantidad'];
$precios_productos = $_POST['precio_compra'];
$codigo_productos = $_POST['codigo_producto'];

// Establecer conexión con la base de datos
require_once 'conexion/conectar-mysql.php';

// Iniciar una transacción
mysqli_begin_transaction($conexion);

try {
    // Calcular el total a pagar
    $total_pagar = 0;
    for ($i = 0; $i < count($cantidad_productos); $i++) {
        $total_pagar += $cantidad_productos[$i] * $precios_productos[$i];
    }

    // Insertar datos en la tabla compra
    $query_compra = "INSERT INTO compra (Folio_Compra, Total_Pagar, Fecha, Id_Proveedor, status) 
                     VALUES ('$folio_compra', '$total_pagar', '$fecha', '$id_proveedor', '$status')";
    $result_compra = mysqli_query($conexion, $query_compra);

    // Verificar si la inserción fue exitosa
    if (!$result_compra) {
        throw new Exception("Error al registrar la compra: " . mysqli_error($conexion));
    }

    // Insertar datos en la tabla detalle_compra
    for ($i = 0; $i < count($cantidad_productos); $i++) {
        $cantidad = $cantidad_productos[$i];
        $precio = $precios_productos[$i];
        $codigo_producto = $codigo_productos[$i];

        // Obtener el stock máximo del producto y las existencias actuales
        $query_stock_maximo = "SELECT Stock_Maximo, Existencias FROM producto WHERE Codigo_Producto = '$codigo_producto'";
        $result_stock_maximo = mysqli_query($conexion, $query_stock_maximo);
        if (!$result_stock_maximo) {
            throw new Exception("Error al obtener el stock máximo del producto: " . mysqli_error($conexion));
        }
        
        $producto_info = mysqli_fetch_assoc($result_stock_maximo);
        $stock_maximo = $producto_info['Stock_Maximo'];
        $existencias_actuales = $producto_info['Existencias'];

        // Verificar si la cantidad supera el stock máximo
        if ($stock_maximo !== null && $existencias_actuales + $cantidad > $stock_maximo) {
            throw new Exception("La cantidad del producto '{$codigo_producto}' supera el stock máximo permitido.");
        }

        // Insertar detalle de compra
        $query_detalle = "INSERT INTO detalle_compra (Cantidad, Precio_Compra, Folio_Compra, Codigo_Producto) 
                          VALUES ('$cantidad', '$precio', '$folio_compra', '$codigo_producto')";
        $result_detalle = mysqli_query($conexion, $query_detalle);
        
        // Verificar si la inserción del detalle fue exitosa
        if (!$result_detalle) {
            throw new Exception("Error al registrar el detalle de la compra: " . mysqli_error($conexion));
        }

        // Actualizar las existencias del producto
        $query_existencias = "UPDATE producto SET Existencias = Existencias + $cantidad WHERE Codigo_Producto = '$codigo_producto'";
        $result_existencias = mysqli_query($conexion, $query_existencias);
        if (!$result_existencias) {
            throw new Exception("Error al actualizar las existencias del producto: " . mysqli_error($conexion));
        }
    }

    // Confirmar la transacción
    mysqli_commit($conexion);

    // Incluir los scripts de SweetAlert2 y Bootstrap
    echo "<!DOCTYPE html>
          <html lang='es'>
          <head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1'>
              <title>Registro de Compra</title>
              <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' rel='stylesheet'>
              <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
          </head>
          <body>
              <script>
                  Swal.fire({
                      icon: 'success',
                      title: 'Compra registrada',
                      text: 'La compra se ha registrado con éxito.',
                      showConfirmButton: false,
                      timer: 2000
                  }).then(() => {
                      window.location.href = 'compras.php';
                  });
              </script>
          </body>
          </html>";
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    mysqli_rollback($conexion);

    // Incluir los scripts de SweetAlert2 y Bootstrap
    echo "<!DOCTYPE html>
          <html lang='es'>
          <head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1'>
              <title>Error</title>
              <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' rel='stylesheet'>
              <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
          </head>
          <body>
              <script>
                  Swal.fire({
                      icon: 'error',
                      title: 'Error',
                      text: '" . addslashes($e->getMessage()) . "',
                      showConfirmButton: true
                  }).then(() => {
                      window.location.href = 'compras.php';
                  });
              </script>
          </body>
          </html>";
}

// Cerrar conexión
mysqli_close($conexion);
?>
