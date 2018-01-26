<div id="ventanacalculadoraVista">
    <div id="titulo">
        Calculadora
    </div>
    <div>
        <form id="form">
            <table>

                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Buscador: </td>
                    <td><div id="buscador"><input type="text" ></td></div>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Tipo de Bono: </td>
                    <td><div id="cmbTipoBono"></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Emisor: </td>
                    <td><div id="cmbEmisor"></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Bono: </td>
                    <td><div id="cmbBono"></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Código Caja: </td>
                    <td><div id="codigoCaja"><input type="text"></td></div>
                </tr>

                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Código Isin: </td>
                    <td><div id="codigoIsin"><input type="text"></td></div>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Por favor, indique un precio expresado Cada 100 Valores Nominales en moneda de origen: </td>
                    <td><div id="precio"><input type="text" ></td></div>
                </tr>
                
                <tr>
                    <td colspan="2" style="text-align: center" >
                        <input type="button" id="calcularButton" value="Calcular">
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

        $("#ventanacalculadoraVista").jqxWindow({showCollapseButton: false, showCloseButton: false, height: 400, 
            width: 350, theme: theme, resizable: false, keyboardCloseKey: -1});

        var srcTipoBono =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'id'},
                        { name: 'nombre' }
                    ],
                    id: 'id',
                    url: '/tipoBono/getTiposBono',
                    async: false
                };
        var DATipoBono = new $.jqx.dataAdapter(srcTipoBono);
        
        $("#cmbTipoBono").jqxDropDownList({ selectedIndex: -1, source: DATipoBono, displayMember: "nombre", 
            valueMember: "id", width: 200, height: 25, theme: theme, placeHolder: "Elija un Tipo de Bono:" });

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

        $("#cmbEmisor").jqxDropDownList({ selectedIndex: -1, source: DAEmisor, displayMember: "nombre", 
        valueMember: "id", width: 200, height: 25, theme: theme, placeHolder: "Elija el Emisor:", disabled: false });        


        var srcBono =
            {
                datatype: "json",
                datafields: [
                    { name: 'id'},
                    { name: 'nombre' }
                ],
                id: 'id',
                url: '/bono/getAll',
                data: {
                    buscador: $("#buscador").val(),
                    emisor_id: getDropDown('#cmbEmisor'),
                    tipobono_id: getDropDown('#cmbTipoBono')
                },
                type: 'POST',
                async: false
            };
        var DABono = new $.jqx.dataAdapter(srcBono);
        
        $("#cmbBono").jqxDropDownList({ selectedIndex: -1, source: DABono, displayMember: "nombre", valueMember: "id", width: 200, height: 25, theme: theme, placeHolder: "Elija un Bono:", disabled: false });

        $('#buscador').jqxInput({width: 200, height: 25, theme: theme});

        $('#codigoCaja').jqxInput({width: 200, height: 25, theme: theme, disabled: true});
        
        $('#codigoIsin').jqxInput({width: 200, height: 25, theme: theme, disabled: true});

        $('#precio').jqxInput({width: 200, height: 25, theme: theme});

        $("#calcularButton").jqxButton({width: 100, height: 25, theme: theme});

        $('#cmbBono').on('change', function (event){   
            var args = event.args;
            if (args) {
                
                // index represents the item's index.                      
                var item = args.item;
                var value = item.value;
                
                $.post('/bono/getBono', {id: value}, function(bono){
                    $('#codigoCaja').val(bono.codigocaja);
                    $('#codigoIsin').val(bono.codigoisin);
                }, 'json');
                
            }
        });
        
        $('#buscador').on('change', function (event){     
            cargarComboBono(event);
        });
    
        $('#cmbTipoBono').on('change', function (event){     
            cargarComboBono(event);
        });
        
        $('#cmbEmisor').on('change', function (event){     
            cargarComboBono(event);
        });
        
        function cargarComboBono(event){
            var args = event.args;
            if (args) {
                // index represents the item's index.                      
                var item = args.item;
                var value = item.value;
                srcBono.data = {
                    buscador: $("#buscador").val(),
                    tipobono_id: $("#cmbTipoBono").val(),
                    emisor_id: $("#cmbEmisor").val()
                };
                DABono.dataBind();
                var items = $("#cmbBono").jqxDropDownList('getItems'); 
                if(items.length > 0){
                    $("#cmbBono").jqxDropDownList('selectIndex', 0 ); 
                }
            }
        }
        
////////////////////////////////////////////////////////////////////////////////
        $('#calcularButton').bind('click', function () {
                datos = {
                    precio: $("#precio").val(),
                    bono: $("#cmbBono").val()
                };
                $.post('/Flujo/calcularFlujo', datos, function(flujos){
                    $.redirect('/Calculadora/resultado', {'flujos': flujos});
                }, 'json');
        });
////////////////////////////////////////////////////////////////////////////////               
        
    });
</script>



