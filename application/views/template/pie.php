<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
        </div>
	<!--//banner-->
	<div class="footer">
		<div class="container">
			<p>Copyright © 2015 Immovable. All rights reserved | Design by <a href="http://w3layouts.com/"> W3layouts</a></p>
		</div>
	</div>
        <script src="/js/bootstrap/bootstrap.js"> </script>
</body>
</html>
<script>
    $(function(){
        var metodo = window.location.pathname.slice(1);
        $.post('/permiso/getPermisosVista', {vista: metodo}, function(datos){
            $.each(datos, function(index, elemento){
               if (elemento.tipo == 'H'){
                   $(elemento.elemento).hide();
               } 
               if (elemento.tipo == 'D'){
                   switch ($(elemento.elemento).attr('role')){
                       case "combobox":
                           $(elemento.elemento).jqxDropDownList({disabled: true });
                           break;
                       case "button":
                           $(elemento.elemento).jqxButton({disabled: true });
                           break;
                       case "textbox":
                           if ($(elemento.elemento).attr('class').indexOf('jqx-datetimeinput') > -1){
                               $(elemento.elemento).jqxDateTimeInput({disabled: true});
                           } else {
                               $(elemento.elemento).jqxInput({disabled: true });
                           }
                           break;
                       case "checkbox":
                           $(elemento.elemento).jqxCheckBox({disabled: true});
                           break;
                   }
               }
            });
            if (typeof seguridad === "function") { 
                seguridad();
            }
            
        }, 'json');
    });
</script>
</body>
