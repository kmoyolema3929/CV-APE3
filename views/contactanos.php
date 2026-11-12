<?php
// COMPLETAR CON LOS DATOS DE LOS INTEGRANTES DEL GRUPO
$integrantes = array(
    array('nombre' => 'Amaguaña Villacres Shirley Dayana', 'correo' => '', 'celular' => '', 'carrera' => ''),
    array('nombre' => 'Coello Fuentes Leslie Elizabeth',   'correo' => '', 'celular' => '', 'carrera' => ''),
    array('nombre' => 'Caraguay Pucha Edwin Patricio',     'correo' => '', 'celular' => '', 'carrera' => ''),
    array('nombre' => 'Molina Alvarado Anahi Dayana',      'correo' => '', 'celular' => '', 'carrera' => ''),
    array('nombre' => 'Moyolema Criollo Katherine Lissette', 'correo' => '', 'celular' => '', 'carrera' => ''),
    array('nombre' => 'Toapanta Paucar Pablo Daniel',      'correo' => '', 'celular' => '', 'carrera' => ''),
);

if (!function_exists('ctDato')) {
    function ctDato($valor) {
        $valor = trim($valor);
        if ($valor === '') {
            return '<span class="ct-vacio">&nbsp;</span>';
        }
        return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('ctCorreo')) {
    function ctCorreo($valor) {
        $valor = trim($valor);
        if ($valor === '') {
            return '<span class="ct-vacio">&nbsp;</span>';
        }
        $v = htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
        return '<a href="mailto:' . $v . '">' . $v . '</a>';
    }
}
if (!function_exists('ctIniciales')) {
    function ctIniciales($nombre) {
        $p = preg_split('/\s+/', trim($nombre));
        $a = mb_substr($p[0], 0, 1, 'UTF-8');
        $b = isset($p[2]) ? mb_substr($p[2], 0, 1, 'UTF-8') : '';
        return mb_strtoupper($a . $b, 'UTF-8');
    }
}
?>
<style>
    .ct-wrap { max-width: 1000px; margin: 25px auto; padding: 0 15px; font-family: Arial, Helvetica, sans-serif; color: #2c3e50; }
    .ct-wrap h1 { margin-bottom: 5px; }
    .ct-sub { margin-top: 0; color: #666; }
    .ct-buscar { width: 100%; max-width: 360px; padding: 10px; margin: 10px 0 20px; border: 1px solid #bbb; border-radius: 4px; font-size: 15px; }
    .ct-grid { display: flex; flex-wrap: wrap; gap: 18px; }
    .ct-card { flex: 1 1 290px; max-width: 100%; background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 6px rgba(0,0,0,.12); border-top: 5px solid rgb(218, 150, 150); }
    .ct-avatar { width: 56px; height: 56px; border-radius: 50%; background: rgb(176, 98, 98); color: #fff; font-size: 22px; font-weight: bold; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; }
    .ct-card h3 { margin: 0 0 12px; font-size: 18px; }
    .ct-fila { margin: 8px 0; font-size: 15px; display: flex; gap: 6px; align-items: baseline; }
    .ct-fila b { min-width: 70px; }
    .ct-vacio { flex: 1; min-width: 120px; border-bottom: 1px dotted #888; display: inline-block; height: 16px; }
</style>

<div class="ct-wrap">
    <h1>Contáctanos</h1>
    <p class="ct-sub">Integrantes del grupo</p>

    <input type="text" id="ctBuscar" class="ct-buscar" placeholder="Buscar integrante por nombre...">

    <div class="ct-grid">
        <?php foreach ($integrantes as $i) { ?>
            <div class="ct-card">
                <div class="ct-avatar"><?php echo ctIniciales($i['nombre']); ?></div>
                <h3><?php echo htmlspecialchars($i['nombre'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <div class="ct-fila"><b>Correo:</b> <?php echo ctCorreo($i['correo']); ?></div>
                <div class="ct-fila"><b>Celular:</b> <?php echo ctDato($i['celular']); ?></div>
                <div class="ct-fila"><b>Carrera:</b> <?php echo ctDato($i['carrera']); ?></div>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    // Filtro por nombre con jQuery
    jQuery(function ($) {
        $('#ctBuscar').on('keyup', function () {
            var texto = $(this).val().toLowerCase();
            $('.ct-card').each(function () {
                $(this).toggle($(this).find('h3').text().toLowerCase().indexOf(texto) !== -1);
            });
        });
    });
</script>