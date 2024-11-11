<?php
    $servidor = "colocar el puerto de en donde esta la base de datos";
    $usuario = "pon el usuario";
    $nomBaseDatos = "project";
    $password= "";
    // Conectando a la base de datos
    $conexion = mysqli_connect($servidor,$usuario,$password, $nomBaseDatos);
    if($conexion){
        #echo "si se pudo may :)";
    }else{
        #echo "no se pudp wey";
    }

?>
