<?php
date_default_timezone_set('America/Guayaquil');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---- Configuración de seguridad ----
define('MAX_INTENTOS', 3);            // intentos fallidos permitidos
define('BLOQUEO_SEGUNDOS', 60);       // tiempo de bloqueo del usuario
define('INACTIVIDAD_SEGUNDOS', 120);  // cierre de sesión por inactividad

// Guarda un registro en la tabla auditoria
function registrarAuditoria($usuario, $accion) {
    try {
        include __DIR__ . '/conexion.php';   // crea $conn
        $fecha = date('Y-m-d H:i:s');
        $idSesion = substr(session_id(), 0, 10);   // solo el inicio del id (el id completo es secreto)
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        $usuario = substr($usuario, 0, 50);
        $stmt = $conn->prepare("insert into auditoria (usuario, accion, fecha_hora, id_sesion, ip) values (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $usuario, $accion, $fecha, $idSesion, $ip);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    } catch (Throwable $e) {
        error_log("Auditoria: " . $e->getMessage());
    }
}

// Cierra la sesión por inactividad y deja un aviso para mostrarlo en el login
function cerrarPorInactividad() {
    registrarAuditoria($_SESSION['usuario'], 'CIERRE_INACTIVIDAD');
    $_SESSION = array();
    session_destroy();
    session_start();
    session_regenerate_id(true);
    $_SESSION['aviso'] = 'Su sesión se cerró por inactividad. Inicie sesión nuevamente.';
}

// Indica si hay sesión activa. Aquí se controla la inactividad
function haySesion() {
    if (!isset($_SESSION['usuario'])) {
        return false;
    }
    $ahora = time();
    if (isset($_SESSION['ultima_actividad']) && ($ahora - $_SESSION['ultima_actividad']) >= INACTIVIDAD_SEGUNDOS) {
        cerrarPorInactividad();
        return false;
    }
    $_SESSION['ultima_actividad'] = $ahora;
    return true;
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