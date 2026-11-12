<!-- Bootstrap 5.3.8 (CSS y JS) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<div class="container my-4">
    <h2 class="mb-3">Estudiantes</h2>
    <div id="mensaje"></div>

    <!-- Botones y buscar por cédula -->
    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
        <button class="btn btn-success" onclick="abrir(false)">Nuevo</button>
        <button class="btn btn-primary" id="btnEditar" onclick="abrir(true)" disabled>Editar</button>
        <button class="btn btn-danger" id="btnEliminar" onclick="pedirEliminar()" disabled>Eliminar</button>
        <button class="btn btn-dark" onclick="generarReporte()">Reporte PDF</button>
        <div class="input-group ms-lg-4" style="max-width: 360px;">
            <input type="text" id="txtBuscar" class="form-control" placeholder="Cédula a buscar">
            <button class="btn btn-outline-secondary" onclick="buscar()">Buscar</button>
            <button class="btn btn-outline-secondary" onclick="verTodos()">Ver todos</button>
        </div>
    </div>

    <!-- Tabla Bootstrap: tabla table-info, encabezado table-dark, fila elegida table-warning -->
    <table class="table table-info table-hover">
        <thead>
            <tr class="table-dark">
                <th>Cédula</th><th>Nombre</th><th>Apellido</th><th>Dirección</th><th>Teléfono</th>
            </tr>
        </thead>
        <tbody id="tabla"></tbody>
    </table>
    <p class="text-muted small">Haz clic en una fila para seleccionarla.</p>
</div>

<!-- Modal Nuevo / Editar -->
<div class="modal fade" id="modalEstudiante" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form id="formEstudiante" novalidate>
            <div class="modal-header">
                <h5 class="modal-title" id="titulo"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="msgModal"></div>
                <label class="form-label">Cédula</label>
                <input class="form-control mb-2" id="cedula" name="cedula" required>
                <label class="form-label">Nombre</label>
                <input class="form-control mb-2" id="nombre" name="nombre" required>
                <label class="form-label">Apellido</label>
                <input class="form-control mb-2" id="apellido" name="apellido" required>
                <label class="form-label">Dirección</label>
                <input class="form-control mb-2" id="direccion" name="direccion" required>
                <label class="form-label">Teléfono</label>
                <input class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar()">Guardar</button>
            </div>
        </form>
    </div></div>
</div>

<!-- Modal Eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Eliminar estudiante</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="textoEliminar"></div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button class="btn btn-danger" onclick="eliminar()">Eliminar</button>
        </div>
    </div></div>
</div>

<script>
    // Usa los mismos archivos de models: select, buscar, guardar, update y delete
    var mEst = new bootstrap.Modal(document.getElementById('modalEstudiante'));
    var mEli = new bootstrap.Modal(document.getElementById('modalEliminar'));
    var campos = ['cedula', 'nombre', 'apellido', 'direccion', 'telefono'];
    var lista = [];        // estudiantes que se muestran
    var sel = null;        // estudiante seleccionado
    var original = null;   // cédula original al editar (null = nuevo)
    var cedulaBuscada = '';  // cédula de la búsqueda actual (vacía = se muestran todos)

    function el(id) { return document.getElementById(id); }
    function esc(t) { return String(t == null ? '' : t).replace(/[&<>"']/g, function (c) { return '&#' + c.charCodeAt(0) + ';'; }); }
    function datos(obj) { var fd = new FormData(); for (var k in obj) fd.append(k, obj[k]); return fd; }
    function mensaje(texto, tipo, donde) {
        el(donde || 'mensaje').innerHTML = '<div class="alert alert-' + tipo + '">' + esc(texto) + '</div>';
    }
    // Llama a un archivo de models y devuelve su JSON
    async function pedir(url, fd) {
        var r = await fetch(url, fd ? { method: 'POST', body: fd } : undefined);
        return JSON.parse((await r.text()).trim());
    }

    function pintar() {
        var html = '';
        lista.forEach(function (e, i) {
            html += '<tr style="cursor:pointer" class="' + (e === sel ? 'table-warning' : '') + '" onclick="elegir(' + i + ')">' +
                campos.map(function (c) { return '<td>' + esc(e[c]) + '</td>'; }).join('') + '</tr>';
        });
        el('tabla').innerHTML = html || '<tr><td colspan="5" class="text-center">No hay estudiantes</td></tr>';
    }

    function elegir(i) {
        sel = lista[i];
        el('btnEditar').disabled = el('btnEliminar').disabled = false;
        pintar();
    }

    // Carga todos (select.php) o solo una cédula (buscar.php)
    async function cargar(cedula) {
        cedulaBuscada = cedula || '';   // recordamos qué se está mostrando para el reporte
        try {
            var d = cedula ? await pedir('models/buscar.php?cedula=' + encodeURIComponent(cedula)) : await pedir('models/select.php');
            lista = Array.isArray(d) ? d : [];
        } catch (e) {
            lista = [];
            mensaje('No se pudo cargar la lista', 'danger');
        }
        sel = null;
        el('btnEditar').disabled = el('btnEliminar').disabled = true;
        pintar();
    }

    async function buscar() {
        var ced = el('txtBuscar').value.trim();
        if (!ced) return mensaje('Ingrese la cédula a buscar', 'warning');
        await cargar(ced);
        if (lista.length == 0) mensaje('No existe un estudiante con esa cédula', 'info');
    }

    function verTodos() { el('txtBuscar').value = ''; cargar(); }

    // Reporte PDF (FPDF): de todos, o solo del estudiante buscado
    function generarReporte() {
        var enlace = 'reportes.php';
        if (cedulaBuscada !== '') {
            enlace += '?cedula=' + encodeURIComponent(cedulaBuscada);
        }
        window.open(enlace, '_blank');
    }


    function abrir(editar) {
        el('formEstudiante').reset();
        el('msgModal').innerHTML = '';
        el('titulo').textContent = editar ? 'Editar Estudiante' : 'Nuevo Estudiante';
        original = editar ? sel.cedula : null;
        if (editar) campos.forEach(function (c) { el(c).value = sel[c]; });
        mEst.show();
    }

    async function guardar() {
        var f = el('formEstudiante');
        if (!f.reportValidity()) return;
        var fd = new FormData(f), ced = el('cedula').value.trim(), ok;
        try {
            if (original !== null && ced === original) {
                // Editar sin cambiar la cédula: update.php
                ok = (await pedir('models/update.php?cedula=' + encodeURIComponent(original), fd)).success == 'Se edito';
            } else {
                // Nuevo, o editar CAMBIANDO la cédula: se revisa que no exista y se guarda
                if ((await pedir('models/buscar.php?cedula=' + encodeURIComponent(ced))).length > 0)
                    return mensaje('Ya existe un estudiante con esa cédula', 'danger', 'msgModal');
                ok = (await pedir('models/guardar.php', fd)).success == 'Se guardo';
                // update.php no cambia la cédula, así que se elimina el registro con la cédula vieja
                if (ok && original !== null) await pedir('models/delete.php', datos({ cedula: original }));
            }
            if (!ok) return mensaje('No se pudo guardar', 'danger', 'msgModal');
            mEst.hide();
            mensaje('Estudiante guardado', 'success');
            cargar();
        } catch (e) {
            mensaje('No se pudo guardar. Revise los datos', 'danger', 'msgModal');
        }
    }

    function pedirEliminar() {
        el('textoEliminar').textContent = '¿Eliminar a ' + sel.nombre + ' ' + sel.apellido + ' (cédula ' + sel.cedula + ')?';
        mEli.show();
    }

    async function eliminar() {
        try {
            var d = await pedir('models/delete.php', datos({ cedula: sel.cedula }));
            mensaje(d.success ? 'Estudiante eliminado' : 'No se pudo eliminar', d.success ? 'success' : 'danger');
        } catch (e) {
            mensaje('No se pudo eliminar', 'danger');
        }
        mEli.hide();
        cargar();
    }

    cargar();
</script>