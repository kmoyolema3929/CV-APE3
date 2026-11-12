<?php
require_once "dompdf/autoload.inc.php";
include "models/conexion.php";

use Dompdf\Dompdf;

$cedulaFiltro = isset($_GET['cedula']) ? trim($_GET['cedula']) : '';

if ($cedulaFiltro !== '') {
    $cedulaFiltro = $conn->real_escape_string($cedulaFiltro);
    $sql = "select * from estudiantes where cedula = '$cedulaFiltro'";
    $titulo = "Reporte del estudiante";
} else {
    $sql = "select * from estudiantes";
    $titulo = "Reporte de todos los estudiantes";
}

$respuesta = $conn->query($sql);

$html = '
<style>
    body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; }
    h2 { text-align: center; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 6px; }
    th { background-color: #2c3e50; color: #ffffff; }
</style>
<h2>' . $titulo . '</h2>
<p style="text-align:right">Fecha: ' . date('d/m/Y H:i') . '</p>
<table>
    <thead>
        <tr>
            <th>Cédula</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Dirección</th>
            <th>Teléfono</th>
        </tr>
    </thead>
    <tbody>';

if ($respuesta->num_rows > 0) {
    while ($fila = $respuesta->fetch_array()) {
        $html .= '<tr>
            <td>' . htmlspecialchars($fila['cedula']) . '</td>
            <td>' . htmlspecialchars($fila['nombre']) . '</td>
            <td>' . htmlspecialchars($fila['apellido']) . '</td>
            <td>' . htmlspecialchars($fila['direccion']) . '</td>
            <td>' . htmlspecialchars($fila['telefono']) . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="5" style="text-align:center">No se encontraron estudiantes</td></tr>';
}

$html .= '</tbody></table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_estudiantes.pdf", ["Attachment" => false]);
?>