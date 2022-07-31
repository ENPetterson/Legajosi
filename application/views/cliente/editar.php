<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaCliente">
    <div id="titulo">
        Editar Cliente
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
                    <td style="padding-right:10px; padding-bottom: 10px">Email: </td>
                    <td><input type="text" id="email" style="width: 250px"></td>
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
        
        $("#ventanaCliente").jqxWindow({showCollapseButton: false, height: 600, width: 350, theme: theme,
        resizable: false, keyboardCloseKey: -1});
        if ($("#id").val() == 0){
            $("#titulo").text('Nuevo Cliente');
        } else {
            $("#titulo").text('Editar Cliente');
            datos = {
                id: $("#id").val()
            };
            $.post('/cliente/getCliente', datos, function(data){
                $("#nombre").val(data.nombre);
            }
            , 'json');
        };
         $('#form').jqxValidator({ rules: [
                    { input: '#nombre', message: 'Debe ingresar el nombre del cliente!',  rule: 'required' },
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
                $('#ventanaCliente').ajaxloader();
                datos = {
                    id: $("#id").val(),
                    nombre: $('#nombre').val(),
                    apellido: $('#apellido').val(),
                    email: $('#email').val()
                }
                $.post('/cliente/saveCliente', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/cliente');
                    } else {
                        new Messi('Hubo un error guardando el cliente', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                        $('#ventanaCliente').ajaxloader('hide');
                    }
                }, 'json');
            }
        });                
    });
</script>