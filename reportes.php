<?php
include "fpdf19/fpdf.php";
include "models/conexion.php";

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetTitle("Cuarto A");
$pdf->SetFont("Arial","B",12);
$pdf-> cell(25,10,"CEDULA",1);
$pdf-> cell(25,10,"NOMBRE",1);
$pdf-> cell(30,10,"APELLIDO",1);
$pdf-> cell(30,10,"DIRECCION",1);
$pdf-> cell(30,10,"TELEFONO",1);

$pdf->Ln();

// ---- NUEVO: si llega la cedula por GET, solo ese estudiante; si no, todos ----
$cedulaFiltro = isset($_GET['cedula']) ? trim($_GET['cedula']) : '';
if ($cedulaFiltro !== '') {
    $cedulaFiltro = $conn->real_escape_string($cedulaFiltro);
    $sql = "select * from estudiantes where cedula = '$cedulaFiltro'";
} else {
    $sql = "select * from estudiantes";
}

$respuesta=$conn->query($sql);
if($respuesta->num_rows>0)
    {
    while($fila=$respuesta->fetch_array()){
        $cedula = $fila['cedula'];
        $nombre = $fila['nombre'];
        $apellido = $fila['apellido'];
        $direccion = $fila['direccion'];
        $telefono = $fila['telefono'];

        $pdf-> cell(25,10,$cedula,1);
        $pdf-> cell(25,10,$nombre,1);
        $pdf-> cell(30,10,$apellido,1);
        $pdf-> cell(30,10,$direccion,1);
        $pdf-> cell(30,10,$telefono,1);

        $pdf->Ln();
    }
}

$pdf->Output();

?>