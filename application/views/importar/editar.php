

<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaBono" >
    <div id="titulo">
        Editar Bono
    </div>
    <div>
        <form id="form" style="margin-left: 10px; margin-top: 10px">
            <table>
                
<!--                <tr>
                    <td style="padding-right:10px; padding-right:20px">Bono: </td>
                    <td style="padding-top: 10px"><input type="text" id="bonoFlujo" style="width: 250px"></td>
                    
                    <td style="padding-right:10px; padding-left:20px">Tipo de Bono: </td>
                    <td style="padding-top: 10px"><input type="text" id="bonoDato" style="width: 250px"></td>
                </tr>-->
                
                <tr>
                    <td style="padding-right:10px; padding-right:20px"></td>
                    <td style="padding-top: 10px"><input type="button" id="importarFlujosBonosButton" value="Importar Flujo de Planilla Bonos"></td>
                    
                    <td style="padding-right:10px; padding-right:20px"></td>
                    <td style="padding-top: 10px"><input type="button" id="importarFlujosProvincialesButton" value="Importar Flujo de Planilla Provinciales"></td>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-right:20px"></td>
                    <td style="padding-top: 10px"><input type="button" id="importarDatosBonosButton" value="Importar Datos de Planilla Bonos"></td>
                    
                    <td style="padding-right:10px; padding-right:20px"></td>
                    <td style="padding-top: 10px"><input type="button" id="importarDatosProvincialesButton" value="Importar Datos de Planilla Provinciales"></td>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-right:20px"></td>
                    <td style="padding-top: 10px"><input type="button" id="importarEstructurasBonosButton" value="Importar Planilla Estructura de Bonos"></td>
                    
                    <td style="padding-right:10px; padding-right:20px"></td>
                    <td style="padding-top: 10px"></td>
                </tr>
                
            </table>
        </form>
    </div> 
</div>
<script>
    $(function(){
        var theme = getTheme();
        var formOK = false;
        
        $("#ventanaBono").jqxWindow({showCollapseButton: false, height: 450, width: 800, maxWidth: 1200, theme: theme,
        resizable: false, keyboardCloseKey: -1});
        if ($("#id").val() == 0){
            $("#titulo").text('Nuevo Bono');
        } else {
            $("#titulo").text('Editar Bono');
            datos = {
                id: $("#id").val()
            };
            $.post('/bono/getBono', datos, function(data){
//                $("#nombre").val(data.nombre);
            }
            , 'json');
        };


//
        $('#importarFlujosBonosButton').jqxButton({ theme: theme, width: '350px' });
        
        $('#importarFlujosProvincialesButton').jqxButton({ theme: theme, width: '350px' });
        
        $('#importarDatosBonosButton').jqxButton({ theme: theme, width: '350px' });
        
        $('#importarDatosProvincialesButton').jqxButton({ theme: theme, width: '350px' });

        $('#importarEstructurasBonosButton').jqxButton({ theme: theme, width: '350px' });






        $("#importarFlujosBonosButton").click(function(){
                    $.redirect('/flujo/getImportarFlujosAllBonos');
                });


        $("#importarFlujosProvincialesButton").click(function(){
                    $.redirect('/flujo/getImportarFlujosAllProvinciales');
                });    

        $("#importarDatosBonosButton").click(function(){
                    $.redirect('/flujo/getImportarDatosAllBonos');
                });       

        $("#importarDatosProvincialesButton").click(function(){
                    $.redirect('/flujo/getImportarDatosAllProvinciales');
                });         

        $("#importarEstructurasBonosButton").click(function(){
                    $.redirect('/flujo/getImportarEstructurasBonos');
                });

        
//        $('#aceptarButton').bind('click', function () {
//            $('#form').jqxValidator('validate');
//            if (formOK){
//                $('#ventanaBono').ajaxloader();
//                datos = {
//                    id: $("#id").val(),
//                    nombre: $('#nombre').val(),
//                    emisor_id: $("#cmbEmisor").jqxDropDownList('getSelectedItem').value,
//                    tipobono_id: $("#cmbTipoBono").jqxDropDownList('getSelectedItem').value,
//                    codigocaja: $("#codigoCaja").val(),
//                    codigoisin: $("#codigoIsin").val(),
//                    monedacobro: $("#cmbMonedaCobro").val(),                 
//                    monedabono: $("#cmbMonedaBono").val(),
//                    tipotasa: $("#cmbTipoTasa").val(),
//                    tipotasavariable: $("#tipoTasaVariable").val(),
//                    cer: $("#cer").val(),
//                    cupon: $("#cupon").val(),
//                    cantidadcuponanual: $("#cantidadCuponAnual").val(),                    
//                    vencimiento: $("#vencimiento").val(),             
//                    capitalresidual: $("#capitalResidual").val(),
//                    ultimoprecio: $("#ultimoPrecio").val(),
//                    oustanding: $("#oustanding").val(),
//                    proximointeres: $("#proximoInteres").val(),
//                    proximoamortizacion: $("#proximoAmortizacion").val(),
//                    legislacion: $("#legislacion").val(),
//                    denominacionminima: $("#denominacionMinima").val(),
//                    libro: $("#libro").val(),
//                    hoja: $("#cmbHoja").val(),
//                    actualizacionAutomatica: $("#actualizacionAutomatica").val()
//                };
//
//                $.post('/bono/saveBono', datos, function(data){
//                    if (data.id > 0){
//                        $.redirect('/bono');
//                    } else {
//                        new Messi('Hubo un error guardando el bono', {title: 'Error', 
//                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
//                        $('#ventanaBono').ajaxloader('hide');
//                    }
//                }, 'json');
//            }
//        });  
    });
</script>