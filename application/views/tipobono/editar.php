<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaTipoBono">
    <div id="titulo">
        Editar Tipos de Bono
    </div>
    <div>
        <form id="form">
            <table>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">TipoBono: </td>
                    <td><input type="text" id="nombre" style="width: 250px"></td>
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
        
        $("#ventanaTipoBono").jqxWindow({showCollapseButton: false, height: 100, width: 350, theme: theme,
        resizable: false, keyboardCloseKey: -1});
        if ($("#id").val() == 0){
            $("#titulo").text('Nuevo Tipo de Bono');
        } else {
            $("#titulo").text('Editar Tipo de Bono');
            datos = {
                id: $("#id").val()
            };
            $.post('/tipoBono/getTipoBono', datos, function(data){
                $("#nombre").val(data.nombre);
            }
            , 'json');
        };
         $('#form').jqxValidator({ rules: [
                    { input: '#nombre', message: 'Debe ingresar el nombre del tipo de bono!',  rule: 'required' },
                    { input: '#nombre', message: 'Ya existe un tipo de bono con ese nombre!',  rule: function(){
                            datos = {
                                tabla: 'tipobono',
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
                    }}], 
                    theme: theme
        });
        $('#form').bind('validationSuccess', function (event) { formOK = true; });
        $('#form').bind('validationError', function (event) { formOK = false; }); 
        $('#aceptarButton').jqxButton({ theme: theme, width: '65px' });
        $('#aceptarButton').bind('click', function () {
            $('#form').jqxValidator('validate');
            if (formOK){
                $('#ventanaTipoBono').ajaxloader();
                datos = {
                    id: $("#id").val(),
                    nombre: $('#nombre').val()
                }
                $.post('/tipoBono/saveTipoBono', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/tipoBono');
                    } else {
                        new Messi('Hubo un error guardando el tipo de bono', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                        $('#ventanaTipoBono').ajaxloader('hide');
                    }
                }, 'json');
            }
        });                
    });
</script>