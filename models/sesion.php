<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function haySesion() {
    return isset($_SESSION['usuario']);
}

// Para los archivos que responden JSON (select, buscar, guardar, update, delete)
function exigirSesionJson() {
    if (!haySesion()) {
        http_response_code(401);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array("error" => "No autorizado. Inicie sesion"));
        exit;
    }
}

// Para los reportes PDF (se abren en otra pestana)
function exigirSesionPagina() {
    if (!haySesion()) {
        header("Location: index.php?accion=login");
        exit;
    }
}
?>