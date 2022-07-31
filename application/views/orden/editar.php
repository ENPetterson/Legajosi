<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaOrden">
    <div id="titulo">
        Editar Orden
    </div>
    <div>
        <form id="form">
            <table>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Fecha: </td>
                    <td><input type="text" id="fecha" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Tipo: </td>
                    <td><input type="text" id="tipo" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Descripcion: </td>
                    <td><input type="text" id="descripcion" style="width: 250px"></td>
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
        

        $("#ventanaOrden").jqxWindow({showCollapseButton: false, height: 600, width: 350, theme: theme,
        resizable: false, keyboardCloseKey: -1});
        if ($("#id").val() == 0){
            $("#titulo").text('Nuevo Orden');
        } else {
            $("#titulo").text('Editar Orden');
            datos = {
                id: $("#id").val()
            };
            $.post('/orden/getOrden', datos, function(data){
                $("#tipo").val(data.tipo);
                $("#fecha").val(data.fecha);
                $("#descripcion").val(data.descripcion);

            }
            , 'json');
        };

//bueno me voy
//Despues seguimos viendo.


        $("#fecha").jqxDateTimeInput({width: 200, height: 25, theme: theme, disabled: false});
        $('#descripcion').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

         $('#form').jqxValidator({ rules: [
                    { input: '#tipo', message: 'Debe ingresar el tipo del orden!',  rule: 'required' },
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
                $('#ventanaOrden').ajaxloader();
                datos = {
                    id: $("#id").val(),
                    fecha: $('#fecha').val(),
                    tipo: $('#tipo').val(),
                    descripcion: $('#descripcion').val()
                }
                $.post('/orden/saveOrden', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/orden');
                    } else {
                        new Messi('Hubo un error guardando el orden', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                        $('#ventanaOrden').ajaxloader('hide');
                    }
                }, 'json');
            }
        });                
    });
</script>