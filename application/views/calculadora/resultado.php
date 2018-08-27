<input type="hidden" id="flujos" value=" <?php $flujos; ?>" >
<div id="ventanaResultado" >
    <div id="titulo">
        Resultado Bono
    </div>
    <div>
        <form id="form" style="margin-left: 10px; margin-top: 10px">
            <table>   
                <tr>
                    <td><?php echo 'TYR / YTM: ' . round($flujos['xirr'],2) . '%';  ?></td>
                </tr>
                
                <tr>
                    <td>Flujos</td>
                </tr>
                
                <tr>                   
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Fechas</td>
                    <td style="padding-right:10px; padding-bottom: 20px; vertical-align: middle">Flujo</td>
                    <td><?php 
                        for ($i = 0; $i <= count($flujos['flujos']) - 1; $i++) {
                            echo '</tr><td>'.$flujos['fechasExcel'][$i].'</td>';
                            echo '<td>'.$flujos['flujos'][$i].'</td></tr>'; 
                        } ?></td>
                </tr>
            </table>
        </form>
    </div> 
</div>
<script>
    $(function(){
        var theme = getTheme();
        var formOK = false;
        
        $("#ventanaResultado").jqxWindow({showCollapseButton: false, height: 500, width: 500, maxWidth: 1200, theme: theme,
        resizable: false, keyboardCloseKey: -1});

        //$('#nombre').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        //console.log($('#flujos').val());
        //print_r($flujos);
/*
        if ($("#flujos").val() > 0){
            console.log($('#flujos').val());
        } else {
            console.log($('#flujos').val());
        }
        */

    });
</script>