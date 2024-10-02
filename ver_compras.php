<?php
// Incluir el archivo de conexión a la base de datos
require_once 'conexion/conectar-mysql.php';

// Consulta para obtener todas las compras con sus detalles
$sql = "SELECT c.Folio_Compra, c.Total_Pagar, c.Fecha, p.Nombre AS NombreProveedor, dc.Cantidad, dc.Precio_Compra, prod.Nombre_P AS NombreProducto
        FROM compra c
        INNER JOIN proveedor p ON c.Id_Proveedor = p.Id_Proveedor
        INNER JOIN detalle_compra dc ON c.Folio_Compra = dc.Folio_Compra
        INNER JOIN producto prod ON dc.Codigo_Producto = prod.Codigo_Producto
        ORDER BY c.Fecha DESC";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Compras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
 
  <style>
        html, body {
            height: 100%;
            background-color: rgb(123, 129, 135);
        }


    </style>

</head>

<body>
    <!-- Barra lateral para navegación -->
    <main class="d-flex flex-nowrap h-100">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark m-3 elevation-4" style="width: 280px;">
            <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4">DÉPOSITO</span>
                </a>
                <a href="cerrar_sesion.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4">Cerrar sesion</span>
                </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                
                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed text-white" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                        <i class="fa-solid fa-chevron-down"></i>
                        Caracteristicas generales
                    </button>
                    <div class="collapse" id="home-collapse">
                      <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="presentacion.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Presentación</a></li>
                        <li><a href="categoria.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Categoría</a></li>
                        <li><a href="marca.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Marca</a></li>
                      </ul>
                    </div>
                </li>

                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed text-white" data-bs-toggle="collapse" data-bs-target="#comp-collapse" aria-expanded="false">
                        <i class="fa-solid fa-chevron-down"></i>  Compras
                    </button>
                    <div class="collapse" id="comp-collapse">
                      <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="ver_compras.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Ver Compra</a></li>
                        <li><a href="compra.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Realizar compra</a></li>
                        <li><a href="proveedor.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Proveedores</a></li>
                      </ul>
                    </div>
                </li>
                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed text-white" data-bs-toggle="collapse" data-bs-target="#pro-collapse" aria-expanded="false">
                        <i class="fa-solid fa-chevron-down"></i> Productos
                    </button>
                    <div class="collapse" id="pro-collapse">
                      <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="producto.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Agregar producto</a></li>
                        <li><a href="existencias.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Existencias</a></li>
                      </ul>
                    </div>
                </li>

                <li class="nav-item"><a href="VerVenta.php" class="nav-link text-white"><i class="fas fa-shopping-cart"></i> Ver Ventas</a></li>
                <li class="nav-item"><a href="RealizarVenta.php" class="nav-link text-white"><i class="fas fa-shopping-cart"></i> Realizar Venta</a></li>
                <li class="border-top my-3"></li>

                <li class="nav-item"><a href="usuarios.php" class="nav-link text-white"><i class="fas fa-user-tie"></i> Usuarios</a></li>
            </ul>
        </div>

<div class="container mt-5">
  <h2>Lista de Compras</h2>
  <table class="table">
    <thead>
      <tr>
        <th>Folio de Compra</th>
        <th>Total a Pagar</th>
        <th>Fecha</th>
        <th>Proveedor</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio de Compra</th>
      </tr>
    </thead>
    <tbody>
      <?php
      // Iterar sobre el resultado de la consulta y mostrar cada compra y sus detalles
      while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $fila['Folio_Compra'] . "</td>";
        echo "<td>" . $fila['Total_Pagar'] . "</td>";
        echo "<td>" . $fila['Fecha'] . "</td>";
        echo "<td>" . $fila['NombreProveedor'] . "</td>";
        echo "<td>" . $fila['NombreProducto'] . "</td>";
        echo "<td>" . $fila['Cantidad'] . "</td>";
        echo "<td>" . $fila['Precio_Compra'] . "</td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/20a0f372fc.js" crossorigin="anonymous"></script>
    

</body>
</html>

<?php
// Cerrar la conexión
$conexion->close();
?>
