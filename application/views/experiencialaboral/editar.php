<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaExperiencialaboral">
    <div id="titulo">
        Editar Experiencia Laboral
    </div>
    <div>
        <form id="form">
            <table>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Experiencias: </td>
                    <td><input type="text" id="experiencia" style="width: 208px"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Empresa: </td>
                    <td><input type="text" id="empresa" style="width: 208px"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-left:1px">Fecha Inicio: </td>
                    <td style="padding-top: 10px"><div id="fechaInicio" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-left:1px">Fecha Salida: </td>
                    <td style="padding-top: 10px"><div id="fechaSalida" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-left:1px">Monto Mensual: </td>
                    <td><div id="montoMensual" style="width: 208px"></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 0px">Dependencia: </td>
                    <td style="padding-top: 5px"><input type="text" id="dependencia" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-left:1px">Funciones: </td>
                    <td><input type="text" id="funciones" style="width: 208px"></td>
                </tr>                               
                <tr>
                    <td colspan="2" style="text-align: center">
                        <input type="button" id="aceptarButton" value="Aceptar">
                    </td>
                </tr>
            </table>
        </form>
    </div> 
</div>
<script>
    $(function(){
        var theme = getTheme();
        var formOK = false;
        
        $("#ventanaExperiencialaboral").jqxWindow({showCollapseButton: false, height: 380, width: 370, theme: theme,
        resizable: false, keyboardCloseKey: -1});
        if ($("#id").val() == 0){
            $("#titulo").text('Nueva Experiencia Laboral');
        } else {
            $("#titulo").text('Editar Experiencia Laboral');
            datos = {
                id: $("#id").val()
            };
            $.post('/experiencialaboral/getExperiencialaboral', datos, function(data){
                $("#experiencia").val(data.experiencia);
                $("#empresa").val(data.empresa);
                $("#fechaInicio").val(data.fechaInicio);
                $("#fechaSalida").val(data.fechaSalida);
                $("#montoMensual").val(data.montoMensual);
                $("#dependencia").val(data.dependencia);
                $("#funciones").val(data.funciones);                             
            }
            , 'json');
        };

        $('#experiencia').jqxInput({width: 200, height: 25, theme: theme, disabled: false});
        $('#empresa').jqxInput({width: 200, height: 25, theme: theme, disabled: false});
        //$("#legajo_id").jqxInput({ width: 200, height: 40, theme: theme });
        $("#fechaInicio").jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy', theme: theme, disabled: true });
        $("#fechaSalida").jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy', theme: theme, disabled: true });
        $("#montoMensual").jqxNumberInput({ width: 200, height: 40, theme: theme });
        $("#dependencia").jqxInput({ width: 200, height: 40, theme: theme });
        $("#funciones").jqxInput({ width: 200, height: 40, theme: theme });

        //$('#esSoltero').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#form').jqxValidator({ rules: [
                    { input: '#experiencia', message: 'Debe ingresar la experiencia laboral!',  rule: 'required' },
/*                    { input: '#nombre', message: 'Ya existe un orden con ese nombre!',  rule: function(){
                            datos = {
                                tabla: 'orden',
                                campo: 'nombre',
                                valor: $('#nombre').val(),
                                id: $('#id').val()
                            };
                            var resultado;
                            jQuery.ajaxSetup({async:false});
                            $.post('/util/buscarDuplicado', datos, function(data){
                                if (data.resultado){
                                    resultado = false;
                                } else {
                                    resultado = true;
                                }
                            }
                            , 'json');
                            jQuery.ajaxSetup({async:true});
                            return resultado;
                    }*/
                    //}
                    ], 
                    theme: theme
        });
        $('#form').bind('validationSuccess', function (event) { formOK = true; });
        $('#form').bind('validationError', function (event) { formOK = false; }); 
        $('#aceptarButton').jqxButton({ theme: theme, width: '65px' });
        $('#aceptarButton').bind('click', function () {
            $('#form').jqxValidator('validate');
            if (formOK){
                $('#ventanaExperiencialaboral').ajaxloader();
                datos = {
                    id: $("#id").val(),
                    experiencia: $('#experiencia').val(),
                    empresa: $('#empresa').val(),
                    fechaInicio: $('#fechaInicio').val(),
                    fechaSalida: $('#fechaSalida').val(),
                    montoMensual: $('#montoMensual').val(),
                    dependencia: $('#dependencia').val(),
                    funciones: $('#funciones').val()
                }
                $.post('/experiencialaboral/saveExperiencialaboral', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/experiencialaboral');
                    } else {
                        new Messi('Hubo un error guardando la experiencia laboral', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                        $('#ventanaExperiencialaboral').ajaxloader('hide');
                    }
                }, 'json');
            }
        });                
    });
</script>