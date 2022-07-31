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
        var nombre;
        var apellido;
        var fechaNacimiento;
        var tipoDocumento;
        var cuil;
        var nacionalidad;
        var estadoCivil;
        var esDiscapacitado;
        var email;
        var sexo;
        var cargo;
        var fechaIngreso;
        var fechaEgreso;
        var fechaAntiguedad;
        var diasVacaciones;
        var sueldoBasico;
        var observaciones;


        $("#ventanaGrilla").jqxWindow({showCollapseButton: false, height: 600, width: 1350, maxWidth: '95%', maxHeight: '95%', theme: theme,
        resizable: false, keyboardCloseKey: -1});

        var source = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'nombre'},
                { name: 'apellido'},
                { name: 'fechaNacimiento'},
                { name: 'tipoDocumento'},
                { name: 'cuil'},  
                { name: 'nacionalidad'},  
                { name: 'estadoCivil'},  
                { name: 'esDiscapacitado'},  
                { name: 'email'},  
                { name: 'sexo'},  
                { name: 'cargo'},  
                { name: 'fechaIngreso'},  
                { name: 'fechaEgreso'},  
                { name: 'fechaAntiguedad'},  
                { name: 'diasVacaciones'},  
                { name: 'sueldoBasico'},  
                { name: 'observaciones'},                           
            ],
            id: 'id',
            url: '/legajo/getLegajos',
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
            width: 1335,
            height: 510,
            columns: [
                    { text: 'Id', datafield: 'id', width: 0, 'hidden': true },
                    { text: 'Nombre', datafield: 'nombre', width: 120 },
                    { text: 'Apellido', datafield: 'apellido', width: 120 },
                    { text: 'Fecha Nacimiento', datafield: 'fechaNacimiento', width: 120 },
                    { text: 'Tipo Documento', datafield: 'tipoDocumento', width: 120 },
                    { text: 'CUIL', datafield: 'cuil', width: 120 },
                    { text: 'Nacionalidad', datafield: 'nacionalidad', width: 120 },
                    { text: 'Estado Civil', datafield: 'estadoCivil', width: 120 },
                    { text: 'Es Discapacitado', datafield: 'esDiscapacitado', width: 80 },
                    { text: 'Email', datafield: 'email', width: 190 },
                    { text: 'Sexo', datafield: 'sexo', width: 60 },
                    { text: 'Cargo', datafield: 'cargo', width: 60 },
                    { text: 'Fecha Ingreso', datafield: 'fechaIngreso', width: 110 },
                    { text: 'Fecha Egreso', datafield: 'fechaEgreso', width: 110 },
                    { text: 'Fecha Antiguedad', datafield: 'fechaAntiguedad', width: 120 },
                    { text: 'Dias Vacaciones', datafield: 'diasVacaciones', width: 80 },
                    { text: 'Sueldo Basico', datafield: 'sueldoBasico', width: 105 },
                    { text: 'Observaciones', datafield: 'observaciones', width: 120 },
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
            $.redirect('/legajo/editar', {'id': 0});
        });
        
        $("#editarButton").click(function(){
            $.redirect('/legajo/editar', {'id': id});
        });
        
        $("#borrarButton").click(function(){
            new Messi('Desea borrar el legajo ' + nombre + ' ?' , {title: 'Confirmar', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            id: id
                        };
                        $.post('/legajo/delLegajo', datos, function(data){
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
                $.post('/legajo/grabarExcel', { file: file.name }, function(msg){
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
    <div>Legajo</div>
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
