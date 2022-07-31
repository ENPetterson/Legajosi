<?php
$timestamp = time();
?>

<script type="text/javascript">
    $(document).ready(function () {
            // prepare the data

        var timestamp = <?= $timestamp; ?>;
        var token = '<?= md5('unique_salt' . $timestamp); ?>'; 
        
        var theme = getTheme();
        var id = 0;
        var fechaPresentacion;
        var fechaEstado;
        var estado_id;
        var observaciones;
        var fechaActualizacion;

        $("#ventanaGrilla").jqxWindow({showCollapseButton: false, height: 500, width: 1000, theme: theme,
        resizable: false, keyboardCloseKey: -1});

        var source = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'fechaPresentacion'},
                { name: 'fechaEstado'},
                { name: 'estado_id'},
                { name: 'observaciones'},
                { name: 'fechaActualizacion'},                           
            ],
            id: 'id',
            url: '/solicitud/getSolicitudes',
            async: false
        };
        var dataAdapter = new $.jqx.dataAdapter(source);

        // initialize jqxGrid
        $("#grilla").jqxGrid(
        {		
            source: dataAdapter,
            theme: theme,
            filterable: true,
            filtermode: 'excel',
            sortable: true,
            autoheight: false,
            pageable: false,
            virtualmode: false,
            columnsresize: true,
            width: 780,
            height: 420,
            columns: [
                    { text: 'Id', datafield: 'id', width: 0, 'hidden': true },
                    { text: 'Fecha Presentacion', datafield: 'fechaPresentacion', width: 180 },
                    { text: 'Fecha Estado', datafield: 'fechaEstado', width: 180 },
                    { text: 'Id Estado', datafield: 'estado_id', width: 120 },
                    { text: 'Observaciones', datafield: 'observaciones', width: 120 },
                    { text: 'Fecha Actualizacion', datafield: 'fechaActualizacion', width: 180 },                                        
            ]
        });
        $("#grilla").on("bindingcomplete", function (event){
            var localizationobj = getLocalization();
            $("#grilla").jqxGrid('localizestrings', localizationobj);
        });  
        
        $("#nuevoButton").jqxButton({ width: '80', theme: theme });
        $("#editarButton").jqxButton({ width: '80', theme: theme, disabled: true });
        $("#borrarButton").jqxButton({ width: '80', theme: theme, disabled: true });
        
        $("#nuevoButton").click(function(){
            $.redirect('/solicitud/editar', {'id': 0});
        });
        
        $("#editarButton").click(function(){
            $.redirect('/solicitud/editar', {'id': id});
        });
        
        $("#borrarButton").click(function(){
            new Messi('Desea borrar la solicitud ' + nombre + ' ?' , {title: 'Confirmar', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            id: id
                        };
                        $.post('/solicitud/delSolicitud', datos, function(data){
                            new Messi(data.resultado, {title: 'Mensaje', modal: true,
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}]});
                            $('#grilla').jqxGrid('updatebounddata');
                            $('#editarButton').jqxButton({disabled: true });
                            $('#borrarButton').jqxButton({disabled: true });
                            $('#grilla').jqxGrid({ selectedrowindex: -1}); 
                        }
                        , 'json');
                    } 
                }
            });
        });
        
        
        $('#grilla').on('rowselect', function (event) {
            var args = event.args; 
            var row = args.rowindex;
            if (row >= 0){
                $('#editarButton').jqxButton({disabled: false });
                $('#borrarButton').jqxButton({disabled: false });
                id = args.row.id;
                nombre = args.row.nombre;
            }
        });
        

        $("#archivoExcel").jqxButton({ width: '300', theme: theme, disabled: false });

        $('#archivoExcel').uploadifive({
            'uploadScript': '/uploadifive.php',
            'formData': {
                'timestamp': timestamp,
                'token': token
            },
            'buttonText': 'Importar Excel...',
            'multi': false,
            'queueSizeLimit': 1,
            'uploadLimit': 0,
            'height': 20,
            'width': 200,
            'removeCompleted': true,
            'onUploadComplete': function(file) {
                $('#grilla').ajaxloader();
                $.post('/solicitud/grabarExcel', { file: file.name }, function(msg){
                    var titleClass;
                    var mensaje;
                    var title;
                    if(msg.resultado == 'OK'){
                        titleClass = 'success';
                        title = 'Correcto';
                        mensaje = 'Se han importado las ordenes';
                    } else {
                        titleClass = 'error';
                        title = 'No se importaron las ordenes';
                        mensaje = msg.mensaje;
                    }
                    $('#grilla').ajaxloader('hide');
                    new Messi(mensaje, {title: title, modal: true,
                        buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: titleClass, callback: function(val) { 
                            if (val == 'X'){
                                $("#grilla").jqxGrid('updatebounddata');
                            } 
                        }
                    });                    
                }, 'json');
            }
        });
        
    });
</script>
<div id="ventanaGrilla">
    <div>Solicitud</div>
    <div>
        <div id="grilla" style="margin-bottom: 0.9em"></div>
        <table boder="0" cellpadding="2" cellspacing="2">
            <tr>
                <td><input type="button" value="Nuevo" id="nuevoButton"></td>
                <td><input type="button" value="Editar" id="editarButton"></td>
                <td><input type="button" value="Borrar" id="borrarButton"></td>
                <td id='archivoExcelFila'><input type="file" value="Archivo" id="archivoExcel"></td>  
            </tr>
        </table>        
    </div>
</div>
