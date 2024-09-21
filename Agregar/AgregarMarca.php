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
        <h2>Agrega una Marca</h2>
        <form action="./AgregarMarca.php" method="post">

            <label class="form-label">Nombre</label>
            <input name="txtNom" class="form-control" type="text" placeholder="Nombre marca" id="txtNom">

            <label class="form-label">Descripcion</label>
            <input name="txtDes" class="form-control" type="text" placeholder="Descripcion" id="txtDes">
            
            <div class="mt-4">
            <button class="btn btn-primary" type="submit" name="Registrar">Registrar</button>
            <a href="../marca.php" class="btn btn-primary" type="reset">Cancelar</a>
            </div>
        </form>
    </div>

    <?php
        if(isset($_REQUEST['Registrar'])){
            $nom = $_POST['txtNom'];
            $des = $_POST['txtDes'];
            include("../conexion/conectar-mysql.php");
            $sql = "CALL insert_marca('$nom','$des')";
            $ejecConsulta = mysqli_query($conexion,$sql);
            if($ejecConsulta){
                header("location:../marca.php");
            } else {
                echo mysqli_error($conexion);    
            }
            mysqli_close($conexion);
        }
    ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>