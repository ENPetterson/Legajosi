<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaGatito">
    <div id="titulo">
        Editar Gatito
    </div>
    <div>
        <form id="form">
            <table>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Nombre: </td>
                    <td><input type="text" id="nombre" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Apellido: </td>
                    <td><input type="text" id="apellido" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Domicilio: </td>
                    <td><input type="text" id="domicilio" style="width: 250px"></td>
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
        
        $("#ventanaGatito").jqxWindow({showCollapseButton: false, height: 600, width: 350, theme: theme,
        resizable: false, keyboardCloseKey: -1});
        if ($("#id").val() == 0){
            $("#titulo").text('Nuevo Gatito');
        } else {
            $("#titulo").text('Editar Gatito');
            datos = {
                id: $("#id").val()
            };
            $.post('/gatito/getGatito', datos, function(data){
                $("#nombre").val(data.nombre);
                $("#apellido").val(data.apellido);
                $("#domicilio").val(data.domicilio);
            }
            , 'json');
        };

        $('#apellido').jqxInput({width: 200, height: 25, theme: theme, disabled: false});
        $('#domicilio').jqxInput({width: 200, height: 25, theme: theme, disabled: false});
        
        $('#form').jqxValidator({ rules: [
                    { input: '#nombre', message: 'Debe ingresar el nombre del gatito!',  rule: 'required' },
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
                $('#ventanaGatito').ajaxloader();
                datos = {
                    id: $("#id").val(),
                    nombre: $('#nombre').val(),
                    apellido: $('#apellido').val(),
                    domicilio: $('#domicilio').val()
                }
                $.post('/gatito/saveGatito', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/gatito');
                    } else {
                        new Messi('Hubo un error guardando el gatito', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                        $('#ventanaGatito').ajaxloader('hide');
                    }
                }, 'json');
            }
        });                
    });
</script>