<div id="ventanaResumen">
    <div id="titulo">
        Planilla Estructura Bonos
    </div>
    <div id="botonera">
        <table boder="0" cellpadding="2" cellspacing="2">
            <tr>
                
                <td style = "padding-bottom: 10px"><div  id="cmbBono"></div></td>
                <br>
                <td style = "padding-bottom: 10px"><div id="cmbFecha" ></div></td>
                <br>
            </tr>
            
            <tr>
                <td style = "padding-bottom: 10px" colspan ="3" ><div id="grilla"></div></td>
            </tr>    
            
            <tr>    
                <td><input type="button" value="Generar Excel" id="generarButton"></td>
            </tr>
        </table>
    </div>
    
</div>

<script>
$(document).ready(function () {
        var theme = getTheme();
        var formOK = false;
        
//        var id = 0;
//        var enviar = [];
//        var generar = [];
//        var cierreFecha;       
        
//        var Bono_id = 0;
//        var cierre_id = 0;
        var bono = '';
        var fecha = '';

        var url = '';


        $("#ventanaResumen").jqxWindow({showCollapseButton: false, maxWidth: 2000, height: 1000, width: 1500, theme: theme,
        resizable: false, keyboardCloseKey: -1});

////Dropdown Bono
////////////////////////////////////////////////////////////////////////////////
        var srcEstructuraBono =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'id'},
                        { name: 'nombre' }
                    ],
                    id: 'id',
                    url: '/bono/getBonos',
                    async: false
                };
        var DAEstructuraBono = new $.jqx.dataAdapter(srcEstructuraBono);


        $("#cmbBono").jqxDropDownList({ selectedIndex: -1, source: DAEstructuraBono, displayMember: "nombre", 
        valueMember: "id", width: 150, height: 25, theme: theme, placeHolder: "Elija Especie Byma:", disabled: false });
////////////////////////////////////////////////////////////////////////////////   

//Dropdown Fecha
////////////////////////////////////////////////////////////////////////////////        
        var srcFecha =
                {
                    datatype: "json",
                    datafields: [
                        { name: 'id'},
                        { name: 'fechaActualizacion' }
                    ],
//                    data: {
//                        bono: bono
//                    },
                    id: 'id',
                    url: '/estructuraBono/getFechaActualizacion',
                    type: 'post',
                    async: false
                };
        var DAFecha = new $.jqx.dataAdapter(srcFecha);


//        $("#cmbFecha").jqxDropDownList({ selectedIndex: -1, source: DAFecha, displayMember: "fechaActualizacion", 
//        valueMember: "id", width: 150, height: 25, theme: theme, placeHolder: "Elija la fecha:", disabled: false });
    
        $("#cmbFecha").jqxDropDownList({ selectedIndex: -1, source: DAFecha, displayMember: "fechaActualizacion", 
            valueMember: "id", width: 200, height: 25, theme: theme, placeHolder: "Elija la fecha:", renderer: function (index, label, value){
                return moment(label).format('DD/MM/YYYY HH:mm');
            }  
        });    
////////////////////////////////////////////////////////////////////////////////


//Esta es la Grilla
////////////////////////////////////////////////////////////////////////////////
        var source = {
                datatype: "json",
                datafields: [
                { name: 'id'},
                { name: 'bono'},
                
                { name: 'tipoInstrumentoImpuesto'},
                { name: 'tipoAjuste'},
                { name: 'tipoInstrumento'},
                { name: 'nombreConocido'},
                { name: 'tipoEmisor'},
                { name: 'emisor'},
                { name: 'monedacobro'},
                { name: 'monedaEmision'},
                { name: 'cerInicial'},
                { name: 'diasPreviosCer'},
                { name: 'especieCaja'},
                { name: 'isin'},
                { name: 'nombre'},
                { name: 'fechaEmision'},
                { name: 'fechaVencimiento'},
                { name: 'oustanding'},
                { name: 'ley'},
                { name: 'amortizacion'},
                { name: 'tipoTasa'},
                { name: 'tipoTasaVariable'},
                { name: 'spread'},
                { name: 'tasaMinima'},
                { name: 'tasaMaxima'},
                { name: 'cuponAnual'},
                { name: 'cantidadCuponesAnio'},
                { name: 'frecuenciaCobro'},
                { name: 'fechasCobroCupon'},
                { name: 'formulaCalculoInteres'},
                { name: 'diasPreviosRecord'},
                { name: 'proximoCobroInteres'},
                { name: 'proximoCobroCapital'},
                { name: 'duration'},
                { name: 'precioMonedaOrigen'},
                { name: 'lastYtm'},
                { name: 'paridad'},
                { name: 'currentYield'},
                { name: 'interesesCorridos'},
                { name: 'valorResidual'},
                { name: 'valorTecnico'},
                { name: 'mDuration'},
                { name: 'convexity'},
                { name: 'denominacionMinima'},
                { name: 'spreadSinTasa'},
                { name: 'ultimaTna'},
                { name: 'diasInicioCupon'},
                { name: 'diasFinalCupon'},
                { name: 'capitalizacionInteres'},
                
                { name: 'especiesRelacionadas'},
                { name: 'curva'},
                { name: 'variableCurva'},
                { name: 'tnaUltimaLicitacion'},
                { name: 'diasVencimiento'},
                { name: 'variableLicitacionPb'},
                { name: 'cuponPbiD'},
                { name: 'cuponPbiW'},
                
                
                { name: 'precioPesos'}

            ],
            cache: false,
//            url: '/estructuraBono/grillaEstructuraBonoFecha',
            
            url: url,
            
            data: {
                //bono: bono
                fechaActualizacion: fecha
            },
            type: 'post'
        };

        dataadapter = new $.jqx.dataAdapter(source);

        $("#grilla").jqxGrid(
        {		
                source: dataadapter,
                theme: theme,
                filterable: true,
                filtermode: 'excel',
                sortable: true,
                autoheight: false,
                pageable: false,
                virtualmode: false,
                selectionmode: 'checkbox',
                columnsresize: true,
                showstatusbar: true,
                statusbarheight: 25,
                showaggregates: true,
                width: 1480,
                height: 400,
                columns: [
                        { text: 'Id', datafield: 'id', width: 80, cellsalign: 'right', cellsformat: 'd', aggregates: ['count'], hidden: true  },
                        { text: 'Especie Byma', datafield: 'bono', width: 110 },
                        { text: 'tipoInstrumentoImpuesto', datafield: 'tipoInstrumentoImpuesto', width: 140 },
                        { text: 'tipoAjuste', datafield: 'tipoAjuste', width: 115 },
                        { text: 'tipoInstrumento', datafield: 'tipoInstrumento', width: 120 },
                        { text: 'nombreConocido', datafield: 'nombreConocido', width: 120 },
                        { text: 'tipoEmisor', datafield: 'tipoEmisor', width: 140 },
                        { text: 'emisor', datafield: 'emisor', width: 140 },
                        { text: 'monedacobro', datafield: 'monedacobro', width: 120 },
                        { text: 'monedaEmision', datafield: 'monedaEmision', width: 120 },
                        { text: 'cerInicial', datafield: 'cerInicial', width: 110 },
                        { text: 'diasPreviosCer', datafield: 'diasPreviosCer', width: 120 },
                        { text: 'especieCaja', datafield: 'especieCaja', width: 120 },
                        { text: 'isin', datafield: 'isin', width: 120 },
                        { text: 'nombre', datafield: 'nombre', width: 120 },
                        { text: 'fechaEmision', datafield: 'fechaEmision', width: 120 },
                        { text: 'fechaVencimiento', datafield: 'fechaVencimiento', width: 120 },
                        { text: 'oustanding', datafield: 'oustanding', width: 120 },
                        { text: 'ley', datafield: 'ley', width: 120 },
                        { text: 'amortizacion', datafield: 'amortizacion', width: 120 },
                        { text: 'tipoTasa', datafield: 'tipoTasa', width: 120 },
                        { text: 'tipoTasaVariable', datafield: 'tipoTasaVariable', width: 120 },
                        { text: 'spread', datafield: 'spread', width: 120 },
                        { text: 'tasaMinima', datafield: 'tasaMinima', width: 120 },
                        { text: 'tasaMaxima', datafield: 'tasaMaxima', width: 120 },
                        { text: 'cuponAnual', datafield: 'cuponAnual', width: 120, cellsformat: 'p2'},
                        { text: 'cantidadCuponesAnio', datafield: 'cantidadCuponesAnio', width: 120 },
                        { text: 'frecuenciaCobro', datafield: 'frecuenciaCobro', width: 120 },
                        { text: 'fechasCobroCupon', datafield: 'fechasCobroCupon', width: 120 },
                        { text: 'formulaCalculoInteres', datafield: 'formulaCalculoInteres', width: 120 },
                        { text: 'diasPreviosRecord', datafield: 'diasPreviosRecord', width: 120 },
                        { text: 'proximoCobroInteres', datafield: 'proximoCobroInteres', width: 120 },
                        { text: 'proximoCobroCapital', datafield: 'proximoCobroCapital', width: 120 },
                        { text: 'duration', datafield: 'duration', width: 120 },
                        { text: 'precioMonedaOrigen', datafield: 'precioMonedaOrigen', width: 120 },
                        { text: 'lastYtm', datafield: 'lastYtm', width: 120 },
                        { text: 'paridad', datafield: 'paridad', width: 120 },
                        { text: 'currentYield', datafield: 'currentYield', width: 120 },
                        { text: 'interesesCorridos', datafield: 'interesesCorridos', width: 120 },
                        { text: 'valorResidual', datafield: 'valorResidual', width: 120 },
                        { text: 'valorTecnico', datafield: 'valorTecnico', width: 120 },
                        { text: 'mDuration', datafield: 'mDuration', width: 120 },
                        { text: 'convexity', datafield: 'convexity', width: 120 },
                        { text: 'denominacionMinima', datafield: 'denominacionMinima', width: 120 },
                        { text: 'spreadSinTasa', datafield: 'spreadSinTasa', width: 120 },
                        { text: 'ultimaTna', datafield: 'ultimaTna', width: 120 },
                        { text: 'diasInicioCupon', datafield: 'diasInicioCupon', width: 120 },
                        { text: 'diasFinalCupon', datafield: 'diasFinalCupon', width: 120 },
                        { text: 'capitalizacionInteres', datafield: 'capitalizacionInteres', width: 120 },
                        
                        
                        { text: 'especiesRelacionadas', datafield: 'especiesRelacionadas', width: 120 },
                        { text: 'curva', datafield: 'curva', width: 120 },
                        { text: 'variableCurva', datafield: 'variableCurva', width: 120 },
                        { text: 'tnaUltimaLicitacion', datafield: 'tnaUltimaLicitacion', width: 120 },
                        { text: 'diasVencimiento', datafield: 'diasVencimiento', width: 120 },
                        { text: 'variableLicitacionPb', datafield: 'variableLicitacionPb', width: 120 },
                        { text: 'cuponPbiD', datafield: 'cuponPbiD', width: 120 },
                        { text: 'cuponPbiW', datafield: 'cuponPbiW', width: 120 },
                                               
                        
                        { text: 'precioPesos', datafield: 'precioPesos', width: 120 },
                        
//                        { text: 'fecha', datafield: 'fecha', width: 120},
//                        
//                        { text: 'vr', datafield: 'vr', width: 150, cellsformat: 'd'},
//                        { text: 'amortizacion', datafield: 'amortizacion', width: 150, cellsformat: 'd'},
//                        
//                        { text: 'VNActualizado', datafield: 'VNActualizado', width: 150, cellsformat: 'd'},
//                        { text: 'VRActualizado', datafield: 'VRActualizado', width: 150, cellsformat: 'd'},
//                        { text: 'cuponAmortizacion', datafield: 'cuponAmortizacion', width: 150, cellsformat: 'd'},
//                        { text: 'cuponInteres', datafield: 'cuponInteres', width: 150, cellsformat: 'd'},
//                        { text: 'totalFlujo', datafield: 'totalFlujo', width: 200, cellsformat: 'd'}
                ]
        });
        $("#grilla").on("bindingcomplete", function (event){
            var localizationobj = getLocalization();
            $("#grilla").jqxGrid('localizestrings', localizationobj);
        }); 
////////////////////////////////////////////////////////////////////////////////        
        
////////////////////////////////////////////////////////////////////////////////
//Inst

        $('#cmbBono').on('change', function (event){            
//            console.log(event.args.item.label);
            
//            srcFecha.data = {
//                bono: event.args.item.label
//            };     
//            DAFecha.dataBind();
            
            
            
            source.url = '/estructuraBono/grillaEstructuraBono';

            console.log(url);

            source.data = {
                bono: event.args.item.label
//                fecha: $("#cmbFecha").jqxDropDownList('getSelectedItem').value
            };      
            dataadapter.dataBind();
            
            $("#cmbFecha").jqxDropDownList({ selectedIndex: -1});
            
        });




//Cierre
        $('#cmbFecha').on('change', function (event){
//            if (args) {
//                console.log(event.args.item);
            
                source.url = '/estructuraBono/grillaEstructuraBonoFecha';
            
                console.log(url);
            
                source.data = {
                    fechaActualizacion: event.args.item.label
                };     
//                dataadapter.dataBind();
                
                
                
                dataadapter.dataBind();
//            }
            $("#cmbBono").jqxDropDownList({ selectedIndex: -1});
        });
        
        
////////////////////////////////////////////////////////////////////////////////        

//Botón generar que importa a Excel.
////////////////////////////////////////////////////////////////////////////////        

        $("#generarButton").jqxButton({ width: '160', theme: theme, disabled: false });
                
        $("#generarButton").click(function(){

        ////////////////////////////////////////////////////////////////////////         
        //            var data = $('#grilla').jqxGrid('getboundrows');
        //            var datos = JSON.stringify(data);
        //            $.redirect('/orden/grid2Excel', {data: datos});
        ////////////////////////////////////////////////////////////////////////
            var title = 'GrillaResumen';
            var titles = $('#grilla').jqxGrid('columns').records;
            var titulosArr = Array();
            var data = $('#grilla').jqxGrid('getboundrows');

            console.log(data);

            $.each(titles, function(index, titulo){
                if (titulo.columntype != 'checkbox'){
                    titulosArr.push(titulo.text);
//                    if (!showHidden){
                        if (titulo.hidden){
                            titulosArr.pop();
                            data.forEach(function(obj){
                                delete obj[titulo.datafield];
                            });
                        }
//                    }
                }
            });
            data.forEach(function(obj){
                delete obj['uid'];
                
                delete obj['boundindex'];
                delete obj['uniqueid'];
                delete obj['visibleindex'];
                //"boundindex":0,"uniqueid":"2325-29-28-30-311726","visibleindex":0}
                for (var propiedad in obj){
                    if (obj.hasOwnProperty(propiedad)){
                        if (obj[propiedad] instanceof Date){
                            obj[propiedad] = moment(obj[propiedad]).format("DD/MM/YYYY HH:mm");
                        }
                    }
                };
            });
            var columnTitle = JSON.stringify(titulosArr);
            
//            var datos = data;
            var datos = JSON.stringify(data);
            $.redirect('/util/grid2Excel', {columnTitle: columnTitle, data: datos, title: title});
        });

////////////////////////////////////////////////////////////////////////////////
       
    });
</script>