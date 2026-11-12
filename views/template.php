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
				<li><a href="index.php?accion=logout" title="Sesión de <?php echo htmlspecialchars($_SESSION['usuario'], ENT_QUOTES, 'UTF-8'); ?>">Cerrar sesión</a></li>
				<?php } else { ?>
				<li><a href="index.php?accion=login"<?php echo claseActiva('login', $accion); ?>>Iniciar sesión</a></li>
				<?php } ?>
			</ul>
		</nav>

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
	</body>
</html>