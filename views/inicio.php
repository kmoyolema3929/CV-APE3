<style>
    .uta-wrap { max-width: 1000px; margin: 25px auto; padding: 0 15px; font-family: Arial, Helvetica, sans-serif; color: #2c3e50; }
    .uta-hero { background: #fff; border-left: 6px solid rgb(218, 150, 150); padding: 25px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,.12); }
    .uta-hero h1 { margin: 0 0 10px; }
    .uta-hero p { line-height: 1.6; margin: 0; }
    .uta-datos { display: flex; flex-wrap: wrap; gap: 12px; margin: 20px 0; }
    .uta-dato { flex: 1 1 200px; background: #fff; padding: 15px; border-radius: 6px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,.12); }
    .uta-dato strong { display: block; font-size: 22px; color: rgb(176, 98, 98); margin-bottom: 4px; }
    .uta-caja { background: #fff; padding: 20px 25px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,.12); margin-bottom: 20px; }
    .uta-caja h2 { margin-top: 0; }
    .uta-tabs { display: flex; gap: 8px; margin-bottom: 15px; }
    .uta-tab { padding: 10px 22px; border: 0; border-radius: 4px; background: #e6e6e6; cursor: pointer; font-size: 16px; }
    .uta-tab.activo { background: rgb(176, 98, 98); color: #fff; font-weight: bold; }
    .uta-panel { display: none; line-height: 1.7; font-size: 17px; }
    .uta-panel.activo { display: block; }
    .uta-lista li { margin-bottom: 6px; }
</style>

<div class="uta-wrap">

    <div class="uta-hero">
        <h1>Universidad Técnica de Ambato</h1>
        <p>
            La Universidad Técnica de Ambato (UTA) es una institución de educación superior pública,
            ubicada en la ciudad de Ambato, en el centro del Ecuador. Fue fundada en 1969 y forma
            profesionales en distintas áreas del conocimiento a través de sus facultades.
        </p>
    </div>

    <div class="uta-datos">
        <div class="uta-dato"><strong>1969</strong>Año de fundación</div>
        <div class="uta-dato"><strong>Pública</strong>Tipo de institución</div>
        <div class="uta-dato"><strong>Ambato</strong>Ecuador</div>
    </div>

    <div class="uta-caja">
        <h2>Misión y Visión</h2>
        <div class="uta-tabs">
            <button type="button" class="uta-tab activo" data-panel="#panelMision">Misión</button>
            <button type="button" class="uta-tab" data-panel="#panelVision">Visión</button>
        </div>

        <div id="panelMision" class="uta-panel activo">
            <!-- Si tu ingeniero pide el texto oficial, pégalo aquí desde uta.edu.ec -->
            Preparar profesionales competentes y líderes, con sentido humanista y pensamiento crítico,
            por medio de la docencia, la investigación y la vinculación con la colectividad, de modo que
            apliquen, promuevan y difundan el conocimiento en respuesta a las necesidades del país.
        </div>

        <div id="panelVision" class="uta-panel">
            <!-- Si tu ingeniero pide el texto oficial, pégalo aquí desde uta.edu.ec -->
            Ser, gracias a su excelencia académica, un centro de educación superior con liderazgo
            y proyección tanto nacional como internacional.
        </div>
    </div>

    <div class="uta-caja">
        <h2>Algunas de sus facultades</h2>
        <ul class="uta-lista">
            <li>Facultad de Ingeniería en Sistemas, Electrónica e Industrial (FISEI)</li>
            <li>Facultad de Ciencias Humanas y de la Educación</li>
            <li>Facultad de Ciencias de la Salud</li>
            <li>Facultad de Diseño, Arte y Arquitectura</li>
            <li>Facultad de Ingeniería Civil y Mecánica</li>
        </ul>
    </div>

</div>

<script>
    // Pestañas Misión / Visión con jQuery
    jQuery(function ($) {
        $('.uta-tab').on('click', function () {
            $('.uta-tab').removeClass('activo');
            $(this).addClass('activo');
            $('.uta-panel').removeClass('activo').hide();
            $($(this).data('panel')).hide().addClass('activo').fadeIn(250);
        });
    });
</script>