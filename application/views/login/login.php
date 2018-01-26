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
                                    
                                    <button id="aceptar">Ingresar</button>
                                    
				</form>
			</div>
		</body>
	</html>

<script>
    
    $(document).ready(function() {
        $("body").data('theme', 'darkblue');
        
        var theme = getTheme();
        var formOK = false;
            
            
//        <select id="dom">
//                <option>Contact name</option>
//        </select>//      
            
//        $('#ventanaLogin').jqxWindow({       
//            height: 170, 
//            width: 270,
//            theme: theme, 
//            resizable: false, 
//            isModal: true, 
//            modalOpacity: 0.3,
//            autoOpen: true,
//            initContent: function () {
//                $('#aceptar').jqxButton({ theme: theme, width: '65px' });
//                $('#formLogin').jqxValidator({
//                    rules: [
//                        { input: '#nombreUsuario', message: 'Debe ingresar el nombre de usuario!', rule: 'required' },
//                        { input: '#clave', message: 'Debe ingresar la clave!',  rule: 'required' }
//                    ], theme: theme
//                });
//            }
//        });
        
        
        
        $('#form').jqxValidator({ rules: [
                    { input: '#nombreUsuario', message: 'Debe ingresar el nombre de usuario!', rule: 'required' },
                    { input: '#clave', message: 'Debe ingresar la clave!',  rule: 'required' }
                    
                    ], 
                    theme: theme
        });
        
        
//        $("#nombreUsuario").jqxInput({placeHolder: "Usuario", height: '20px', width: '170px', minLength: 1, theme: theme });
//        $("#clave").jqxPasswordInput({  width: '170px', height: '20px', theme: theme});


//        var sourceDominios = [<?php echo DOMINIOS;?>];
//        $("#dominio").jqxDropDownList({ 
//            source: sourceDominios, 
//            selectedIndex: 0, 
//            width: '170', 
//            height: '30px', 
//            theme: theme});
//        if ($("#dominioPrevio").val()){
//            $("#dominio").jqxDropDownList('selectItem', $("#dominioPrevio").val());
//        }
        
//        $('#form').bind('validationSuccess', function (event) { formOK = true; });
//        $('#form').bind('validationError', function (event) { formOK = false; }); 
//        
        $('#form').bind('validationSuccess', function (event) { formOK = true; });
        $('#form').bind('validationError', function (event) { formOK = false; }); 
        
        $('#aceptar').bind('click', function () {
            $('#form').jqxValidator('validate');
            if (formOK){
                console.log('OK');
                datos = {
                    nombreUsuario: $('#nombreUsuario').val(),
                    clave: $('#clave').val(),
                    dominio: $("#dominio").val()
                };
                $.post('usuario/validarUsuario', datos, function(data){
                    if (data.resultado == 'OK'){
                        console.log('OK');
                        $(location).attr('href','/calculadora');
//                        $.redirect('/calculadora/resultado');
                    } else {
                        new Messi(data.resultado, {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
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