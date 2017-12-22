<div id="documentacion"></div>
<input type="button" value="text" id="test">
<script>
    $(document).ready(function () {
        var theme = getTheme();
        
        var docsOriginales;
        var docsCopias;
        
        var srcDocumentacion = {
            datatype: "json",
            datafields: [
                { name: 'id', type: 'int'},
                { name: 'nombre', type: 'string' },
                { name: 'original', type: 'int'}
            ],
            id: 'id',
            data: {tipoPersona: 'J'},
            type: 'POST',
            url: "/documentacion/getAll"
        };
        
        var checkboxRenderer = function (row, column, value) {
                var fila = $("#documentacion").jqxGrid('getrowdata', row);
                if (column == 'copia'){
                    valor = '<div style="text-align: center; margin-top: 5px;"><input type="checkbox" id="chk_copia_' + fila.id + '"/></div>';
                } else {
                    var valor = '<div style="text-align: center; margin-top: 5px;"></div>';
                    if (fila.original == 1){
                        valor = '<div style="text-align: center; margin-top: 5px;"><input type="checkbox" id="chk_original_' + fila.id + '"/></div>';
                    }                    
                }
                return valor;
            }


        
        var DADocumentacion = new $.jqx.dataAdapter(srcDocumentacion);
        $("#documentacion").jqxGrid(
        {
            width: 850,
            source: DADocumentacion,
            columnsresize: true,
            editable: false,
            columns: [
              { text: 'id', datafield: 'id', width: 0, hidden: true},
              { text: 'Documentacion', datafield: 'nombre', width: 250},
              { text: 'ori', datafield:'original', hidden: true},
              { text: 'Original', datafield: 'origCliente', cellsrenderer: checkboxRenderer , width: 50 },
              { text: 'Copia', datafield: 'copia', cellsrenderer: checkboxRenderer, width: 50 }
            ],
            theme: theme
        });
        
        $("#test").jqxButton({theme: theme});
        $("#test").bind('click', function(){
            obtenerDocumentacion();

        });
        
        function obtenerDocumentacion(){
            docsOriginales = [];
            docsCopias = [];
            $.post('/documentacion/getAll', {tipoPersona: 'J'}, function(documentaciones){
                $.each(documentaciones, function(index, documentacion){
                    if($("#chk_copia_" + documentacion.id.toString()).is(":checked")){
                        docsCopias.push(documentacion.id);
                    }
                    if (documentacion.original == 1){
                        if($("#chk_original_" + documentacion.id.toString()).is(":checked")){
                            docsOriginales.push(documentacion.id);
                        }
                    }
                });
            }, 'json');
        }
    });    
    
</script>
