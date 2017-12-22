<div class="container">
    <div id='ventanaGrilla'>
        <div>Formularios Presentados</div>
        <div>
            <div id="grillaRetail" style="margin-bottom: 0.9em"></div>
            <table boder="0" cellpadding="2" cellspacing="2">
                <tr>
                    <td><input type="button" value="Ver Ficha" id="verFicha"></td>
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
        
        $("#ventanaGrilla").jqxWindow({showCollapseButton: false, maxHeight:800, height: 480, maxWidth: 3000, width: 1210, theme: theme, resizable: false, keyboardCloseKey: -1});
        
        var url = "/grilla/v_retail/id-fechaPresentacion-numComitente-numeroDocumento-apellido-nombre-estado_id-estado-observaciones-tramiteAlta/id%20desc";
        
        var source = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'fechaPresentacion', type: 'date', format: 'yyyy-MM-dd HH:mm:ss'},
                { name: 'numComitente'},
                { name: 'numeroDocumento'},
                { name: 'apellido'},
                { name: 'nombre'},
                { name: 'estado_id'},
                { name: 'estado'},
                { name: 'observaciones'},
                { name: 'tramiteAlta'}
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
        
        // initialize jqxGrid
        $("#grillaRetail").jqxGrid(
        {		
                source: dataadapter,
                theme: theme,
                filterable: true,
                sortable: true,
                autoheight: false,
                pageable: false,
                virtualmode: false,
                columnsresize: true,
                width: 1200,
                height: 400,
                selectionmode: 'singlerow',
                autosavestate: true,
                autoloadstate: true,
                rendergridrows: function(obj)
                {
                        return obj.data;    
                },
                columns: [
                    { text: 'Solicitud', datafield: 'id', width: 70},
                    { text: 'Fecha Presentación', datafield: 'fechaPresentacion', width: 150, cellsformat: 'dd/MM/yyyy HH:mm:ss'},
                    { text: 'Comitente', datafield: 'numComitente', width: 60},
                    { text: 'DNI', datafield: 'numeroDocumento', width: 90},
                    { text: 'Apellido', datafield: 'apellido', width: 200},
                    { text: 'Nombre', datafield: 'nombre', width: 200},
                    { text: 'Id Estado', datafield: 'estado_id', width: 0, hidden: true},
                    { text: 'Estado', datafield: 'estado', width: 100},
                    { text: 'Observaciones', datafield: 'observaciones', width: 200},
                    { text: 'Tramite alta', datafield: 'tramiteAlta', width: 100},
                    { text: 'Retail Id', datafield: 'retail_id', width: 100, hidden: true}
                ]
        });
        $("#grillaRetail").on("bindingcomplete", function (event){
            var localizationobj = getLocalization();
            $("#grillaRetail").jqxGrid('localizestrings', localizationobj);
        });  
        
        $("#verFicha").jqxButton({ width: '80', theme: theme, disabled: true });
        
        $("#verFicha").click(function(){
            var data = {id: id};
            $.post('/formulario/getFicha', data, function(datos){
                var datosPost = {datos: JSON.stringify(datos)};
                $.redirect('/generador/ficha.php', datosPost, 'POST');
            }, 'json');            
        });
        
        
        $("#excelButton").jqxButton({ width: '80', theme: theme, disabled: false });
        
        $("#excelButton").click(function(){
            grid2excel('#grillaRetail', 'FormulariosPresentados', false);
        });
        
        $('#grillaRetail').on('rowselect', function (event) {
            var args = event.args; 
            var row = args.rowindex;
            if (row >= 0){
                if (args.row.retail_id > 0){
                    $('#verFicha').jqxButton({disabled: true });
                } else {
                    $('#verFicha').jqxButton({disabled: false });
                }
                id = args.row.id;
            }
        });
        
    });
</script>
