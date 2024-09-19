<?php
session_start();

// Verifica si el usuario no ha iniciado sesión
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 1) {
        // Redirige al usuario a la página de inicio de sesión
    header("Location: login.php");
    exit();
}
        if(isset($_REQUEST['Registrar'])){
            $id = $_POST['id'];
            $des = $_POST['txtDes'];
            include("../conexion/conectar-mysql.php");
            echo $sql = "CALL edit_presentacion($id,'$des')";
            $ejecConsulta = mysqli_query($conexion,$sql);
            if($ejecConsulta){
                header("location:../presentacion.php");
            } else {
                echo mysqli_error($conexion);    
            }
            mysqli_close($conexion);
        }
    ?>