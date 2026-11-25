<?php
$accion = $this->paginaActual();   // 'login' si pidio una pagina protegida sin sesion
if (!function_exists('claseActiva')) {
    function claseActiva($pagina, $actual) {
        return $pagina === $actual ? ' class="activo"' : '';
    }
}
?>
<html>
	<head>
		<title>CuartoA</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/estilos.css">
		<link rel="stylesheet" href="css/menu.css">
		<?php if ($accion !== 'servicios') { ?>
		<!-- jQuery se carga aquí en todas las páginas, menos en Servicios, que ya carga el suyo -->
		<script src="jquery/jquery.min.js"></script>
		<?php } ?>
	</head>
	<body>
		<header><img src="images/banner.png" width="100%" height="30%" alt="Banner"></header>

		<nav id="menu">
			<button id="btnMenu" type="button">&#9776; Menú</button>
			<ul>
				<li><a href="index.php?accion=inicio"<?php echo claseActiva('inicio', $accion); ?>>Inicio</a></li>
				<li><a href="index.php?accion=nosotros"<?php echo claseActiva('nosotros', $accion); ?>>Nosotros</a></li>
				<li><a href="index.php?accion=contactanos"<?php echo claseActiva('contactanos', $accion); ?>>Contáctanos</a></li>
				<li><a href="index.php?accion=servicios"<?php echo claseActiva('servicios', $accion); ?>>Servicios</a></li>
				<?php if (isset($_SESSION['usuario'])) { ?>
				<li><a href="index.php?accion=logout">Cerrar sesión</a></li>
				<?php } else { ?>
				<li><a href="index.php?accion=login"<?php echo claseActiva('login', $accion); ?>>Iniciar sesión</a></li>
				<?php } ?>
			</ul>
		</nav>

		<?php if (isset($_SESSION['usuario'])) { ?>
		<!-- Quién está conectado, desde qué hora y su id de sesión -->
		<div style="background:#f3e3e3; color:#5a2d2d; padding:8px 15px; font-size:14px; text-align:center; font-family:Arial, Helvetica, sans-serif;">
			Conectado: <b><?php echo htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8'); ?></b>
			&nbsp;|&nbsp; Ingreso: <b><?php echo htmlspecialchars($_SESSION['hora_login'], ENT_QUOTES, 'UTF-8'); ?></b>
			&nbsp;|&nbsp; Sesión: <b><?php echo htmlspecialchars(substr(session_id(), 0, 10), ENT_QUOTES, 'UTF-8'); ?></b>
			&nbsp;|&nbsp; Se cierra tras <?php echo (INACTIVIDAD_SEGUNDOS / 60); ?> min sin actividad
		</div>
		<?php } ?>

		<section>
			<?php
			$this->enlacesPaginasController();
			?>
		</section>

		<footer> Derechos reservados @CuartoA </footer>

		<!-- Menú con jQuery: se despliega en celular y se reinicia al agrandar la ventana -->
		<script>
			jQuery(function ($) {
				var $lista = $('#menu ul');
				$('#btnMenu').on('click', function () {
					$lista.stop(true, true).slideToggle(200);
				});
				$(window).on('resize', function () {
					if ($(window).width() > 700) {
						$lista.removeAttr('style');
					}
				});
			});
		</script>

		<?php if (isset($_SESSION['usuario'])) { ?>
		<!-- Cierre por inactividad: si no hay actividad, el servidor cierra la sesión -->
		<script>
			(function () {
				var limite = <?php echo INACTIVIDAD_SEGUNDOS; ?> * 1000 + 1000;
				var temporizador;
				function reiniciar() {
					clearTimeout(temporizador);
					temporizador = setTimeout(function () {
						window.location = 'index.php?accion=inactividad';
					}, limite);
				}
				['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(function (evento) {
					document.addEventListener(evento, reiniciar, true);
				});
				reiniciar();
			})();
		</script>
		<?php } ?>
	</body>
</html>