<div id="ventanacalculadoraVista">
    <div id="titulo">
        Calculadora
    </div>
    <div>
        <form id="form">
            <table>

                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px">Buscador: </td>
                    <td><input type="text" id="nombre" style="width: 250px"></td>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px; vertical-align: middle">Tipo de Bono: </td>
                    <td><div id="cmbTipoBono"></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px; vertical-align: middle">Emisor: </td>
                    <td><div id="cmbEmisor"></div></td>
                </tr>
                
                <tr>
                    <td style="padding-right:10px; padding-bottom: 10px; vertical-align: middle">Bono: </td>
                    <td><div id="cmbBono"></div></td>
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

        $("#ventanacalculadoraVista").jqxWindow({showCollapseButton: false, showCloseButton: false, height: 400, 
            width: 320, theme: theme, resizable: false, keyboardCloseKey: -1});

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
                    emisor_id: getDropDown('#cmbEmisor'),
                    tipobono_id: getDropDown('#cmbTipoBono')
                },
                type: 'POST',
                async: false
            };
        var DABono = new $.jqx.dataAdapter(srcBono);
        
        $("#cmbBono").jqxDropDownList({ selectedIndex: -1, source: DABono, displayMember: "nombre", 
        valueMember: "id", width: 200, height: 25, theme: theme, placeHolder: "Elija un Bono:", disabled: false });
    
        $('#cmbTipoBono').on('change', function (event){     
            var args = event.args;
            if (args) {
                // index represents the item's index.                      
                var item = args.item;
                var value = item.value;
                srcBono.data = {
                    tipobono_id: value,
                    emisor_id: $("#cmbEmisor").val()
                };
                DABono.dataBind();
            }
            console.log(srcBono.data);
        });

        $('#cmbEmisor').on('change', function (event){     
            var args = event.args;
            if (args) {
                // index represents the item's index.                      
                var item = args.item;
                var value = item.value;
                srcBono.data = {
                    emisor_id: value,
                    tipobono_id: $("#cmbTipoBono").val()
                };
                DABono.dataBind();
            }
            console.log(srcBono.data);
        });




    });
</script>



