<?php
// Inicia la sesión si aún no se ha iniciado
session_start();

// Destruye la sesión actual
session_destroy();

// Redirige al usuario a la página de inicio de sesión
header("Location: login.php");
exit();
?>
