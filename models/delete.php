<?php
    include "conexion.php";
    $cedula = $_POST["cedula"];
    $sql = "delete from estudiantes where cedula = '$cedula'";

    if($conn -> query($sql) == true) { 
        echo (json_encode(["success"=>"Se elimino"]));
    }
    else {
       echo (json_encode(["error"=>"No se elimino"]));
    }
?>