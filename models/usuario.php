<?php
require_once __DIR__ . "/sesion.php";

class Usuario{
    // Devuelve un arreglo: ok (true/false), usuario y mensaje
    public static function validar($usuario, $clave){
        include __DIR__ . "/conexion.php";   // crea la variable $conn
        $resultado = array('ok' => false, 'usuario' => '', 'mensaje' => 'Usuario o contraseña incorrectos');

        $stmt = $conn->prepare("select usuario, clave, intentos, bloqueado_hasta from usuarios where usuario = ? limit 1");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->bind_result($nombre, $hash, $intentos, $bloqueadoHasta);

        if (!$stmt->fetch()) {
            // El usuario no existe
            $stmt->close();
            $conn->close();
            registrarAuditoria($usuario, 'LOGIN_FALLIDO');
            return $resultado;
        }
        $stmt->close();

        // ¿Está bloqueado?
        if ($bloqueadoHasta !== null && strtotime($bloqueadoHasta) > time()) {
            $faltan = strtotime($bloqueadoHasta) - time();
            $conn->close();
            $resultado['mensaje'] = "Usuario bloqueado por demasiados intentos. Intente de nuevo en " . $faltan . " segundos.";
            return $resultado;
        }
        if ($bloqueadoHasta !== null) {
            $intentos = 0;   // el bloqueo ya terminó
        }

        if (password_verify($clave, $hash)) {
            // Clave correcta: se reinician los intentos
            $upd = $conn->prepare("update usuarios set intentos = 0, bloqueado_hasta = NULL where usuario = ?");
            $upd->bind_param("s", $nombre);
            $upd->execute();
            $upd->close();
            $conn->close();
            $resultado['ok'] = true;
            $resultado['usuario'] = $nombre;
            $resultado['mensaje'] = '';
            return $resultado;
        }

        // Clave incorrecta
        $intentos++;
        if ($intentos >= MAX_INTENTOS) {
            $hasta = date('Y-m-d H:i:s', time() + BLOQUEO_SEGUNDOS);
            $upd = $conn->prepare("update usuarios set intentos = 0, bloqueado_hasta = ? where usuario = ?");
            $upd->bind_param("ss", $hasta, $nombre);
            $upd->execute();
            $upd->close();
            $conn->close();
            registrarAuditoria($nombre, 'LOGIN_FALLIDO');
            registrarAuditoria($nombre, 'BLOQUEO');
            $resultado['mensaje'] = "Superó " . MAX_INTENTOS . " intentos. Usuario bloqueado por " . BLOQUEO_SEGUNDOS . " segundos.";
        } else {
            $upd = $conn->prepare("update usuarios set intentos = ?, bloqueado_hasta = NULL where usuario = ?");
            $upd->bind_param("is", $intentos, $nombre);
            $upd->execute();
            $upd->close();
            $conn->close();
            registrarAuditoria($nombre, 'LOGIN_FALLIDO');
            $resultado['mensaje'] = "Usuario o contraseña incorrectos. Intentos restantes: " . (MAX_INTENTOS - $intentos);
        }
        return $resultado;
    }
}
?>