<?php
    class PaginasController{

        // Paginas que solo se ven con sesion iniciada
        private $protegidas = array('nosotros', 'servicios');
        private $errorLogin = '';

        // Inicia o cierra sesion. Se llama en index.php ANTES de mostrar HTML
        public function procesarSesion(){
            $accion = isset($_GET['accion']) ? $_GET['accion'] : '';

            if ($accion === 'logout') {
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
                $nombre = Usuario::validar($usuario, $clave);
                if ($nombre !== false) {
                    session_regenerate_id(true);
                    $_SESSION['usuario'] = $nombre;
                    header("Location: index.php?accion=" . $this->destinoLogin());
                    exit;
                }
                $this->errorLogin = "Usuario o contraseña incorrectos";
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
            $conSesion = isset($_SESSION['usuario']);
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
            $respuesta=enlacesPagina::enlacePaginaModel($accion);
            include $respuesta;
        }
        public function getTemplate(){
            include "views/template.php";
        }
    }
?>