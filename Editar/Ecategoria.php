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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgregarCategoria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="content m-5">
        <h2>Edita una Categoria</h2>

    <?php
        if(isset($_GET['id'])){
      $id = $_GET['id'];
      include("../conexion/conectar-mysql.php");
      $sql = "SELECT * FROM categoria WHERE Id_Categoria = $id";
      $ejecConsulta = mysqli_query($conexion,$sql);
      $datPre = mysqli_fetch_array($ejecConsulta);
        }
      
    ?>

        <form action="./Ecategoria.php" method="post">

            <label class="form-label"># <?php echo $datPre[0] ?></label>
            <br>
            <label class="form-label">Nombre</label>
            <input name="txtNom" class="form-control" type="text" id="txtNom" value="<?php echo $datPre[1] ?>">
            <br>
            <label class="form-label">Descripcion</label>
            <input name="txtDes" class="form-control" type="text" id="txtDes" value="<?php echo $datPre[2] ?>">
            
            <div class="mt-4">
            <button class="btn btn-primary" type="submit" name="Registrar">Actualizar</button>
            <input type="hidden" name="id" value="<?php echo $datPre[0] ?>">
            <a href="../categoria.php" class="btn btn-primary" type="reset">Cancelar</a>
            </div>
        </form>
    </div>

    <?php
        if(isset($_REQUEST['Registrar'])){
            $id = $_POST['id'];
            $nom = $_POST['txtNom'];
            $des = $_POST['txtDes'];
            include("../conexion/conectar-mysql.php");
            echo $sql = "CALL edit_categoria($id,'$nom','$des')";
            $ejecConsulta = mysqli_query($conexion,$sql);
            if($ejecConsulta){
                header("location:../categoria.php");
            } else {
                echo mysqli_error($conexion);    
            }
            mysqli_close($conexion);
        }
    ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>