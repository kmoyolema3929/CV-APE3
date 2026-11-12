<?php
    include "conexion.php";
    $cedula = $_GET["cedula"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $direccion = $_POST["direccion"];
    $telefono = $_POST["telefono"];
    $sql = "update estudiantes set nombre='$nombre', apellido='$apellido', direccion='$direccion', telefono='$telefono' 
    where cedula = '$cedula'";

    if($conn -> query($sql) == true) { 
        echo (json_encode(["success"=>"Se edito"]));
    }
    else {
        echo (json_encode(["error"=>"No se edito"]));
    }
?>