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
        var experiencia;
        var empresa;
        var legajo_id;
        var fechaInicio;
        var fechaSalida;
        var montoMensual;
        var dependencia;
        var funciones;

        $("#ventanaGrilla").jqxWindow({showCollapseButton: false, height: 590, width: 1045, maxWidth: '95%', maxHeight: '95%', theme: theme,
        resizable: false, keyboardCloseKey: -1});

        var source = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'experiencia'},
                { name: 'empresa'},
                { name: 'legajo_id'},
                { name: 'fechaInicio'},
                { name: 'fechaSalida'},
                { name: 'montoMensual'},
                { name: 'dependencia'},
                { name: 'funciones'},                           
            ],
            id: 'id',
            url: '/experiencialaboral/getExperienciaslaborales',
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
            width: 1032,
            height: 507,
            columns: [
                    { text: 'Id', datafield: 'id', width: 0, 'hidden': true },
                    { text: 'Experiencias', datafield: 'experiencia', width: 220 },
                    { text: 'Empresa', datafield: 'empresa', width: 110 },
                    { text: 'ID Legajo', datafield: 'legajo_id', width: 80 },
                    { text: 'Fecha Inicio', datafield: 'fechaInicio', width: 103 },
                    { text: 'Fecha Salida', datafield: 'fechaSalida', width: 103 },
                    { text: 'Monto Mensual', datafield: 'montoMensual', width: 106 },
                    { text: 'Dependencia', datafield: 'dependencia', width: 100 },
                    { text: 'Funciones', datafield: 'funciones', width: 210 },
                                        
            ]
        });

        $("#grilla").on("bindingcomplete", function (event){
            var localizationobj = getLocalization();
            $("#grilla").jqxGrid('localizestrings', localizationobj);
        });  
        
        $("#nuevoButton").jqxButton({ width: '80', theme: theme });
        $("#editarButton").jqxButton({ width: '80', theme: theme, disabled: true });
        $("#borrarButton").jqxButton({ width: '80', theme: theme, disabled: true });
        $("#enviarButton").jqxButton({ width: '160', theme: theme, disabled: true });        
        
        $("#nuevoButton").click(function(){
            $.redirect('/experiencialaboral/editar', {'id': 0});
        });
        
        $("#editarButton").click(function(){
            $.redirect('/experiencialaboral/editar', {'id': id});
        });
        
        $("#borrarButton").click(function(){
            new Messi('Desea borrar la experiencia laboral ' + nombre + ' ?' , {title: 'Confirmar', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            id: id
                        };
                        $.post('/experiencialaboral/delExperiencialaboral', datos, function(data){
                            new Messi(data.resultado, {title: 'Mensaje', modal: true,
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}]});
                            $('#grilla').jqxGrid('updatebounddata');
                            $('#editarButton').jqxButton({disabled: true });
                            $('#borrarButton').jqxButton({disabled: true });
                            $('#enviarButton').jqxButton({disabled: true });                            
                            $('#grilla').jqxGrid({ selectedrowindex: -1}); 
                        }
                        , 'json');
                    } 
                }
            });
        });
        
        $("#enviarButton").click(function(){
            new Messi('Desea enviar las ordenes seleccionadas ?' , {title: 'Confirmar',titleClass: 'warning', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            ordenes: enviar
                        };
                        $.post('/canje/enviarOrdenes', datos, function(data){
                            var titleClass;
                            if (data.exito == 0){
                                titleClass = 'error';
                            } else {
                                titleClass = 'success';
                            }
                            new Messi(data.resultado, {title: 'Mensaje', modal: true,
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], titleClass: titleClass});
                            $('#grilla').jqxGrid('updatebounddata');
                            $('#editarButton').jqxButton({disabled: true });
                            $('#borrarButton').jqxButton({disabled: true });
                            $('#enviarButton').jqxButton({disabled: true });
                            $('#grilla').jqxGrid('clearselection');
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
                $('#enviarButton').jqxButton({disabled: false });                
                id = args.row.id;
                nombre = args.row.nombre;
            }
        });
        

////////////////////////////////////////////////////////////////////////////////   
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
                $.post('/experiencialaboral/grabarExcel', { file: file.name }, function(msg){
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
////////////////////////////////////////////////////////////////////////////////  
        

                
    });

</script>
<div id="ventanaGrilla">
    <div>Experiencia Laboral</div>
    <div>
        <div id="grilla" style="margin-bottom: 0.9em"></div>
        <table boder="0" cellpadding="2" cellspacing="2">
            <tr>
                <td><input type="button" value="Nuevo" id="nuevoButton"></td>
                <td><input type="button" value="Editar" id="editarButton"></td>
                <td><input type="button" value="Borrar" id="borrarButton"></td> 
                <td><input type="button" value="Enviar a Backoffice" id="enviarButton"></td>               
                <td id='archivoExcelFila'><input type="file" value="Archivo" id="archivoExcel"></td>                
            </tr>
        </table>        
    </div>
</div>
