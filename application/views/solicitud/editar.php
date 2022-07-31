<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaSolicitud">
    <div id="titulo">
        Editar Solicitud de Solicitud
    </div>
    <div>
        <div id='tabPrincipal'>
            <ul>
                <li style="margin-left: 10px;">Datos Generales</li>
            </ul>
            <div>
                <form id="formDatosGenerales">
                    <input type="hidden" id="id" value="<?php echo $id; ?>">
                    <table style="margin: 10px; padding: 3px; border-spacing: 5px; border-collapse: separate ">
                        <tr>
                            <td>Fecha Presentacion:</td>
                            <td><div id="fechaPresentacion"></div></td>
                            <td>Fecha Estado:</td>
                            <td><div id="fechaEstado"></div></td>
                        </tr>
                        <tr>
                            <td>Estado<br>del cliente:</td>
                            <td><div id="estado_id"></div></td>
                            <td>Observaciones:</td>
                            <td><textarea id="observaciones"></textarea></td>
                        </tr>
                        <tr>
                            <td>Fecha Actualizacion</td>
                            <td><div id="fechaActualizacion"></div></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <table>
            <tr>
                <td><div id="cancelar" style="margin: 5px">Cancelar</div></td>
                <td><div id="grabarYSalir" style="margin: 5px">Grabar y Salir</div></td>
                <td><div id="grabar" style="margin: 5px">Grabar</div></td>
                <td><div id="generarSolicitud" style="margin: 5px">Generar Solicitud</div></td>
            </tr>
        </table>
    </div> 
</div>
<div id="notificacionGuardado">Datos guardados exitósamente</div>
<script>
    $(function(){
        var theme = getTheme();
        var formOK = false;
        var totalPerfiles;

        var srcColor = [
            {id: '1', valor: 'Amarillo'},
            {id: '2', valor: 'Rojo'},
            {id: '3', valor: 'Azul'},
            {id: '4', valor: 'Verde'}
        ];

        var srcComida = [
            {id: '1', valor: 'Arroz'},
            {id: '2', valor: 'Queso'},
            {id: '3', valor: 'Fideos'},
            {id: '4', valor: 'Pizza'}
        ];

        var srcMusica = [
            {id: '1', valor: 'Guns'},
            {id: '2', valor: 'RHCP'},
            {id: '3', valor: 'Foo Fighters'},
            {id: '4', valor: 'Rammstein'}
        ];

        var srcPelicula = [
            {id: '1', valor: 'Inception'},
            {id: '2', valor: 'Saw'},
            {id: '3', valor: '23'},
            {id: '4', valor: 'Gladiator'}
        ];

        var srcRelacionDependencia = [
            {id: '1', valor: 'Si'},
            {id: '2', valor: 'No'}
        ];

        var srcAsisteUniversidad = [
            {id: '1', valor: 'Si'},
            {id: '2', valor: 'No'}
        ];


        $("#ventanaSolicitud").jqxWindow({showCollapseButton: false, height: 500, width: '40%', maxWidth: '95%', maxHeight:600, theme: theme,
        resizable: false, keyboardCloseKey: -1});

        $("#ventanaSolicitud").ajaxloader();

        $("#tabPrincipal").jqxTabs({width: '98%', height: 415, position: 'top', theme: theme});

        
        $("#fechaPresentacion").jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy HH:mm:ss', theme: theme, disabled: true });

        $("#fechaEstado").jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy HH:mm:ss', theme: theme, disabled: true });        

        //$("#estado_id").jqxInput({width: 100, height: 25, theme: theme, disabled: false});

        //$("#observaciones").jqxInput({width: 200, height: 25, theme: theme, disabled: false});
        //Faltaba un $
        $("#fechaActualizacion").jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy HH:mm:ss', theme: theme, disabled: true });


        if ($("#id").val() == 0){
            $("#titulo").text('Nueva Solicitud de Suscripción');
            $("#ventanaSolicitud").ajaxloader('hide');
        } else {   

                
    

            $("#titulo").text('Editar Solicitud: * ' + $("#id").val() + ' *');
            datos = {
                id: $("#id").val()
            };
            $.post('/solicitud/getSolicitudPerfilId', datos, function(data){
                onLoad = true;
                
                $("#fechaPresentacion").jqxDateTimeInput('val', data.fechaPresentacion);
                $("#fechaEstado").jqxDateTimeInput('val', data.fechaEstado);
                $("#estado_id").val(data.estado_id);
//                setDropDown("#estado_id", data.estado_id);
                $("#observaciones").val(data.observaciones);
                //No estaba igual a los demás
                $("#fechaActualizacion").jqxDateTimeInput('val', data.fechaActualizacion);
                
                $.each(data.perfiles, function(index, perfil){
                    totalPerfiles = index;
                    var htmlPerfil = "\n\
                        <table width='50%' style=\"margin: 0px; padding: 0px; border-spacing: 0px; border-collapse: separate \">\n\
                        <form id='formPerfil_" + index.toString() + "'> \n\
                        <input type='hidden' id='id_" + index.toString() + "' value=''> \n\
                        <table style=\"margin: 10px; padding: 1px; border-spacing: 10px; border-collapse: separate \"> \n\
                        <tr> \n\
                            <td></td> \n\
                            <td><input type='hidden' id='idPerfil_" + index.toString() + "'></td> \n\
                            <td></td> \n\
                            <td></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Nombre:</td> \n\
                            <td><input type='text' id='nombre_" + index.toString() + "'></td> \n\
                            <td>Apellido:</td> \n\
                            <td><input type='text' id='apellido_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr> \n\  \n\
                            <td>Es Soltero/a:</td> \n\
                            <td><input type='text' id='esSoltero_" + index.toString() + "'></td> \n\
                            <td>Color:</td> \n\
                            <td><div id='color_" + index.toString() + "'></div></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Comida:</td> \n\
                            <td><div id='comida_" + index.toString() + "'></div></td> \n\
                            <td>Musica:</td> \n\
                            <td><div id='musica_" + index.toString() + "'></div></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Pelicula:</td> \n\
                            <td><div id='pelicula_" + index.toString() + "'></div></td> \n\
                            <td>Es Deportista:</td> \n\
                            <td><input type='text' id='esDeportista_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Es Vegetariano/a:</td> \n\
                            <td><input type='text' id='esVegetariano_" + index.toString() + "'></td> \n\
                            <td></td> \n\
                            <td></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Relacion Dependencia:</td> \n\
                            <td><div id='relacionDependencia_" + index.toString() + "'></div></td> \n\
                            <td class='esEmpleado_" + index.toString() + "'>Empleador:</td> \n\
                            <td class='esEmpleado_" + index.toString() + "'><input type='text' id='empleador_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Asiste a Universidad?</td> \n\
                            <td><div id='asisteUniversidad_" + index.toString() + "'></div></td> \n\
                            <td class='esUniversitario_" + index.toString() + "'>Que Universidad?:</td> \n\
                            <td class='esUniversitario_" + index.toString() + "'><input type='text' id='universitario_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Edad:</td> \n\
                            <td><input type='text' id='cantidadAnios_" + index.toString() + "'></td> \n\
                            <td></td> \n\
                            <td></td> \n\
                        </tr> \n\
                        \n\
                    </table> \n\
                    </form>";
                    
                    
                    $("#tabPrincipal").jqxTabs('addLast', perfil.nombre + ' ' + perfil.apellido, htmlPerfil);

                    //$(".esEmpleado_" + index.toString()).hide();
                    //$(".esUniversitario_" + index.toString()).hide();            

                    $("#nombre_" + index.toString()).jqxInput({height: 20, width: 100, theme: theme });
                    $("#apellido_" + index.toString()).jqxInput({height: 20, width: 100, theme: theme });
                    $("#esSoltero_" + index.toString()).jqxCheckBox({height: 20, width: 100, theme: theme });
                    $("#color_" + index.toString()).jqxDropDownList({ width: '100', height: '20', theme: theme, source: srcColor, displayMember: 'valor', valueMember: 'id'});
                    $("#comida_" + index.toString()).jqxDropDownList({ width: '100', height: '20', theme: theme, source: srcComida, displayMember: 'valor', valueMember: 'id'});
                    $("#musica_" + index.toString()).jqxDropDownList({ width: '100', height: '20', theme: theme, source: srcMusica, displayMember: 'valor', valueMember: 'id'});
                    $("#pelicula_" + index.toString()).jqxDropDownList({ width: '100', height: '20', theme: theme, source: srcPelicula, displayMember: 'valor', valueMember: 'id'});
                    $("#esDeportista_" + index.toString()).jqxCheckBox({height: 20, width: 100, theme: theme });
                    $("#esVegetariano_" + index.toString()).jqxCheckBox({height: 20, width: 100, theme: theme });  
                    $("#relacionDependencia_" + index.toString()).jqxDropDownList({ width: '100', height: '20', theme: theme, source: srcRelacionDependencia, displayMember: 'valor', valueMember: 'id'}); 
                    $("#empleador_" + index.toString()).jqxInput({height: 20, width: 100, theme: theme });
                    $("#asisteUniversidad_" + index.toString()).jqxDropDownList({ width: '100', height: '20', theme: theme, source: srcAsisteUniversidad, displayMember: 'valor', valueMember: 'id'}); 
                    $("#universitario_" + index.toString()).jqxInput({height: 20, width: 100, theme: theme });     
                    $("#cantidadAnios_" + index.toString()).jqxNumberInput({height: 20, width: 100, theme: theme });                                        
                    
                    $("#idPerfil_" + index.toString()).val(perfil.id);
                    $("#nombre_" + index.toString()).val(perfil.nombre);
                    $("#apellido_" + index.toString()).val(perfil.apellido);
                    $("#esSoltero_" + index.toString()).val(perfil.esSoltero);   
                    $("#color_" + index.toString()).val(perfil.color);    
                    $("#comida_" + index.toString()).val(perfil.comida);    
                    $("#musica_" + index.toString()).val(perfil.musica);    
                    $("#pelicula_" + index.toString()).val(perfil.pelicula);    
                    $("#esDeportista_" + index.toString()).val(perfil.esDeportista); 
                    $("#esVegetariano_" + index.toString()).val(perfil.esVegetariano);  
                    $("#relacionDependencia_" + index.toString()).val(perfil.relacionDependencia);
                    $("#empleador_" + index.toString()).val(perfil.empleador);
                    $("#asisteUniversidad_" + index.toString()).val(perfil.asisteUniversidad);
                    $("#universitario_" + index.toString()).val(perfil.universitario);   
                    $("#cantidadAnios_" + index.toString()).val(perfil.cantidadAnios);                                        

                    if ($("#relacionDependencia_" + index.toString()).val() == 1) { //Te faltaba un $
                        $(".esEmpleado_" + index.toString()).show();  //Se llama es empleado la clase (Es empleado_0)
                    } else { 
                        $(".esEmpleado_" + index.toString()).hide(); // Le agregué el _0 porque tiene que ser por cada perfil, 
                    }                                                // Sino se oculta o muestra en abas páginas, si tiene dos

                    if ($("#asisteUniversidad_" + index.toString()).val() == 1) {
                        $(".esUniversitario_" + index.toString()).show();
                    } else {
                        $(".esUniversitario_" + index.toString()).hide();
                    }


                    //jQuery.ajaxSetup({async:false});

                    $("#relacionDependencia_" + index.toString()).on('change', function (event) {

                        if ($("#relacionDependencia_" + index.toString()).val() == 1) { //Te faltaba un $
                            $(".esEmpleado_" + index.toString()).show();  //Se llama es empleado la clase (Es empleado_0)
                        } else { 
                            $(".esEmpleado_" + index.toString()).hide(); // Le agregué el _0 porque tiene que ser por cada perfil, 
                        }                                                // Sino se oculta o muestra en abas páginas, si tiene dos

                    });    

                    $("#asisteUniversidad_" + index.toString()).on('change', function (event) {

                        if ($("#asisteUniversidad_" + index.toString()).val() == 1) {
                            $(".esUniversitario_" + index.toString()).show();
                        } else {
                            $(".esUniversitario_" + index.toString()).hide();
                        }    

                        var args = event.args;
                        if(args){
                            console.log(args.item.originalItem);
                            console.log($("#relacionDependencia_"+index.toString()).val() )
                            console.log($("#asisteUniversidad_"+index.toString()).val() )
                                   
                        }                    
                        console.log("Cambia");
                    });

                    //jQuery.ajaxSetup({async:true});



                    $("#formPerfil_" + index.toString()).jqxValidator({rules:[



                        { input: '#nombre_' + index.toString(), message: 'El nombre no puede estar vacio!', rule: 'required' },
                        { input: '#apellido_' + index.toString(), message: 'El apellido no puede estar vacio!', rule: 'required' },

                        // Poné el fukin _
                        { input: '#comida_' + index.toString(), message: 'Seleccione comida!', action: 'keyup, blur',  rule: function(){
                            // El valor de un dropdown es distinto, porque te trae el valor de id. (Línea 62)
                            console.log("Imprimo id:");
                            console.log( $("#comida_" + index.toString()).val() );

                            //el get selected index te trae el indice del dropdown, -1 es cuando no elegiste ninguno
                            console.log("Imprimo el indice del dropdown seleccionado");
                            console.log( $("#comida_" + index.toString() ).jqxDropDownList('getSelectedIndex') );


                            return ($("#comida_" + index.toString() ).jqxDropDownList('getSelectedIndex') != -1);
                        }},

                        { input: '#cantidadAnios_' + index.toString(), message: 'Edad erronea!', action: 'keyup, blur',  rule: function(){
                            if ($("#cantidadAnios_" + index.toString()).val() > 10) {
                                return false;
                            } else {
                                return true;
                            }
                        }},

                        ],
                    theme:theme});

                    $("#formPerfil_" + index.toString()).on('validationSuccess', function (event) { formOK = true; });
                    $("#formPerfil_" + index.toString()).on('validationError', function (event) { formOK = false; });                        
                
                });
                                
                                
                totalPerfiles++;
                $('#tabPrincipal').jqxTabs('select', 0); 
                $("#ventanaSolicitud").ajaxloader('hide');
                onLoad = false;
            }
            , 'json');
        };

        $("#cancelar").jqxButton({theme: theme, width: '70px'});
        $("#cancelar").bind('click', function (){
            $.redirect('/solicitud');
        });
        
        $("#notificacionGuardado").jqxNotification({
            width: 100, position: "bottom-right", opacity: 0.9,
            autoOpen: false, animationOpenDelay: 800, autoClose: true, autoCloseDelay: 3000, template: "success"
        });
        
        $("#grabar").jqxButton({theme: theme, width: '70px'});
        $("#grabar").bind('click', function (){
            grabar(false);
        });
        
        $("#grabarYSalir").jqxButton({theme: theme, width: '100px'});
        $("#grabarYSalir").bind('click', function(){
            grabar(true);
        });
        

        function grabar(salir){
            formOK = false;
            $("#tabPrincipal").jqxTabs('select', 0);
            $("#formDatosGenerales").jqxValidator('validate');
            if (formOK){
                var i = 0;
                while (i<totalPerfiles && formOK){
                    formOK = false;
                    $("#tabPrincipal").jqxTabs('select', i+1);
                    $("#formPerfil_" + i.toString()).jqxValidator('validate');
                    i++;
                }
                if (formOK){
                    var fechaPresentacion = moment($("#fechaPresentacion").jqxDateTimeInput('val'), 'DD/MM/YYYY HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');                    
                    var fechaEstado = moment($("#fechaEstado").jqxDateTimeInput('val'), 'DD/MM/YYYY HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');
                    var fechaActualizacion = moment($("#fechaActualizacion").jqxDateTimeInput('val'), 'DD/MM/YYYY HH:mm:ss').format('YYYY-MM-DD HH:mm:ss' );                              

                    var solicitud = {
                        id: $("#id").val(),
                        fechaPresentacion: fechaPresentacion,
                        fechaEstado: fechaEstado,
                        fechaActualizacion: fechaActualizacion,
                        estado_id: $("#estado_id").val(),
                        observaciones: $("#observaciones").val()
                    };

                    var perfiles = Array();
                    for (var h=0; h<totalPerfiles; h++){

                        var perfil = {
                            idPerfil: $('#idPerfil_' + h.toString()).val(),
                            nombre: $('#nombre_' + h.toString()).val(),
                            apellido: $('#apellido_' + h.toString()).val(),
                            esSoltero: $('#esSoltero_' + h.toString()).val(),
                            color: $('#color_' + h.toString()).val(),
                            comida: $('#comida_' + h.toString()).val(),
                            musica: $('#musica_' + h.toString()).val(),
                            pelicula: $('#pelicula_' + h.toString()).val(),
                            esDeportista: $('#esDeportista_' + h.toString()).val(),
                            esVegetariano: $('#esVegetariano_' + h.toString()).val(),
                            relacionDependencia: $('#relacionDependencia_' + h.toString()).val(),
                            empleador: $('#empleador_' + h.toString()).val(),                              
                            asisteUniversidad: $('#asisteUniversidad_' + h.toString()).val(),
                            universitario: $('#universitario_' + h.toString()).val(),
                            cantidadAnios: $('#cantidadAnios_' + h.toString()).val(),                                                                                                                              
                        };
                        
                        perfiles.push(perfil);
                    }
                    solicitud.perfiles = perfiles;
                    $("#ventanaSolicitud").ajaxloader();
                    $.post('/solicitud/saveSolicitud', solicitud, function(data){
                        if (data.id > 0){
                            if (salir){
                                $.redirect('/solicitud');
                            } else {
                                if($("#estado").val() == '5' || $("#estado").val() =='7'){
                                    var perfiles = Array();
                                    for (var h=0; h<totalPerfiles; h++){
                                        $("#enviarSolicitudButton_" + h.toString()).jqxButton({theme: theme, width: '220px', disabled:false});
                                    }
                                }
                                $('#ventanaSolicitud').ajaxloader('hide');
                                $("#notificacionGuardado").jqxNotification("open");
                            }
                        } else {
                            new Messi('Hubo un error guardando el solicitud', {title: 'Error', 
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                            $('#ventanaSolicitud').ajaxloader('hide');
                        }
                    }, 'json');
                }
            }            
        }

        $('#generarSolicitud').jqxButton({ theme: theme, width: '160px' });
        $('#generarSolicitud').bind('click', function () {
            var data = {id: $("#id").val(),
                            envioPorMail: JSON.stringify('false')
                        };
            $.post('/solicitud/getFicha', data, function(datos){
                var datosPost = {datos: JSON.stringify(datos),
                                    envioPorMail: JSON.stringify('false')
                
        };
                $.redirect('/generador/fichaAlfy.php', datosPost, 'POST');
            }, 'json');
        });

        $('#formDatosGenerales').jqxValidator({rules:[
                
//            { input: '#responsable', message: 'Debe seleccionar el responsable de la suscripción', action: 'blur', rule: function(){
//                return ($("#responsable").jqxDropDownList('getSelectedIndex') !== -1);
//            }},
            { input: '#fechaPresentacion', message: 'La fecha de presentación no es válida!', rule: function(){
                var fechaPresentacion = moment($("#fechaPresentacion").jqxDateTimeInput('val','date'));
                return moment().diff(fechaPresentacion) >= 0;
            }},
//            { input: '#fechaEstado', message: 'La fecha de certificadoPymeVencimiento no es válida!', rule: function(){
//                    var fechaEstado = moment($("#fechaEstado").jqxDateTimeInput('val','date'));
//                    return (fechaEstado.isValid());
//                }},
        ], theme: theme});
        
        $('#formDatosGenerales').bind('validationSuccess', function (event) { 
            formOK = true; 
        });
        $('#formDatosGenerales').bind('validationError', function (event) { 
            formOK = false; 
        }); 





        /*
        $('#form').jqxValidator({ rules: [
                    { input: '#fechaPresentacion', message: 'Debe ingresar la fecha de la solicitud!',  rule: 'required' },

                    ], 
                    theme: theme
        });
        $('#form').bind('validationSuccess', function (event) { formOK = true; });
        $('#form').bind('validationError', function (event) { formOK = false; }); 
        $('#aceptarButton').jqxButton({ theme: theme, width: '65px' });
        $('#aceptarButton').bind('click', function () {
            $('#form').jqxValidator('validate');
            if (formOK){
                $('#ventanaSolicitud').ajaxloader();
                datos = {
                    id: $("#id").val(),
                    fechaPresentacion: $('#fechaPresentacion').val(),
                    fechaEstado: $('#fechaEstado').val(),
                    estado_id: $('#estado_id').val(),
                    observaciones: $('#observaciones').val(),
                    fechaActualizacion: $('#fechaActualizacion').val()
                }
                $.post('/solicitud/saveSolicitud', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/solicitud');
                    } else {
                        new Messi('Hubo un error guardando la solicitud', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                        $('#ventanaSolicitud').ajaxloader('hide');
                    }
                }, 'json');
            }
        }); 
        */


    });
</script>