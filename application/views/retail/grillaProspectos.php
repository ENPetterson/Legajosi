<div class="container">
    <div id='ventanaGrilla'>
        <div>Formularios Presentados</div>
        <div>
            <div id="grillaProspectos" style="margin-bottom: 0.9em"></div>
            <table boder="0" cellpadding="2" cellspacing="2">
                <tr>
                    <td><input type="button" value="Excel" id="excelButton"></td>                    
                </tr>
            </table>        
        </div>
    </div>
    
</div>
<script type="text/javascript">
    $(document).ready(function () {
            // prepare the data
        var theme = getTheme();
        var id = 0;
        
        $("#ventanaGrilla").jqxWindow({showCollapseButton: false, maxHeight:800, height: 480, maxWidth: 2000, width: 860, theme: theme, resizable: false, keyboardCloseKey: -1});
        
        var url = "/retail/grillaProspectos";
        
        var source = {
            datatype: "json",
            datafields: [
                { name: 'id' , type:'integer'},
                { name: 'apellido'},
                { name: 'nombre'},
                { name: 'email'},
                { name: 'fechahora', type: 'date', format: 'yyyy-MM-dd HH:mm:ss'}
            ],
            //id: "id",
            url: url,
            async: false,
            beforeprocessing: function(data)
            {		
                    if (data != null)
                    {
                            source.totalrecords = data[0].TotalRows;					
                    }
            }
        };

        var dataadapter = new $.jqx.dataAdapter(source, {
                loadError: function(xhr, status, error)
                {
                        alert(xhr.responseText);
                }
            }
        );
        var initialState;
        
        // initialize jqxGrid
        $("#grillaProspectos").jqxGrid(
        {		
                source: dataadapter,
                theme: theme,
                filterable: true,
                sortable: true,
                autoheight: false,
                pageable: false,
                virtualmode: false,
                columnsresize: true,
                width:  850,
                height: 400,
                selectionmode: 'singlerow',
                autosavestate: true,
                autoloadstate: true,
                rendergridrows: function(obj)
                {
                        return obj.data;    
                },
                rendered: function () {
                    initialState = $("#grillaProspectos").jqxGrid('getstate');
                },
                columns: [
                    { text: 'Solicitud', datafield: 'id', width: 70},
                    { text: 'Apellido', datafield: 'apellido', width: 200},
                    { text: 'Nombre', datafield: 'nombre', width: 200},
                    { text: 'E-mail', datafield: 'email', width: 200},
                    { text: 'Fecha y Hora', datafield: 'fechahora', width: 150, cellsformat: 'dd/MM/yyyy HH:mm:ss'}
                ]
        });
        $("#grillaProspectos").on("bindingcomplete", function (event){
            var localizationobj = getLocalization();
            $("#grillaProspectos").jqxGrid('localizestrings', localizationobj);
        });  
        $("#grillaProspectos").jqxGrid('loadstate', initialState);
        $("#excelButton").jqxButton({ width: '80', theme: theme, disabled: false });
        
        $("#excelButton").click(function(){
            grid2excel('#grillaRetail', 'FormulariosPresentados', false);
        });
        
    });
</script>
