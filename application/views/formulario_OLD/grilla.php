<div class="container">
    <div id='ventanaGrilla'>
        <div>Formularios Presentados</div>
        <div>
            <div id="grillaFormularios" style="margin-bottom: 0.9em"></div>
            <table boder="0" cellpadding="2" cellspacing="2">
                <tr>
                    <td><input type="button" value="Nuevo" id="nuevoButton"></td>
                    <td><input type="button" value="Editar" id="editarButton"></td>
                    <td><input type="button" value="Borrar" id="borrarButton"></td>
                    <td><input type="button" value="Excel" id="excelButton"></td>
                </tr>
            </table>        
        </div>
    </div>
    
</div>
<div id="ventanaEdicion">
    <div>Edicion Rápida</div>
    <div>
        <form id='frmEdicion'>
            <table style="margin: 10px; padding: 3px; border-spacing: 5px; border-collapse: separate ">
                <tr>
                    <td>Estado:</td>
                    <td><div id="estado"></div></td>
                </tr>
                <tr>
                    <td>Fecha Estado:</td>
                    <td style="padding-top: 8px"><div id="fechaEstado"></div></td>
                </tr>
                <tr>
                    <td>Tramite Alta:</td>
                    <td style="padding-top: 8px"><div id="tramiteAlta"></div></td>
                </tr>
                <tr>
                    <td>Observaciones:</td>
                    <td style="padding-top: 8px"><textarea id="observaciones"></textarea></td>
                </tr>
                <tr class="esAlta">
                    <td>Nro Comitente:</td>
                    <td style="padding-top: 8px"><div id="numComitente"></div></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center">
                        <table boder="0" cellpadding="2" cellspacing="2" align="center">
                            <tr>
                                <td style="padding: 10px 10px 0px 0px"><input type="button" value="Aceptar" id="aceptarEdicion"></td>
                                <td style="padding-top: 10px"><input type="button" value="Cancelar" id="cancelarEdicion"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>        
        </form>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
            // prepare the data
        var theme = getTheme();
        var id = 0;
        var nombre;
        
        $("#ventanaGrilla").jqxWindow({showCollapseButton: false, maxHeight:800, height: 480,maxWidth: 5000, 
            width: '90%', theme: theme, resizable: false, keyboardCloseKey: -1});
        
        var url = "/grilla/v_formulario/id-fechaPresentacion-numComitente-numeroDocumento-apellido-nombre-estado_id-estado-contacto-responsable-observaciones-tramiteAlta-retail_id/id%20desc";
        
        var source = {
            datatype: "json",
            datafields: [
                { name: 'id', type: 'int'},
                { name: 'fechaPresentacion', type: 'date', format: 'yyyy-MM-dd HH:mm:ss'},
                { name: 'numComitente'},
                { name: 'numeroDocumento'},
                { name: 'apellido'},
                { name: 'nombre'},
                { name: 'estado_id'},
                { name: 'estado'},
                { name: 'contacto'},
                { name: 'responsable'},
                { name: 'observaciones'},
                { name: 'tramiteAlta'},
                { name: 'retail_id'}
            ],
            //id: "id",
            url: url,
            async: false,
            beforeprocessing: function(data)
            {		
                    if (data != null)
                    {
                            source.totalrecords = data[0].TotalRows;					
                    }
            }
        };

        var dataadapter = new $.jqx.dataAdapter(source, {
                loadError: function(xhr, status, error)
                {
                        alert(xhr.responseText);
                }
            }
        );
        
        var cellclassname = function (row, column, value, data) {
            if (data.retail_id > 0){
                return "grayClass";
            }
            switch (data.estado_id){
                case '5':
                    return "greenClass";
                    break;
                case '3':
                    return "yellowClass";
                    break;
                case '6':
                    return "redClass";
                    break;
            }
        };
        
        
        setTimeout(function(){
            var state = $("#grillaFormularios").jqxGrid('loadstate');
            $("#wrapper").css('opacity', 1);
        }, 1000);
        
        // initialize jqxGrid
        $("#grillaFormularios").jqxGrid(
        {		
                source: dataadapter,
                theme: theme,
                showfilterrow: true,
                filterable: true,
                sortable: true,
                autoheight: false,
                pageable: false,
                virtualmode: false,
                columnsresize: true,
                width: '100%',
                height: 400,
                selectionmode: 'singlerow',
                autosavestate: true,
                autoloadstate: false,
                rendergridrows: function(obj)
                {
                        return obj.data;    
                },
                columns: [
                    { text: 'Solicitud', datafield: 'id', width: 70, cellclassname: cellclassname},
                    { text: 'Fecha Presentación', datafield: 'fechaPresentacion', width: 150, cellsformat: 'dd/MM/yyyy HH:mm:ss', cellclassname: cellclassname},
                    { text: 'Comitente', datafield: 'numComitente', width: 60, cellclassname: cellclassname},
                    { text: 'DNI', datafield: 'numeroDocumento', width: 90, cellclassname: cellclassname},
                    { text: 'Apellido', datafield: 'apellido', width: 200, cellclassname: cellclassname},
                    { text: 'Nombre', datafield: 'nombre', width: 200, cellclassname: cellclassname},
                    { text: 'Id Estado', datafield: 'estado_id', width: 0, hidden: true, cellclassname: cellclassname},
                    { text: 'Estado', datafield: 'estado', width: 100, cellclassname: cellclassname},
                    { text: 'Contacto', datafield: 'contacto', width: 200, cellclassname: cellclassname},
                    { text: 'Responsable', datafield: 'responsable', width: 200, cellclassname: cellclassname},
                    { text: 'Observaciones', datafield: 'observaciones', width: 200, cellclassname: cellclassname},
                    { text: 'Tramite alta', datafield: 'tramiteAlta', width: 100, cellclassname: cellclassname},
                    { text: 'Retail Id', datafield: 'retail_id', width: 100, cellclassname: cellclassname, hidden: true}
                ]
        });
        $("#grillaFormularios").on("bindingcomplete", function (event){
            var localizationobj = getLocalization();
            $("#grillaFormularios").jqxGrid('localizestrings', localizationobj);
        });  
        
        $("#nuevoButton").jqxButton({ width: '80', theme: theme });
        $("#editarButton").jqxButton({ width: '80', theme: theme, disabled: true });
        $("#borrarButton").jqxButton({ width: '80', theme: theme, disabled: true });
        $("#excelButton").jqxButton({ width: '80', theme: theme, disabled: false });
        
        $("#excelButton").click(function(){
            grid2excel('#grillaFormularios', 'FormulariosPresentados', false);
        });
        
        $("#nuevoButton").click(function(){
            //$.redirect('/comercial/editar', {'id': 0});
        });
        
        $("#editarButton").click(function(){
            $.redirect('/formulario/editar', {'id': id});
        });
        
        $("#borrarButton").click(function(){
            new Messi('Desea borrar el formulario ' + id + ' ?' , {title: 'Confirmar', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            id: id
                        };
                        $.post('/formulario/del', datos, function(data){
                            new Messi(data.resultado, {title: 'Mensaje', modal: true,
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}]});
                            $('#grillaFormularios').jqxGrid('updatebounddata');
                            $('#editarButton').jqxButton({disabled: true });
                            $('#borrarButton').jqxButton({disabled: true });
                            $('#grillaFormularios').jqxGrid({ selectedrowindex: -1}); 
                        }
                        , 'json');
                    } 
                }
            });
        });
        
        
        $('#grillaFormularios').on('rowselect', function (event) {
            var args = event.args; 
            var row = args.rowindex;
            if (row >= 0){
                if (args.row.retail_id > 0){
                    $('#editarButton').jqxButton({disabled: true });
                    $('#borrarButton').jqxButton({disabled: true });
                } else {
                    $('#editarButton').jqxButton({disabled: false });
                    $('#borrarButton').jqxButton({disabled: false });
                }
                id = args.row.id;
                nombre = args.row.apellido + ' ' + args.row.nombre;
            }
        });
        var cuits;
        
        $('#grillaFormularios').on('rowdoubleclick', function (event) { 
            if (!id>0){
                var indice = $("#grillaFormularios").jqxGrid('getselectedrowindex');
                if (indice >= 0){
                    var datos = $("#grillaFormularios").jqxGrid('getrowdata', indice);
                    id = datos.id;
                    nombre = datos.apellido + ', ' + datos.nombre;
                }
            }
            if (id > 0){
                $(".esAlta").hide();
                $("#estado").jqxDropDownList('selectIndex', -1);
                $("#observaciones").val('');
                $("#numComitente").val(0);
                $.post('/formulario/getRapido', {id: id}, function(resultado){
                    if (resultado){
                        cuits = resultado.cuit;
                        $("#ventanaEdicion").jqxWindow({title: nombre});
                        $("#estado").val(resultado.estado_id);
                        $("#fechaEstado").jqxDateTimeInput('val', resultado.fechaEstado);
                        $("#tramiteAlta").val(resultado.tramitealta_id);
                        $("#observaciones").val(resultado.observaciones);
                        if (resultado.numComitente){
                            $("#numComitente").val(resultado.numComitente);
                        }
                        $("#ventanaEdicion").jqxWindow('open');
                        $("#ventanaEdicion").jqxWindow('bringToFront');
                    } else {
                        $("#ventanaEdicion").jqxWindow('close');
                    }
                }, 'json');
            }
        });
        
        
        
        
        /*********************************************************************************/
        /**                                                                             **/
        /**                               Edicion Rápida                                **/
        /**                                                                             **/
        /*********************************************************************************/
        
        $("#ventanaEdicion").jqxWindow({showCollapseButton: false, height: 350, width: 350, theme: theme, resizable: false, keyboardCloseKey: -1, autoOpen: false, isModal: true});
        // Estado Observaciones numComitente aceptar y cancelar
        var srcEstados = {
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'descripcion'}
            ],
            id: 'id',
            url: '/estado/getAll'
        };
        var DAEstados = new $.jqx.dataAdapter(srcEstados);        
        $("#estado").jqxDropDownList({ width: '200', height: '20', theme: theme, source: DAEstados, displayMember: 'descripcion', valueMember: 'id'});
        $("#fechaEstado").jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy HH:mm:ss', theme: theme, disabled: true });
        var srcTramiteAlta = {
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'descripcion'}
            ],
            id: 'id',
            url: '/tramiteAlta/getAll'
        };
        
        var DATramiteAlta = new $.jqx.dataAdapter(srcTramiteAlta);
        
        $("#tramiteAlta").jqxDropDownList({width: '200', height: '20', theme: theme, source: DATramiteAlta, displayMember: 'descripcion', valueMember: 'id'});        
        $('#observaciones').jqxTextArea({ height: 90, width: 200, theme: theme, disabled: false});
        $("#estado").on('change', function(event){
            var args = event.args;
            if (args){
                var estado_id = args.item.value;
                $.post('/estado/get', {id: estado_id}, function(estado){
                    if (estado.esAlta == 1){
                        $('.esAlta').show();
                    } else {
                        $('.esAlta').hide();
                    }
                }, 'json');
            }
        });
        $("#numComitente").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999, theme: theme});
        
        $('#frmEdicion').jqxValidator({rules:[
            { input: '#numComitente', message: 'No existe un comitente con ese número', action: 'blur', rule: function(){
                $("#frmEdicion").jqxValidator('hideHint', '#numComitente');
                var resultado = false;
                var value = $("#numComitente").val();
                if (value == 0){
                    resultado = true;
                } else {
                    jQuery.ajaxSetup({async:false});
                    $.post('/esco/getComitente', {numComitente: value}, function(pComitente){
                        if (pComitente){
                            $('#frmEdicion').jqxValidator('rules')[0].message = "No coinciden el cuit del comitente (" + pComitente.cuit + ") con ninguno de los titulares";
                            $.each(cuits, function(index, cuit){
                                if (cuitSinGuiones(cuit)  == pComitente.cuit){
                                    resultado = true;
                                }
                            });
                            if (resultado){
                                $('#frmEdicion').jqxValidator('rules')[0].message = 'No coincide en ESCO (Presencial / No Presencial)';
                                //Aca verifico que esten en ambos lados cargados como presenciales / no Presenciales
                                var tramiteAlta = $("#tramiteAlta").val();
                                switch (tramiteAlta){
                                    // 1 Presencial
                                    case '1':
                                    // 2 Certificado
                                    case '2':
                                        if (pComitente.noPresencial != 0){
                                            resultado = false;
                                        }
                                        break;
                                    // 3 No Presencial
                                    case '3':
                                        if (pComitente.noPresencial != -1){
                                            resultado = false;
                                        }
                                        break;
                                }
                            }
                        } else {
                            $('#frmEdicion').jqxValidator('rules')[0].message = 'No existe un comitente con ese número';
                            resultado = false;
                        }
                    },'json');
                    if (resultado){
                        $('#frmEdicion').jqxValidator('rules')[0].message = 'Ya existe una solicitud con ese numero de comitente';
                        datos = {
                            tabla: 'formulario',
                            campo: 'numComitente',
                            valor: $('#numComitente').val(),
                            id: id
                        };
                        var resultado;
                        $.post('/util/buscarDuplicado', datos, function(data){
                            if (data.resultado){
                                resultado = false;
                            } else {
                                resultado = true;
                            }
                        }
                        , 'json');                                    

                    }                    
                    jQuery.ajaxSetup({async:true});
                }
                return resultado;
            }},
            { input: '#tramiteAlta', message: 'Debe seleccionar el tramite de alta', action: 'blur', rule: function(){
                return ($("#tramiteAlta").jqxDropDownList('getSelectedIndex') !== -1);
            }} 
        ], theme: theme});
    
        $('#frmEdicion').bind('validationSuccess', function (event) { 
            formOK = true; 
        });
        $('#frmEdicion').bind('validationError', function (event) { 
            formOK = false; 
        }); 

        var formOK = false;
        
        $("#aceptarEdicion").jqxButton({theme: theme, width: '100px'});
        $("#aceptarEdicion").bind('click', function (){
            formOK = false;
            $("#frmEdicion").jqxValidator('validate');
            if (formOK){
                formulario = {
                    id: id,
                    estado_id: $("#estado").val(),
                    tramitealta_id: $("#tramiteAlta").val(),
                    observaciones: $("#observaciones").val(),
                    numComitente: $("#numComitente").val()
                };
                $("#ventanaFormulario").ajaxloader();
                $.post('/formulario/saveRapido', formulario, function(resultado){
                    if (resultado.id>0){
                        $("#grillaFormularios").jqxGrid('updatebounddata');
                        $("#ventanaEdicion").jqxWindow('close');                        
                    }
                }, 'json');
            }
        });
        
        $("#cancelarEdicion").jqxButton({theme: theme, width: '100px'});
        $("#cancelarEdicion").bind('click', function (){
            $("#ventanaEdicion").jqxWindow('close');
        });
        
    });
</script>
<style>
    .redClass:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected), .jqx-widget .green:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
        background-color: #f4424e;
        color: white;
    }
    .greenClass:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected), .jqx-widget .yellow:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
        color: white;
        background-color: darkgreen;
    }
    .yellowClass:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected), .jqx-widget .red:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
        color: black;
        background-color: #fffd99;
    }
    .grayClass:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected), .jqx-widget .red:not(.jqx-grid-cell-hover):not(.jqx-grid-cell-selected) {
        color: white;
        background-color: #999999
    }
    
</style>