<?php
    class PaginasController{

        // Paginas que solo se ven con sesion iniciada
        private $protegidas = array('nosotros', 'servicios');
        private $errorLogin = '';

        // Inicia o cierra sesion. Se llama en index.php ANTES de mostrar HTML
        public function procesarSesion(){
            $accion = isset($_GET['accion']) ? $_GET['accion'] : '';
            $conSesion = haySesion();   // aqui se detecta el cierre por inactividad

            // Lo pide el navegador cuando pasa el tiempo sin actividad
            if ($accion === 'inactividad') {
                header("Location: index.php?accion=" . ($conSesion ? 'inicio' : 'login'));
                exit;
            }

            if ($accion === 'logout') {
                if ($conSesion) {
                    registrarAuditoria($_SESSION['usuario'], 'CIERRE_SESION');
                }
                $_SESSION = array();
                if (ini_get("session.use_cookies")) {
                    $p = session_get_cookie_params();
                    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
                }
                session_destroy();
                header("Location: index.php?accion=inicio");
                exit;
            }

            if ($accion === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
                $clave = isset($_POST['clave']) ? $_POST['clave'] : '';
                $r = Usuario::validar($usuario, $clave);
                if ($r['ok']) {
                    session_regenerate_id(true);
                    $_SESSION['usuario'] = $r['usuario'];
                    $_SESSION['hora_login'] = date('H:i:s');
                    $_SESSION['ultima_actividad'] = time();
                    registrarAuditoria($r['usuario'], 'INGRESO');
                    header("Location: index.php?accion=" . $this->destinoLogin());
                    exit;
                }
                $this->errorLogin = $r['mensaje'];
            }
        }

        // A que pagina volver despues de iniciar sesion
        private function destinoLogin(){
            if (isset($_POST['destino'])) {
                $d = $_POST['destino'];
            } elseif (isset($_GET['accion'])) {
                $d = $_GET['accion'];
            } else {
                $d = 'inicio';
            }
            return in_array($d, array('inicio', 'nosotros', 'contactanos', 'servicios')) ? $d : 'inicio';
        }

        // Pagina que realmente se va a mostrar (aplica la proteccion)
        public function paginaActual(){
            $accion = isset($_GET['accion']) ? $_GET['accion'] : 'inicio';
            if (!in_array($accion, array('inicio', 'nosotros', 'contactanos', 'servicios', 'login'))) {
                $accion = 'inicio';
            }
            $conSesion = haySesion();
            if (in_array($accion, $this->protegidas) && !$conSesion) {
                $accion = 'login';
            }
            if ($accion === 'login' && $conSesion) {
                $accion = 'inicio';
            }
            return $accion;
        }

        public function enlacesPaginasController(){
            $accion = $this->paginaActual();
            $error = $this->errorLogin;          // los usa views/login.php
            $destino = $this->destinoLogin();    // los usa views/login.php
            $aviso = isset($_SESSION['aviso']) ? $_SESSION['aviso'] : '';   // aviso de inactividad
            unset($_SESSION['aviso']);
            $respuesta=enlacesPagina::enlacePaginaModel($accion);
            include $respuesta;
        }
        public function getTemplate(){
            include "views/template.php";
        }
    }
?>