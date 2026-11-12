<?php
$servername="localhost";
$username="root";
$password="";
$database="cuartoa";
$conn=mysqli_connect($servername, $username, $password, $database);
//$mysql=new mysql($servername, $username, $password, $database); Con cualquiera de las dos se puede conectar

    if(!$conn){
        die("No se conecto".error.mysqli_connect_error());
    }

?>
