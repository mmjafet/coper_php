<?php
    session_start();

    // Verifica si el usuario no ha iniciado sesión
    if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 2) {
        // Redirige al usuario a la página de inicio de sesión
        header("Location: login.php");
        exit();
    }

    // Incluir el archivo que establece la conexión a la base de datos
    require_once 'conexion/conectar-mysql.php';

    // Verificar si hay una variable de sesión para el carrito, si no existe, crearla como un array vacío
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }

    // Función para agregar un producto al carrito
    function agregarAlCarrito($codigo_producto) {
        global $conexion;

        // Verificar si el producto ya está en el carrito
        if (isset($_SESSION['carrito'][$codigo_producto])) {
            // Si ya existe, incrementar la cantidad
            $_SESSION['carrito'][$codigo_producto]['cantidad']++;
        } else {
            // Si no existe, obtener detalles del producto y agregarlo al carrito con cantidad 1
            $sql = "SELECT Nombre_P, Precio FROM producto WHERE Codigo_Producto = ? AND status = '1'";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $codigo_producto);
            $stmt->execute();
            $stmt->bind_result($nombre, $precio);
            $stmt->fetch();
            $stmt->close();

            $_SESSION['carrito'][$codigo_producto] = [
                'codigo_producto' => $codigo_producto,
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => 1
            ];
        }
    }

    // Verificar si se recibió un código de producto para agregar al carrito
    if (isset($_GET['agregar']) && !empty($_GET['agregar'])) {
        agregarAlCarrito($_GET['agregar']);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Obtener el número total de productos en el carrito
    $num_productos_carrito = count($_SESSION['carrito']);
    $total_venta = 0;

    // Función para calcular el total de la venta
    function calcularTotalVenta() {
        $total = 0;
        foreach ($_SESSION['carrito'] as $item) {
            $total += $item['cantidad'] * $item['precio'];
        }
        return $total;
    }

    // Calcular el total de la venta solo si hay productos en el carrito
    if ($num_productos_carrito > 0) {
        $total_venta = calcularTotalVenta();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta por Mayoreo de Bebidas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa; /* Fondo gris claro */
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            background-color: #343a40; /* Navbar color oscuro */
            margin-bottom: 20px;
        }
        .navbar-brand {
            color: #fff; /* Texto blanco para el enlace de la marca */
            font-weight: bold;
        }
        .navbar-brand:hover {
            color: #ffc107; /* Cambio de color al pasar el mouse */
        }
        .container {
            max-width: 1200px; /* Ancho máximo para el contenido */
        }
        .card {
            border: none; /* Sin borde en las tarjetas */
            border-radius: 10px;
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05); /* Escalar al pasar el mouse */
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .card img {
            max-width: 100%;
            height: auto;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card-body {
            padding: 1.5rem;
            text-align: center;
        }
        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }
        .card-text {
            color: #555;
        }
        .carrito-container {
            position: fixed;
            top: 70px; /* Ajuste para evitar solapamiento con el navbar */
            right: 10px;
            background-color: white;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .modal-confirmation {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            z-index: 1050;
            text-align: center;
        }
        .modal-confirmation.show {
            display: block;
        }
        .modal-confirmation p {
            margin: 0;
            padding: 10px 0;
        }
        .btn-cerrar-sesion {
            margin-bottom: 10px; /* Espacio entre el botón de cerrar sesión y el carrito */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Venta de Bebidas</a>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="cerrar_sesion.php" class="btn btn-danger btn-cerrar-sesion">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1 class="text-center">Nuestros Productos</h1>
        <div class="row">
            <?php
            // Consulta SQL para obtener los productos activos
            $sql = "SELECT * FROM producto WHERE status = '1' and Existencias >= Stock_Minimo";
            $result = $conexion->query($sql);

            // Verificar si hay productos encontrados
            if ($result->num_rows > 0) {
                // Iterar sobre los resultados y mostrar cada producto en una tarjeta
                while($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-3">';
                    echo '<div class="card">';
                    // Mostrar imagen si está presente en la base de datos
                    if (!empty($row['Imagen'])) {
                        $imagen_base64 = base64_encode($row['Imagen']);
                        echo "<img src='data:image/jpeg;base64,$imagen_base64' class='card-img-top' alt='Imagen de producto'>";
                    }
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($row['Nombre_P']) . '</h5>';
                    echo '<p class="card-text">Descripción: ' . htmlspecialchars($row['Descripcion']) . '</p>';
                    echo '<p class="card-text">Existencias: ' . htmlspecialchars($row['Existencias']) . '</p>';
                    echo '<p class="card-text">Precio: $' . htmlspecialchars($row['Precio']) . '</p>';
                    // Agregar botón para agregar al carrito
                    echo '<a href="?agregar=' . htmlspecialchars($row['Codigo_Producto']) . '" class="btn btn-warning agregar-carrito" data-producto="' . htmlspecialchars($row['Nombre_P']) . '">Agregar al carrito</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="col">No se encontraron productos.</p>';
            }
            ?>
        </div>
    </div>

    <!-- Mostrar cantidad de productos en el carrito y total de la venta -->
    <div class="carrito-container">
        <h3>Carrito de Compras</h3>
        <p>Número de productos: <?php echo $num_productos_carrito; ?></p>
        <p>Total de la venta: $<?php echo number_format($total_venta, 2); ?></p>
        <a href="detalle_carrito.php" class="btn btn-info">Ver Detalles del Carrito</a>
    </div>

    <!-- Modal de confirmación -->
    
    <script>
        // Función para cerrar el modal de confirmación
        function closeModal() {
            var modal = document.querySelector('.modal-confirmation');
            modal.classList.remove('show');
        }

        // Mostrar modal de confirmación al agregar un producto al carrito
        var agregarBotones = document.querySelectorAll('.agregar-carrito');
        agregarBotones.forEach(function(boton) {
            boton.addEventListener('click', function(event) {
                var producto = event.target.getAttribute('data-producto');
                var modal = document.querySelector('.modal-confirmation');
                modal.classList.add('show');
                event.preventDefault(); // Evitar que se siga el enlace
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

