

<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaBono" >
    <div id="titulo">
        Editar Bono
    </div>
    <div>
        <form id="form" style="margin-left: 10px; margin-top: 10px">
            <table>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Bono: </td>
                    <td><input type="text" id="nombre" style="width: 250px"></td>
                    
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle; padding-left:20px">Tipo de Bono: </td>
                    <td><div id="cmbTipoBono" ></div></td>

                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Emisor: </td>
                    <td><div id="cmbEmisor"></div></td>

                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle; padding-left:20px">Código Caja: </td>
                    <td><input type="text" id="codigoCaja" style="width: 250px" class="text-input"></td>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Código Isin: </td>
                    <td><input type="text" id="codigoIsin" style="width: 250px" class="text-input"></td>
          
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle; padding-left:20px">Moneda de Cobro: </td>
                    <td><div id="cmbMonedaCobro"></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Moneda de Bono: </td>
                    <td><div id="cmbMonedaBono"></div></td>
                
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle; padding-left:20px">Tipo de Tasa: </td>
                    <td><div id="cmbTipoTasa"></div></td>
                </tr>        
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Tipo de tasa variable: </td>
                    <td><input type="text" id="tipoTasaVariable" style="width: 250px" class="text-input"></td>
                
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle; padding-left:20px">CER aplicable: </td>
                    <td><input type="text" id="cer" style="width: 250px" class="text-input"></td>
                </tr>                
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Cupón: </td>
                    <td><input type="text" id="cupon" style="width: 250px" class="text-input"></td>
                
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle; padding-left:20px">Cantidad de cupones al año: </td>
                    <td><input type="text" id="cantidadCuponAnual" style="width: 250px" class="text-input"></td>
                </tr>
                 
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Vencimiento: </td>
                    <td><input type="text" id="vencimiento" style="width: 250px" class="text-input"></td>
                
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle; padding-left:20px">Capital Residual: </td>
                    <td><input type="text" id="capitalResidual" style="width: 250px" class="text-input"></td>
                </tr>                
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Último Precio en Moneda de Origen: </td>
                    <td><input type="text" id="ultimoPrecio" style="width: 250px" class="text-input"></td>
                
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle; padding-left:20px">Oustanding (mln): </td>
                    <td><input type="text" id="oustanding" style="width: 250px" class="text-input"></td>
                </tr>   
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Próximo Pago de Intereses: </td>
                    <td><input type="text" id="proximoInteres" style="width: 250px" class="text-input"></td>
                
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle; padding-left:20px">Próximo Pago de amortizaciones: </td>
                    <td><input type="text" id="proximoAmortizacion" style="width: 250px" class="text-input"></td>
                </tr> 
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Legislación: </td>
                    <td><input type="text" id="legislacion" style="width: 250px" class="text-input"></td>
                
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle; padding-left:20px">Denominación Mïnima: </td>
                    <td><input type="text" id="denominacionMinima" style="width: 250px" class="text-input"></td>
                </tr> 
                
                

                
                <tr>
                    <td colspan="4" style="text-align: right; padding-top: 20px">
                        <input type="text" id="libro">
                    </td>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle; padding-left:20px">Hoja: </td>
                    <td><div id="cmbHoja" ></div></td>
                </tr>
                
               
                <tr>
                    <td colspan="4" style="text-align: right; padding-top: 20px">
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
        
        $("#ventanaBono").jqxWindow({showCollapseButton: false, height: 520, width: 950, maxWidth: 1200, theme: theme,
        resizable: false, keyboardCloseKey: -1});
        if ($("#id").val() == 0){
            $("#titulo").text('Nuevo Bono');
        } else {
            $("#titulo").text('Editar Bono');
            datos = {
                id: $("#id").val()
            };
            $.post('/bono/getBono', datos, function(data){
                $("#nombre").val(data.nombre);
            }
            , 'json');
        };

        $('#nombre').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        var srcTipoBono =
            {
                datatype: "json",
                datafields: [
                    { name: 'id'},
                    { name: 'nombre' }
                ],
                id: 'id',
                url: '/tipobono/getTiposBono',
                async: false
            };
        var DATipoBono = new $.jqx.dataAdapter(srcTipoBono);
        $("#cmbTipoBono").jqxDropDownList({ selectedIndex: -1, source: DATipoBono, displayMember: "nombre", valueMember: "id", width: 200, height: 25, theme: theme, placeHolder: "Elija un tipo de bono:" });

        var srcEmisor =
            {
                datatype: "json",
                datafields: [
                    { name: 'id'},
                    { name: 'nombre' }
                ],
                id: 'id',
                url: '/emisor/getEmisores',
                async: false
            };
        var DAEmisor = new $.jqx.dataAdapter(srcEmisor);
        $("#cmbEmisor").jqxDropDownList({ selectedIndex: -1, source: DAEmisor, displayMember: "nombre", valueMember: "id", width: 200, height: 25, theme: theme, placeHolder: "Elija un emisor:" });
        
        $('#codigoCaja').jqxInput({width: 200, height: 25, theme: theme, disabled: false});
        
        $('#codigoIsin').jqxInput({width: 200, height: 25, theme: theme, disabled: false});
          
        var srcMonedaCobro =
            {
                datatype: "json",
                datafields: [
                    { name: 'id'},
                    { name: 'nombre' }
                ],
                id: 'id',
                url: '/moneda/getMonedas',
                async: false
            };
        var DAMonedaCobro = new $.jqx.dataAdapter(srcMonedaCobro);
        $("#cmbMonedaCobro").jqxDropDownList({ selectedIndex: -1, source: DAMonedaCobro, displayMember: "nombre", 
        valueMember: "id", width: 200, height: 25, theme: theme, placeHolder: "Elija Moneda de Cobro:" });
        
        var srcMonedaBono =
            {
                datatype: "json",
                datafields: [
                    { name: 'id'},
                    { name: 'nombre' }
                ],
                id: 'id',
                url: '/moneda/getMonedas',
                async: false
            };
        var DAMonedaBono = new $.jqx.dataAdapter(srcMonedaBono);       
        $("#cmbMonedaBono").jqxDropDownList({ selectedIndex: -1, source: DAMonedaBono, displayMember: "nombre", 
        valueMember: "id", width: 200, height: 25, theme: theme, placeHolder: "Elija Moneda de Bono:", disabled: false });  

        var dataTipoTasa = [
            { id: 'F', nombre: "Fija" },
            { id: 'V', nombre: "Variable" }
        ];

        var srcTipoTasa = {
            localdata: dataTipoTasa,
            datatype: "array",
            datafields: [
                { name: 'id' },
                { name: 'nombre' }
            ]
        };
        var DATipoTasa = new $.jqx.dataAdapter(srcTipoTasa);
        $("#cmbTipoTasa").jqxDropDownList({ selectedIndex: -1, source: DATipoTasa, displayMember: "nombre", 
        valueMember: "id", width: 200, height: 20, theme: theme, placeHolder: "Elija Tipo de Tasa:", disabled: false });

        $('#tipoTasaVariable').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        $("#cer").jqxCheckBox({ width: 200, height: 20, theme: theme });

        $('#cupon').jqxNumberInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#cantidadCuponAnual').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        $("#vencimiento").jqxDateTimeInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#capitalResidual').jqxNumberInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#ultimoPrecio').jqxNumberInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#oustanding').jqxNumberInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#proximoInteres').jqxDateTimeInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#proximoAmortizacion').jqxDateTimeInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#legislacion').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#denominacionMinima').jqxNumberInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#form').jqxValidator({ rules: [
                    { input: '#nombre', message: 'Debe ingresar el nombre del bono!',  rule: 'required' },
                    { input: '#nombre', message: 'Ya existe un bono con ese nombre!',  rule: function(){
                            datos = {
                                tabla: 'bono',
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
                    }},
                
                    { input: '#codigoCaja', message: 'Ya existe un bono con ese código CAJA!',  rule: function(){
                            datos = {
                                tabla: 'bono',
                                campo: 'codigocaja',
                                valor: $('#codigoCaja').val(),
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
                    }},
                
                    { input: '#codigoIsin', message: 'Ya existe un bono con ese código ISIN!',  rule: function(){
                            datos = {
                                tabla: 'bono',
                                campo: 'codigoisin',
                                valor: $('#codigoIsin').val(),
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
                    }}
                    ], 
                    theme: theme
        });
        
        $('#form').bind('validationSuccess', function (event) { formOK = true; });
        $('#form').bind('validationError', function (event) { formOK = false; }); 


        $('#libro').jqxButton({ theme: theme, width: '300px' });
        
        $("#cmbHoja").jqxComboBox({width: 200, height: 25, theme: theme, placeHolder: "Elija Hoja:"});

        $('#aceptarButton').jqxButton({ theme: theme, width: '75px' });

        $('#libro').on('change', function () {
                datos = {
                    archivo: $("#libro").val()
                }
                $.post('/util/adjuntarExcel', datos, function(data){
                    $.each(data.resultado, function (index, value) {
                        $("#cmbHoja").jqxComboBox('addItem', value);
                    });

                }, 'json');

                
        });  
        
        
        /*        
        var srcTest =
            {
                datatype: "json",
                datafields: [
                    { name: 'id'},
                    { name: 'nombre' }
                ],
                id: 'id',
                url: '/tipobono/getTiposBono',
                async: false
            };
        var DATest = new $.jqx.dataAdapter(srcTest);

        $('#cmbTipoBono').on('change', function (event){     
            var args = event.args;
            if (args) {
                // index represents the item's index.                      
                var item = args.item;
                var value = item.value;
                srcTest.data = {
                    //vencimiento: $("#vencimiento").val() // {vencimiento: "30/10/2017"}
                    //vencimiento: $("#vencimiento").val('val', "2013/3/3"), //{vencimiento: "30/10/2017"}
                };
                DATest.dataBind();
            }
            console.log(srcTest.data);
        });
*/      
        
        
        $('#aceptarButton').bind('click', function () {
            $('#form').jqxValidator('validate');
            if (formOK){
                $('#ventanaBono').ajaxloader();
                datos = {
                    id: $("#id").val(),
                    nombre: $('#nombre').val(),
                    emisor_id: $("#cmbEmisor").jqxDropDownList('getSelectedItem').value,
                    tipobono_id: $("#cmbTipoBono").jqxDropDownList('getSelectedItem').value,
                    codigocaja: $("#codigoCaja").val(),
                    codigoisin: $("#codigoIsin").val(),
                    
                    monedacobro: $("#cmbMonedaCobro").val(),                 
                    monedabono: $("#cmbMonedaBono").val(),
                    tipotasa: $("#cmbTipoTasa").val(),
                    tipotasavariable: $("#tipoTasaVariable").val(),
                    cer: $("#cer").val(),
                    cupon: $("#cupon").val(),
                    cantidadcuponanual: $("#cantidadCuponAnual").val(),                    
                    vencimiento: $("#vencimiento").val(),             
                    capitalresidual: $("#capitalResidual").val(),
                    ultimoprecio: $("#ultimoPrecio").val(),
                    oustanding: $("#oustanding").val(),
                    proximointeres: $("#proximoInteres").val(),
                    proximoamortizacion: $("#proximoAmortizacion").val(),
                    legislacion: $("#legislacion").val(),
                    denominacionminima: $("#denominacionMinima").val(),
                    
                    libro: $("#libro").val(),
                    hoja: $("#cmbHoja").val()
                }

                $.post('/bono/saveBono', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/bono');
                    } else {
                        new Messi('Hubo un error guardando el bono', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                        $('#ventanaBono').ajaxloader('hide');
                    }
                }, 'json');
            }
        });  
    });
</script>