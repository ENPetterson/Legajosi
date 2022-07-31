<script type="text/javascript">
    $(document).ready(function () {
            // prepare the data
        var theme = getTheme();
        var id = 0;
        var fecha;
        var tipo;


        
        $("#ventanaGrilla").jqxWindow({showCollapseButton: false, height: 1200, width: 2000, theme: theme,
        resizable: false, keyboardCloseKey: -1});

        var source = {
            datatype: "json",
            datafields: [
                { name: 'id'},
                { name: 'fecha'},
                { name: 'tipo'},
                { name: 'descripcion'},
                { name: 'observaciones'},
                { name: 'usuario'}
            ],
            id: 'id',
            url: '/grilla/orden/id-fecha-tipo-descripcion/fecha',
            //url: '/grilla/v_orden/id-fecha-tipo-descripcion-observaciones-usuario/nombre',//por qué nombre???
            //url: '/grilla/v_orden/id-fecha-tipo-descripcion-observaciones-usuario/fecha',//por qué nombre???

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
            width: 1000,
            height: 800,
            columns: [
                    { text: 'Id', datafield: 'id', width: 0, 'hidden': true },
                    //{ text: 'Usuario', datafield: 'fecha', width: 200 },// aca por qué nombre??? ningun cmpo se llama nombre NINGUNO
                    
                    { text: 'Fecha', datafield: 'fecha', width: 200 },
                    { text: 'Tipo', datafield: 'tipo', width: 200 },
                    { text: 'Descripcion', datafield: 'descripcion', width: 200 },
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
            $.redirect('/orden/editar', {'id': 0});
        });
        
        $("#editarButton").click(function(){
            $.redirect('/orden/editar', {'id': id});
        });
        
        $("#borrarButton").click(function(){
            new Messi('Desea borrar el orden ' + nombre + ' ?' , {title: 'Confirmar', modal: true,
                buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                    if (val == 's'){
                        datos = {
                            id: id
                        };
                        $.post('/orden/delOrden', datos, function(data){
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
    <div>Orden</div>
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
