<input type="hidden" value="<?php $dominio;?>" id="dominioPrevio"/>
	<!--banner-->
	<div class="bannerLogin">
		<!--header-->
		<div class="headder">		
				<nav class="navbar navbar-default">
				</nav>	
                                <div class="logo">
						<a class="navbar-brand" href="index.html"><img src="/images/logo.png" /></a>
				</div>
				<div class="clearfix"> </div>			
		</div>	
		<!--//header-->
	<html>
		<head>
			<!--<title>Sistema de Órdenes | Allaria Ledesma & Cia</title>-->
			<!--<meta charset="utf-8">-->
<!--			<link rel="stylesheet" type="text/css" href="styleLogin.css">-->
			<!--<link href="https://fonts.googleapis.com/css?family=Quicksand|Slabo+27px" rel="stylesheet">-->
		</head>
		<body>
                    <div id="login">
                        <div>
                            <header>Calculadora de Bonos</header>
                            <form id="form">
                                <input type="text" id="nombreUsuario" placeholder="usuario">
                                <input type="password" id="clave" placeholder="clave">
                                <select id="dominio">
                                    <?php    
                                    $dominios = DOMINIOS; 
                                    $dominios = str_replace('"', "", $dominios);
                                    $arrayDominios = explode(",", $dominios);
                                    foreach($arrayDominios as $dominio){ ?>
                                        <option value="<?= $dominio; ?>"><?php echo $dominio; ?></option>
                                    <?php } ?>
                                </select>
                            </form>

                        </div>   

                        <div>
                            <button id="aceptar">Ingresar</button>
                        </div>

                    </div>
		</body>
	</html>

<script>
    
//    $(document).ready(function() {
$(function(){
        $("body").data('theme', 'darkblue');
        
        var theme = getTheme();
        var formOK = false;

        $('#form').jqxValidator({ rules: [
                    { input: '#nombreUsuario', message: 'Debe ingresar el nombre de usuario!', rule: 'required' },
                    { input: '#clave', message: 'Debe ingresar la clave!',  rule: 'required' }
                    ], 
                    theme: theme
        });

        $('#form').bind('validationSuccess', function (event) { formOK = true; });
        $('#form').bind('validationError', function (event) { formOK = false; }); 
        
        $('#aceptar').bind('click', function () {
            $('#form').jqxValidator('validate');
            if (formOK){
                $('#login').ajaxloader();
                datos = {
                    nombreUsuario: $('#nombreUsuario').val(),
                    clave: $('#clave').val(),
                    dominio: $("#dominio").val()
                };
                $.post('usuario/validarUsuario', datos, function(data){
                    if (data.resultado == 'OK'){
                        $(location).attr('href','/calculadora');
                    } else {
                        new Messi(data.resultado, {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                        $('#login').ajaxloader('hide');
                    }
                }, 'json');
            }
        });        

        $('input').keypress(function (e) {
            if(e.which === 13) {
                $('#aceptar').click();
            }
        });
        
    });
    
    $(window).load(function() {
		// Animate loader off screen
		$(".se-pre-con").fadeOut("slow");;
    });
    
</script>    