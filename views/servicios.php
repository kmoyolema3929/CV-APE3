<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Basic CRUD Application - jQuery EasyUI CRUD Demo</title>
    <link rel="stylesheet" type="text/css" href="jquery/themes/black/easyui.css">
    <link rel="stylesheet" type="text/css" href="jquery/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="jquery/themes/color.css">
    <script type="text/javascript" src="jquery/jquery.min.js"></script>
    <script type="text/javascript" src="jquery/jquery.easyui.min.js"></script>
</head>
<body>
    <h2>Basic CRUD Application</h2>
    <p>Click the buttons on datagrid toolbar to do crud actions.</p>
    
    <table id="dg" title="Estudiantes" class="easyui-datagrid" style="width:700px;height:250px"
            url="models/select.php"
            toolbar="#toolbar" pagination="true"
            rownumbers="true" fitColumns="true" singleSelect="true">
        <thead>
            <tr>
                <th field="cedula" width="50">Cedula</th>
                <th field="nombre" width="50">Nombre</th>
                <th field="apellido" width="50">Apellido</th>
                <th field="direccion" width="50">Direccion</th>
                <th field="telefono" width="50">Telefono</th>
            </tr>
        </thead>
    </table>
    <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">Nuevo Estudiante</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">Editar Estudiante</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">Eliminar Estudiante</a>

        <!-- agregar barra de busqueda -->
        <span style="margin-left:20px">
            <input id="txtBuscar" class="easyui-textbox" prompt="Cedula a buscar" style="width:160px">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="buscarEstudiante()">Buscar</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="verTodos()">Ver todos</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="reporteFpdf()">Reporte FPDF</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="reporteDompdf()">Reporte Dompdf</a>
        </span>

    </div>
    
    <div id="dlg" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
        <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
            <h3>Estudiante</h3>
            <div style="margin-bottom:10px">
                <input name="cedula" class="easyui-textbox" required="true" label="Cedula:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="nombre" class="easyui-textbox" required="true" label="Nombre:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="apellido" class="easyui-textbox" required="true" label="Apellido:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="direccion" class="easyui-textbox" required="true" label="Direccion:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="telefono" class="easyui-textbox" required="true" label="Telefono:" style="width:100%">
            </div>
        </form>
    </div>
    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
    </div>
    <script type="text/javascript">
        var url;
        var cedulaBuscada = '';   // guarda la cedula buscada (vacia = todos los estudiantes)

        //funciones para buscar y ver todos los estudiantes
        function buscarEstudiante(){
            var cedula = $.trim($('#txtBuscar').textbox('getValue'));
            if (!cedula){
                $.messager.alert('Aviso','Ingrese la cedula a buscar','warning');
                return;
            }
            cedulaBuscada = cedula;   // recordamos la busqueda para el reporte
            $('#dg').datagrid('options').url = 'models/buscar.php';
            $('#dg').datagrid('load', {cedula: cedula});
        }
        function verTodos(){
            cedulaBuscada = '';       // limpiamos la busqueda
            $('#txtBuscar').textbox('clear');
            $('#dg').datagrid('options').url = 'models/select.php';
            $('#dg').datagrid('load', {});
        }

        //reportes PDF: de todos, o solo del estudiante buscado
        function abrirReporte(archivo){
            var enlace = archivo;
            if (cedulaBuscada !== ''){
                enlace += '?cedula=' + encodeURIComponent(cedulaBuscada);
            }
            window.open(enlace, '_blank');
        }
        function reporteFpdf(){
            abrirReporte('reportes.php');          // el que hicimos con el ingeniero (FPDF)
        }
        function reporteDompdf(){
            abrirReporte('reporte_dompdf.php');    // el nuevo (Dompdf)
        }
       
        //--
        function newUser(){
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','Nuevo Estudiante');
            $('#fm').form('clear');
            url = 'models/guardar.php';
        }
        function editUser(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dlg').dialog('open').dialog('center').dialog('setTitle','Editar Estudiante');
                $('#fm').form('load',row);
                url = 'models/update.php?cedula='+row.cedula+"&nombre="+row.nombre;
            }
        }
        function saveUser(){
            $('#fm').form('submit',{
                url: url,
                iframe: false,
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){
                    var result = eval('('+result+')');
                    if (result.errorMsg){
                        $.messager.show({
                            title: 'Error',
                            msg: result.errorMsg
                        });
                    } else {
                        $('#dlg').dialog('close');        // close the dialog
                        $('#dg').datagrid('reload');    // reload the user data
                    }
                }
            });
        }
        function destroyUser(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('Confirmar','EStas seguro de eliminar el estudiantes?',function(r){
                    if (r){
                        $.post('models/delete.php?cedula='+row.cedula,{cedula:row.cedula},function(result){
                            if (result.success){
                                $('#dg').datagrid('reload');    // reload the user data
                            } else {
                                $.messager.show({    // show error message
                                    title: 'Error',
                                    msg: result.errorMsg
                                });
                            }
                        },'json');
                    }
                });
            }
        }
    </script>
</body>
</html>