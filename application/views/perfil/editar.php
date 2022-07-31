<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaPerfil">
    <div id="titulo">
        Editar Perfil
    </div>
    <div>
        <form id="form">
            <table>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Nombre: </td>
                    <td><input type="text" id="nombre" style="width: 208px"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Apellido: </td>
                    <td><input type="text" id="apellido" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 0px">Soltero: </td>
                    <td><input type="text" id="esSoltero" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-left:1px">Color: </td>
                    <td style="padding-top: 10px"><div id="cmbColor" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-left:1px">Comida: </td>
                    <td style="padding-top: 10px"><div id="cmbComida" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-left:1px">Musica: </td>
                    <td style="padding-top: 10px"><div id="cmbMusica" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-left:1px">Pelicula: </td>
                    <td style="padding-top: 10px"><div id="cmbPelicula" ></div></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 0px">Deportista: </td>
                    <td style="padding-top: 5px"><input type="text" id="esDeportista" style="width: 250px"></td>
                </tr>
                <tr>
                    <td style="padding-right:10px; padding-bottom: 0px">Vegetariano: </td>
                    <td style="padding-top: 5px"><input type="text" id="esVegetariano" style="width: 250px"></td>
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
        
        $("#ventanaPerfil").jqxWindow({showCollapseButton: false, height: 380, width: 370, theme: theme,
        resizable: false, keyboardCloseKey: -1});
        if ($("#id").val() == 0){
            $("#titulo").text('Nuevo Perfil');
        } else {
            $("#titulo").text('Editar Perfil');
            datos = {
                id: $("#id").val()
            };
            $.post('/perfil/getPerfil', datos, function(data){
                $("#nombre").val(data.nombre);
                $("#apellido").val(data.apellido);
                $("#esSoltero").val(data.esSoltero);
                $("#cmbColor").val(data.color);
                $("#cmbComida").val(data.comida);
                $("#cmbMusica").val(data.musica);
                $("#cmbPelicula").val(data.pelicula);
                $("#esDeportista").val(data.esDeportista);
                $("#esVegetariano").val(data.esVegetariano);                                
            }
            , 'json');
        };

        $('#nombre').jqxInput({width: 200, height: 25, theme: theme, disabled: false});
        $('#apellido').jqxInput({width: 200, height: 25, theme: theme, disabled: false});
        $("#esSoltero").jqxCheckBox({ width: 200, height: 40, theme: theme });
        $("#esDeportista").jqxCheckBox({ width: 200, height: 40, theme: theme });
        $("#esVegetariano").jqxCheckBox({ width: 200, height: 20, theme: theme });

        //$('#esSoltero').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        var srcColor =
            {
                datatype: "json",
                datafields: [
                    { name: 'id'},
                    { name: 'descripcion' }
                ],
                id: 'id',
                url: '/perfil/getColores',
                async: false
            };
        var DAColor = new $.jqx.dataAdapter(srcColor);
        $("#cmbColor").jqxDropDownList({ selectedIndex: -1, source: DAColor, displayMember: "descripcion", valueMember: "id", width: 150, height: 25, theme: theme, placeHolder: "Elija un Color:" });

        var srcComida =
            {
                datatype: "json",
                datafields: [
                    { name: 'id'},
                    { name: 'descripcion' }
                ],
                id: 'id',
                url: '/perfil/getComidas',
                async: false
            };
        var DAComida = new $.jqx.dataAdapter(srcComida);
        $("#cmbComida").jqxDropDownList({ selectedIndex: -1, source: DAComida, displayMember: "descripcion", valueMember: "id", width: 150, height: 25, theme: theme, placeHolder: "Elija una Comida:" });

        var srcMusica =
            {
                datatype: "json",
                datafields: [
                    { name: 'id'},
                    { name: 'descripcion' }
                ],
                id: 'id',
                url: '/perfil/getMusicas',
                async: false
            };
        var DAMusica = new $.jqx.dataAdapter(srcMusica);
        $("#cmbMusica").jqxDropDownList({ selectedIndex: -1, source: DAMusica, displayMember: "descripcion", valueMember: "id", width: 150, height: 25, theme: theme, placeHolder: "Elija una Musica:" });

        var srcPelicula =
            {
                datatype: "json",
                datafields: [
                    { name: 'id'},
                    { name: 'descripcion' }
                ],
                id: 'id',
                url: '/perfil/getPeliculas',
                async: false
            };
        var DAPelicula = new $.jqx.dataAdapter(srcPelicula);
        $("#cmbPelicula").jqxDropDownList({ selectedIndex: -1, source: DAPelicula, displayMember: "descripcion", valueMember: "id", width: 150, height: 25, theme: theme, placeHolder: "Elija una Pelicula:" });


        $('#form').jqxValidator({ rules: [
                    { input: '#nombre', message: 'Debe ingresar el nombre del perfil!',  rule: 'required' },
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
                $('#ventanaPerfil').ajaxloader();
                datos = {
                    id: $("#id").val(),
                    nombre: $('#nombre').val(),
                    apellido: $('#apellido').val(),
                    esSoltero: $('#esSoltero').val(),
                    color: $("#cmbColor").jqxDropDownList('getSelectedItem').value,
                    comida: $("#cmbComida").jqxDropDownList('getSelectedItem').value,
                    musica: $("#cmbMusica").jqxDropDownList('getSelectedItem').value,
                    pelicula: $("#cmbPelicula").jqxDropDownList('getSelectedItem').value,
                    esDeportista: $('#esDeportista').val(),
                    esVegetariano: $('#esVegetariano').val()
                }
                $.post('/perfil/savePerfil', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/perfil');
                    } else {
                        new Messi('Hubo un error guardando el perfil', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                        $('#ventanaPerfil').ajaxloader('hide');
                    }
                }, 'json');
            }
        });                
    });
</script>