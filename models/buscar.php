<?php
include "conexion.php";

if (isset($_POST['cedula'])) {
    $cedula = $_POST['cedula'];
} else {
    $cedula = $_GET['cedula'];
}

$sql = "select * from estudiantes where cedula = '$cedula'";
$respuesta = $conn->query($sql);
$resultado = array();

if ($respuesta->num_rows > 0) {
    while ($fila = $respuesta->fetch_array()) {
        array_push($resultado, $fila);
    }
}

print_r(json_encode($resultado));
?>