<?php
session_start();
// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
    // Redirige al usuario a la página de inicio de sesión
header("Location: login.php");
exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro de Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        html, body {
            height: 100%;
            background-color: rgb(230, 234, 240);
        }
        .sidebar .nav-link,
        .sidebar .btn-toggle {
            color: #d1e7ff;
        }
        .sidebar .nav-link:hover,
        .sidebar .btn-toggle:hover {
            color: #fff;
        }
        .sidebar .btn-toggle:focus {
            box-shadow: none;
        }
        .content {
            flex-grow: 1;
        }
        .card-link {
            text-decoration: none;
            color: #495057;
        }
        .card-link:hover {
            text-decoration: underline;
            color: #007bff;
        }
    </style>
</head>
<body>
    <!-- Barra lateral para navegación -->
    <main class="d-flex flex-nowrap h-100">
        <div class="d-flex flex-column flex-shrink-0 p-4 sidebar bg-dark m-3">
            <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
                <span class="fs-4 text-white fa-solid fa-wine-bottle"> DEPOSITO</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 text-white" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="false">
                        <i class="fas fa-chevron-down me-2 text-"></i> Características generales
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
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 text-white" data-bs-toggle="collapse" data-bs-target="#comp-collapse" aria-expanded="true">
                        <i class="fas fa-chevron-down me-2"></i> Compras
                    </button>
                    <div class="collapse show" id="comp-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="mostrar_compras.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Ver Compra</a></li>
                            <li><a href="compras.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-dark fw-bold bg-white">Realizar compra</a></li>
                            <li><a href="proveedor.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Proveedores</a></li>
                        </ul>
                    </div>
                </li>
                <li class="mb-1">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed text-white" data-bs-toggle="collapse" data-bs-target="#pro-collapse" aria-expanded="false">
                        <i class="fas fa-chevron-down me-2"></i> Productos
                    </button>
                    <div class="collapse" id="pro-collapse">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="producto.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Agregar producto</a></li>
                            <li><a href="existencias.php" class="link-body-emphasis d-inline-flex text-decoration-none rounded text-white">Existencias</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item"><a href="ver_ventas.php" class="nav-link text-white"><i class="fas fa-shopping-cart me-2 text-white"></i> Ver Ventas</a></li>
                <li class="border-top my-3"></li>
                <li class="nav-item"><a href="usuarios.php" class="nav-link text-white"><i class="fas fa-user-tie me-2 text-white"></i> Usuarios</a></li>
            </ul>
            <a href="cerrar_sesion.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="fs-4 text-white btn btn-danger">Cerrar sesión</span>
            </a>
        </div>

    <div class="container">
        <h1 class="mt-5">Realizar Compra</h1>
        <form id="formCompra" action="procesar_compra.php" method="POST">
            <fieldset class="border p-4 mb-4">
                <legend class="w-auto">Detalles de la Compra</legend>
                <div class="form-group">
                    <label for="folio_compra">Folio de Compra:</label>
                    <input type="text" class="form-control" id="folio_compra" name="folio_compra" readonly>
                </div>
                <div class="form-group">
                    <label for="total_pagar">Total a Pagar:</label>
                    <input type="number" step="0.01" class="form-control" id="total_pagar" name="total_pagar" required readonly>
                </div>
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>
                <div class="form-group">
                    <label for="id_proveedor">ID del Proveedor:</label>
                    <select class="form-control" id="id_proveedor" name="id_proveedor" required>
                        <option value="">Seleccione un proveedor</option>
                        <?php
                        require_once 'conexion/conectar-mysql.php';
                        $query = "SELECT Id_Proveedor, Nombre FROM proveedor WHERE status = '1'";
                        $result = mysqli_query($conexion, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='{$row['Id_Proveedor']}'>{$row['Nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="status" value="1"> <!-- Campo oculto para status -->
            </fieldset>

            <fieldset class="border p-4 mb-4">
                <legend class="w-auto">Detalles del Producto</legend>
                <div id="productos">
                    <div class="producto form-row mb-2" id="producto_1">
                        <div class="form-group col-md-3">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" class="form-control cantidad" name="cantidad[]" required onchange="validarCantidad(this)">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="precio_compra">Precio de Compra:</label>
                            <input type="number" step="0.01" class="form-control precio_compra" name="precio_compra[]" required onchange="validarPrecio(this)">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="codigo_producto">Código del Producto:</label>
                            <select class="form-control codigo_producto" name="codigo_producto[]" required>
                                <option value="">Seleccione un producto</option>
                                <?php
                                $query = "SELECT Codigo_Producto, Nombre_P, Stock_Maximo FROM producto WHERE status = '1'";
                                $result = mysqli_query($conexion, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='{$row['Codigo_Producto']}' data-stock-maximo='{$row['Stock_Maximo']}'>{$row['Nombre_P']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3 mt-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger" onclick="eliminarProducto(this)">Eliminar</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" onclick="agregarProducto()">Agregar Producto</button>
            </fieldset>

            <button type="submit" class="btn btn-primary">Registrar Compra</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
    function actualizarTotal() {
        let total = 0;
        document.querySelectorAll('.producto').forEach(function(producto) {
            const cantidad = parseFloat(producto.querySelector('.cantidad').value) || 0;
            const precio = parseFloat(producto.querySelector('.precio_compra').value) || 0;
            total += cantidad * precio;
        });
        document.getElementById('total_pagar').value = total.toFixed(2);
    }

    function agregarProducto() {
        const productosDiv = document.getElementById('productos');
        const nuevoProductoIndex = productosDiv.children.length + 1;

        // Crear y agregar el nuevo producto
        const productoDiv = document.createElement('div');
        productoDiv.className = 'producto form-row mb-2';
        productoDiv.id = 'producto_' + nuevoProductoIndex;

        productoDiv.innerHTML = `
            <div class="form-group col-md-3">
                <label for="cantidad">Cantidad:</label>
                <input type="number" class="form-control cantidad" name="cantidad[]" required onchange="actualizarTotal()">
            </div>
            <div class="form-group col-md-3">
                <label for="precio_compra">Precio de Compra:</label>
                <input type="number" step="0.01" class="form-control precio_compra" name="precio_compra[]" required onchange="actualizarTotal()">
            </div>
            <div class="form-group col-md-3">
                <label for="codigo_producto">Código del Producto:</label>
                <select class="form-control codigo_producto" name="codigo_producto[]" required>
                    <option value="">Seleccione un producto</option>
                    <?php
                    $query = "SELECT Codigo_Producto, Nombre_P, Stock_Maximo FROM producto WHERE status = '1'";
                    $result = mysqli_query($conexion, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['Codigo_Producto']}' data-stock-maximo='{$row['Stock_Maximo']}'>{$row['Nombre_P']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-3 d-flex align-items-end">
                <button type="button" class="btn btn-primaryr" onclick="eliminarProducto(this)">Eliminar</button>
            </div>
        `;

        productosDiv.appendChild(productoDiv);

        // Actualizar el total al agregar un nuevo producto
        actualizarTotal();
    }

    function eliminarProducto(button) {
        const productoDiv = button.closest('.producto');
        productoDiv.remove();
        // Actualizar el total al eliminar un producto
        actualizarTotal();
    }

    function validarCantidad(input) {
        if (input.value <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'La cantidad debe ser mayor que cero.'
            });
            input.value = '';
        }
    }

    function validarPrecio(input) {
        if (input.value <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El precio debe ser mayor que cero.'
            });
            input.value = '';
        }
    }

    // Generar Folio de Compra automáticamente
    document.getElementById('folio_compra').value = new Date().toISOString().replace(/[^0-9]/g, '').slice(0, 14);

    // Verificar al enviar el formulario
    document.getElementById('formCompra').addEventListener('submit', function(event) {
        // Verificar si hay al menos un producto agregado
        const productos = document.querySelectorAll('.producto');
        if (productos.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Debe agregar al menos un producto antes de registrar la compra.'
            });
            event.preventDefault(); // Prevenir el envío del formulario
            return;
        }

        // Verificar el total a pagar
        const totalPagar = parseFloat(document.getElementById('total_pagar').value);
        if (isNaN(totalPagar) || totalPagar <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'El total a pagar debe ser mayor que cero.'
            });
            event.preventDefault(); // Prevenir el envío del formulario
            return;
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/20a0f372fc.js" crossorigin="anonymous"></script>
   

</body>
</html>
