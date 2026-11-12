<?php
$mensaje="Hola Mundo";
echo("<br>");
echo($mensaje);
echo("<br>");
print_r($mensaje);
echo("<br>");
echo(gettype($mensaje));
echo("<br>");

$numero=10;
echo("<br>");
echo($numero);
echo("<br>");
echo(gettype($numero));
echo("<br>");

$vector=array("lunes", "martes", "miercoles");
echo("<br>");
for($i=0; $i<3; $i++)
    {
        echo($vector[$i]);
    }
echo("<br>");
print_r($vector[0]);
echo("<br>");
echo(gettype($vector));
echo("<br>");

//array asociativoarray de atributos
$vectorasociativo= array("dia1" => "lunes", "dia2" => "martes", "dia3" => "miercoles");
echo("<br>");
print_r($vectorasociativo["dia3"]);
echo("<br>");

$objeto=(object)["dia1" => "lunes", "dia2" => "martes", "dia3" => "miercoles"];
echo("<br>");
print_r($objeto -> dia3);
echo("<br>");

$objetoauto1=(object)["placa"=>"tbd1111","marca"=>"mercedes","modelo"=>"rav"];
$objetoauto1=(object)["placa"=>"tbd2222","marca"=>"ford","modelo"=>"f150"];

function mostrar ()
{
    print_r($objetoauto1->placa);
}
?>