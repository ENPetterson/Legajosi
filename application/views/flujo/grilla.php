<div id="ventanaResumen">
    <div id="titulo">
        Flujos cargados
    </div>
    <div id="botonera">
        <table boder="0" cellpadding="2" cellspacing="2">
            <tr>
                
                <td style = "padding-bottom: 10px"><div  id="cmbBono"></div></td>
                <br>
                <td style = "padding-bottom: 10px"><div id="cmbFecha" ></div></td>
                <br>
                <!--<td><div id="cmbInstitucion" ></div></td>-->
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



        $("#ventanaResumen").jqxWindow({showCollapseButton: false, maxWidth: 2000, height: 550, width: 1500, theme: theme,
        resizable: false, keyboardCloseKey: -1});

////Dropdown Bono
////////////////////////////////////////////////////////////////////////////////
        var srcBono =
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
        var DABono = new $.jqx.dataAdapter(srcBono);


        $("#cmbBono").jqxDropDownList({ selectedIndex: -1, source: DABono, displayMember: "nombre", 
        valueMember: "id", width: 150, height: 25, theme: theme, placeHolder: "Elija el Bono:", disabled: false });
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
                    data: {
                        bono: bono
                    },
                    id: 'id',
                    url: '/flujo/getFechaActualizacion',
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
                { name: 'fechapagos'},
                { name: 'amortizacion', type: 'number'},
                { name: 'vr', type: 'number'},
                { name: 'interes', type: 'number'}
            ],
            cache: false,
            url: '/flujo/grillaFlujo',
            
            data: {
                bono: bono,
                fecha: fecha
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
                        { text: 'Id', datafield: 'id', width: 80, cellsalign: 'right', cellsformat: 'd', aggregates: ['count']  },
                        { text: 'Bono', datafield: 'bono', width: 150 },
                        { text: 'fechapagos', datafield: 'fechapagos', width: 150},
                        { text: 'amortizacion', datafield: 'amortizacion', width: 150, cellsformat: 'd'},
                        { text: 'vr', datafield: 'vr', width: 150, cellsformat: 'd'},
                        { text: 'interes', datafield: 'interes', width: 150, cellsformat: 'd'}
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
//            console.log($("#cmbFecha").jqxDropDownList());
            
            srcFecha.data = {
                bono: event.args.item.label
            };     
            DAFecha.dataBind();

            source.data = {
                bono: event.args.item.label,
//                fecha: $("#cmbFecha").jqxDropDownList('getSelectedItem').value
            };      
            dataadapter.dataBind();
        });
        
//Cierre
        $('#cmbFecha').on('change', function (event){
//            if (args) {
//                console.log(event.args.item);
             console.log($("#cmbFecha").jqxDropDownList());
            
                source.data = {
                    fecha: event.args.item.label
                };     
//                dataadapter.dataBind();
                
                $("#grilla").jqxGrid('updatebounddata');
//            }
            
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
            var datos = JSON.stringify(data);
            $.redirect('/util/grid2Excel', {columnTitle: columnTitle ,data: datos, title: title});
        });

////////////////////////////////////////////////////////////////////////////////
       
    });
</script>