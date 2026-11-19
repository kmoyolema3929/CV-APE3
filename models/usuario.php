<?php
class Usuario{
    // Devuelve el nombre de usuario si los datos son correctos, o false si no
    public static function validar($usuario, $clave){
        include __DIR__ . "/conexion.php";   // crea la variable $conn
        $stmt = $conn->prepare("select usuario, clave from usuarios where usuario = ? limit 1");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->bind_result($nombre, $claveGuardada);
        $correcto = false;

        if ($stmt->fetch() && $clave === $claveGuardada) {
            $correcto = $nombre;
        }
        $stmt->close();
        $conn->close();
        return $correcto;
    }
}
?>