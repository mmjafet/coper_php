<?php
    if(isset($_REQUEST['Registrar'])){
        $nom = $_POST['txtNombre'];
        $des = $_POST['txtDes'];
        include("../conexion/conectar-mysql.php");
        $sql = 'SELECT * FROM categoria';
                # Ejecutar consulta
                $ejecConsulta = mysqli_query($conexion,$sql);
                # Obtener datos de consulta

                while($Pre = mysqli_fetch_array($ejecConsulta)){ 
                        echo "<tr>
                        <th scope='row'>" . $Pre[0] . "</th>
                        <td>" . $Pre[1] . "</td>
                        <td>" . $Pre[2] . "</td>
                        <td>
                        <a href='./Ad_PlayerasT_Eliminar.php?id=".$Pre[0]."' class='btn btn-info mg-20'>Editar</a>
                        <a href='./Ad_PlayerasT_Eliminar.php?id=".$Pre[0]."' class='btn btn-danger mg-20'>Eliminar</a>
                        
                        </td>
                        </tr>";
                }
    }
?>