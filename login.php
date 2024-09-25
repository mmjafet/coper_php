<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <!-- Bootstrap CSS para estilos rápidos y consistentes -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome para iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Iniciar sesión</h1>
        <form id="form-login" action="procesar_login.php" method="POST">
            <div class="mb-3">
                <label for="usuario" class="form-label">Email</label>
                <input type="email" class="form-control" id="txtEmail" name="txtEmail">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="txtPassword" name="txtPassword">
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            <label class="form-label">¿No tienes cuenta? <a href="login/registrarse.php">Registrate</a></label>
        </form>
    </div>

    <!-- Font Awesome para iconos -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

    <!-- SweetAlert2 para mensajes de alerta -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#txtPassword');
            const eyeIcon = document.querySelector('#eyeIcon');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                eyeIcon.classList.toggle('fa-eye-slash');
            });

            // Captura el evento submit del formulario
            document.getElementById("form-login").addEventListener("submit", function(event) {
                // Evita que el formulario se envíe por defecto
                event.preventDefault();

                // Obtén los valores de los campos del formulario
                var email = document.getElementById("txtEmail").value;
                var password = document.getElementById("txtPassword").value;

                // Realiza las validaciones necesarias
                if (email.trim() === "" || password.trim() === "") {
                    // Si los campos están vacíos, muestra un mensaje de error con SweetAlert2
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Por favor, completa todos los campos',
                    });
                    return; // Detiene el envío del formulario
                }

                // Si pasa las validaciones, envía el formulario
                this.submit();
            });
        });
    </script>
</body>
</html>
