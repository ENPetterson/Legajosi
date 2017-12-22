<script type="text/javascript">
    $(document).ready(function () {
            // prepare the data
        var theme = getTheme();
        var id = 0;
        var nombre;
        
        $("#ventanaGrilla").jqxWindow({showCollapseButton: false, height: 480, width: 880, theme: theme,
        resizable: false, keyboardCloseKey: -1});

        var source = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'nombre'},
                { name: 'emisor'},
                { name: 'tipobono'},
                { name: 'codigocaja'},
                { name: 'codigoisin'}
            ],
            id: 'id',
            url: '/grilla/v_bono/id-nombre-emisor-tipobono-codigocaja-codigoisin/nombre',
            async: false
        };
        var dataAdapter = new $.jqx.dataAdapter(source);

        // initialize jqxGrid
        $("#grilla").jqxGrid(
        {		
            source: dataAdapter,
            theme: theme,
            filterable: true,
            filtermode: 'excel',
            sortable: true,
            autoheight: false,
            pageable: false,
            virtualmode: false,
            columnsresize: true,
            width: 750,
            height: 400,
            columns: [
                    { text: 'Id', datafield: 'id', width: 0, 'hidden': true },
                    { text: 'Bono', datafield: 'nombre', width: 250 },
                    { text: 'Emisor', datafield: 'emisor', width: 250 },
                    { text: 'Tipo de Bono', datafield: 'tipobono', width: 250 },
                    { text: 'Código CAJA', datafield: 'codigocaja', width: 250 },
                    { text: 'Código ISIN', datafield: 'codigoisin', width: 250 }
            ]
        });
        $("#grilla").on("bindingcomplete", function (event){
            var localizationobj = getLocalization();
            $("#grilla").jqxGrid('localizestrings', localizationobj);
        });  
        
        $("#nuevoButton").jqxButton({ width: '80', theme: theme });
        $("#editarButton").jqxButton({ width: '80', theme: theme, disabled: true });
        $("#borrarButton").jqxButton({ width: '80', theme: theme, disabled: true });
        
        $("#nuevoButton").click(function(){
            $.redirect('/bono/editar', {'id': 0});
        });
        
        $("#editarButton").click(function(){
            $.redirect('/bono/editar', {'id': id});
        });
        
        $("#borrarButton").click(function(){
            new Messi('Desea borrar el bono ' + nombre + ' ?' , {title: 'Confirmar', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            id: id
                        };
                        $.post('/bono/delBono', datos, function(data){
                            new Messi(data.resultado, {title: 'Mensaje', modal: true,
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}]});
                            $('#grilla').jqxGrid('updatebounddata');
                            $('#editarButton').jqxButton({disabled: true });
                            $('#borrarButton').jqxButton({disabled: true });
                            $('#grilla').jqxGrid({ selectedrowindex: -1}); 
                        }
                        , 'json');
                    } 
                }
            });
        });
        
        
        $('#grilla').on('rowselect', function (event) {
            var args = event.args; 
            var row = args.rowindex;
            if (row >= 0){
                $('#editarButton').jqxButton({disabled: false });
                $('#borrarButton').jqxButton({disabled: false });
                id = args.row.id;
                nombre = args.row.nombre;
            }
        });
        
        
        
    });
</script>
<div id="ventanaGrilla">
    <div>Bonos</div>
    <div>
        <div id="grilla" style="margin-bottom: 0.9em"></div>
        <table boder="0" cellpadding="2" cellspacing="2">
            <tr>
                <td><input type="button" value="Nuevo" id="nuevoButton"></td>
                <td><input type="button" value="Editar" id="editarButton"></td>
                <td><input type="button" value="Borrar" id="borrarButton"></td>
            </tr>
        </table>        
    </div>
</div>
