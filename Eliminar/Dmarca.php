<?php
session_start();

// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
        // Redirige al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}
    if(isset($_GET['id'])){
      $id = $_GET['id'];
      include("../conexion/conectar-mysql.php");
      $sql = "UPDATE marca SET status='0' WHERE Id_Marca = $id";
      $ejecConsulta = mysqli_query($conexion,$sql);
      if($ejecConsulta){
        header("location:../marca.php");
    } else {
        echo mysqli_error($conexion);    
    }
    mysqli_close($conexion);
    }
      
?>