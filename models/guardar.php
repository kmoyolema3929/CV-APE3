<?php
include "conexion.php";
$cedula=$_POST['cedula'];
$nombre=$_POST['nombre'];
$apellido=$_POST['apellido'];
$direccion=$_POST['direccion'];
$telefono=$_POST['telefono'];
$sql="insert into estudiantes values('$cedula','$nombre','$apellido','$direccion','$telefono')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => "Se guardo"]);
} else {
    echo json_encode(["success" => "No se guardo"]);
}
?>