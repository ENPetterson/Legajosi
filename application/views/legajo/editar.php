<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaLegajo">
    <div id="titulo">
        Editar Legajo
    </div>
    <div>
        <div id='tabPrincipal'>
            <ul>
                <li style="margin-left: 10px;">Datos Generales</li>
            </ul>
            <div>
                <form id="formDatosGenerales">
                    <input type="hidden" id="id" value="<?php echo $id; ?>">
                    <table style="margin: 3px; padding: 3px; border-spacing: 10px; border-collapse: separate ">
                        <tr>
                            <td>Nombre:</td>
                            <td><input type='text' id="nombre"></td>
                            <td>Apellido:</td>
                            <td><input type='text' id="apellido"></td>
                        </tr>
                        <tr>
                            <td>Fecha de Nacimiento:</td>
                            <td><div id="fechaNacimiento"></div></td>
                            <td>Tipo de Documento:</td>
                            <td><input type='text' id="tipoDocumento"></td>
                        </tr>
                        <tr>
                            <td>CUIL</td>
                            <td><div id="cuil"></div></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Nacionalidad:</td>
                            <td><input type='text' id="nacionalidad"></td>
                            <td>Estado Civil:</td>
                            <td><input type='text' id="estadoCivil"></td>
                        </tr>
                        <tr>
                            <td>Es Discapacitado:</td>
                            <td><input type='text' id="esDiscapacitado"></td>
                            <td>Email:</td>
                            <td><input type='text' id="email"></td>
                        </tr>
                        <tr>
                            <td>Sexo:</td>
                            <td><input type='text' id="sexo"></td>
                            <td>Cargo:</td>
                            <td><div id="cargo"></div></td>
                        </tr>
                        <tr>
                            <td>Fecha Ingreso:</td>
                            <td><div id="fechaIngreso"></div></td>
                            <td>Fecha Egreso:</td>
                            <td><div id="fechaEgreso"></div></td>
                        </tr>
                        <tr>
                            <td>Fecha Antiguedad:</td>
                            <td><div id="fechaAntiguedad"></div></td>
                            <td>Dias Vacaciones:</td>
                            <td><div id="diasVacaciones"></div></td>
                        </tr>
                        <tr>
                            <td>Sueldo Basico:</td>
                            <td><div id="sueldoBasico"></div></td>
                            <td>Observaciones:</td>
                            <td><textarea id="observaciones"></textarea></td>
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
                <td><div id="generarLegajo" style="margin: 5px">Generar Legajo</div></td>
            </tr>
        </table>
    </div> 
</div>
<div id="notificacionGuardado">Datos guardados exitósamente</div>
<script>
    $(function(){
        var theme = getTheme();
        var formOK = false;
        var totalExperienciaslaborales;

        $("#ventanaLegajo").jqxWindow({showCollapseButton: false, height: 500, width: '42%', maxWidth: '95%', maxHeight:700, theme: theme,
        resizable: false, keyboardCloseKey: -1});

        $("#ventanaLegajo").ajaxloader();

        $("#tabPrincipal").jqxTabs({width: '98%', height: 415, position: 'top', theme: theme});

        //Si es un varchar, un texto, vá con un input type='text' y un jqxInput
        //Si es un número vá con un div, y con un jqxNumberInput
        //Fecha vá div y .jqxDateTimeInput
        //Y no me acuerdo otro tipo
        //Si, esta bien, es que como te digo, eran un poco distintas las tablas
        //Ok, igual anotatelo, tenés que hacerte un manual o algo así
        //Dale

        $('#nombre').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#apellido').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#fechaNacimiento').jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy', theme: theme, disabled: true });

        $('#tipoDocumento').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#cuil').jqxNumberInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#nacionalidad').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#estadoCivil').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#esDiscapacitado').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#email').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#sexo').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#cargo').jqxNumberInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#fechaIngreso').jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy', theme: theme, disabled: true });    

        $('#fechaEgreso').jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy', theme: theme, disabled: true });    

        $('#fechaAntiguedad').jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy', theme: theme, disabled: true }); 

        $('#diasVacaciones').jqxNumberInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#sueldoBasico').jqxNumberInput({width: 200, height: 25, theme: theme, disabled: false});

        $('#observaciones').jqxInput({width: 200, height: 25, theme: theme, disabled: false});

        if ($("#id").val() == 0){
            $("#titulo").text('Nuevo Legajo');
            $("#ventanaLegajo").ajaxloader('hide');
        } else {   

                
    

            $("#titulo").text('Editar Legajo: * ' + $("#id").val() + ' *');
            datos = {
                id: $("#id").val()
            };
            $.post('/legajo/getLegajoExperiencialaboralId', datos, function(data){
                onLoad = true;
                
                $("#nombre").val(data.nombre);
                $("#apellido").val(data.apellido);
                $("#fechaNacimiento").jqxDateTimeInput('val', data.fechaNacimiento);
                $("#tipoDocumento").val(data.tipoDocumento);
                $("#cuil").val(data.cuil);
                $("#nacionalidad").val(data.nacionalidad);
                $("#estadoCivil").val(data.estadoCivil);
                $("#esDiscapacitado").val(data.esDiscapacitado);
                $("#email").val(data.email);
                $("#sexo").val(data.sexo);
                $("#cargo").val(data.cargo);
                $("#fechaIngreso").jqxDateTimeInput('val', data.fechaIngreso);
                $("#fechaEgreso").jqxDateTimeInput('val', data.fechaEgreso);
                $("#fechaAntiguedad").jqxDateTimeInput('val', data.fechaAntiguedad);
                $("#diasVacaciones").val(data.diasVacaciones);
                $("#sueldoBasico").val(data.sueldoBasico);
                $("#observaciones").val(data.observaciones);

                
                $.each(data.experienciaslaborales, function(index, experiencialaboral){
                    totalExperienciaslaborales = index;
                    var htmlExperiencialaboral = "\n\
                        <table width='50%' style=\"margin: 0px; padding: 0px; border-spacing: 0px; border-collapse: separate \">\n\
                        <form id='formExperiencialaboral_" + index.toString() + "'> \n\
                        <input type='hidden' id='id_" + index.toString() + "' value=''> \n\
                        <table style=\"margin: 10px; padding: 1px; border-spacing: 10px; border-collapse: separate \"> \n\
                        <tr> \n\
                            <td></td> \n\
                            <td><input type='hidden' id='idExperiencialaboral_" + index.toString() + "'></td> \n\
                            <td></td> \n\
                            <td></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Experiencia:</td> \n\
                            <td><input type='text' id='experiencia_" + index.toString() + "'></td> \n\
                            <td>Empresa:</td> \n\
                            <td><input type='text' id='empresa_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Fecha Inicio</td> \n\
                            <td><div id='fechaInicio_" + index.toString() + "'></div></td> \n\
                            <td>Fecha Salida:</td> \n\
                            <td><div id='fechaSalida_" + index.toString() + "'></div></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Monto Mensual</td> \n\
                            <td><div id='montoMensual_" + index.toString() + "'></div></td> \n\
                            <td></td> \n\
                            <td></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Dependencia:</td> \n\
                            <td><input type='text' id='dependencia_" + index.toString() + "'></td> \n\
                            <td>Funciones:</td> \n\
                            <td><input type='text' id='funciones_" + index.toString() + "'></td> \n\
                        </tr> \n\
                    </table> \n\
                    </form>";   
                        

                    $("#tabPrincipal").jqxTabs('addLast', experiencialaboral.experiencia + ' ' + experiencialaboral.empresa, htmlExperiencialaboral);

                    //$(".esEmpleado_" + index.toString()).hide();
                    //$(".esUniversitario_" + index.toString()).hide();            



                    $("#experiencia_" + index.toString()).jqxInput({height: 20, width: 100, theme: theme });
                    $("#empresa_" + index.toString()).jqxInput({height: 20, width: 100, theme: theme });
                    $("#fechaInicio_" + index.toString()).jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy', theme: theme, disabled: true }); 
                    $("#fechaSalida_" + index.toString()).jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy', theme: theme, disabled: true });
                    $("#montoMensual_" + index.toString()).jqxNumberInput({height: 20, width: 100, theme: theme });
                    $("#dependencia_" + index.toString()).jqxInput({height: 20, width: 100, theme: theme });
                    $("#funciones_" + index.toString()).jqxInput({height: 20, width: 100, theme: theme });

                    $("#idExperiencialaboral_" + index.toString()).val(experiencialaboral.id);

                    $("#experiencia_" + index.toString()).val(experiencialaboral.experiencia);
                    $("#empresa_" + index.toString()).val(experiencialaboral.empresa);
                    $("#fechaInicio_" + index.toString()).val(experiencialaboral.fechaInicio);   
                    $("#fechaSalida_" + index.toString()).val(experiencialaboral.fechaSalida);    
                    $("#montoMensual_" + index.toString()).val(experiencialaboral.montoMensual);    
                    $("#dependencia_" + index.toString()).val(experiencialaboral.dependencia);    
                    $("#funciones_" + index.toString()).val(experiencialaboral.funciones);    
                                      

                    /*if ($("#relacionDependencia_" + index.toString()).val() == 1) { //Te faltaba un $
                        $(".esEmpleado_" + index.toString()).show();  //Se llama es empleado la clase (Es empleado_0)
                    } else { 
                        $(".esEmpleado_" + index.toString()).hide(); // Le agregué el _0 porque tiene que ser por cada perfil, 
                    }                                                // Sino se oculta o muestra en abas páginas, si tiene dos

                    if ($("#asisteUniversidad_" + index.toString()).val() == 1) {
                        $(".esUniversitario_" + index.toString()).show();
                    } else {
                        $(".esUniversitario_" + index.toString()).hide();
                    }*/


                    //jQuery.ajaxSetup({async:false});

                    /*$("#relacionDependencia_" + index.toString()).on('change', function (event) {

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
                    });*/

                    //jQuery.ajaxSetup({async:true});



                    $("#formExperiencialaboral_" + index.toString()).jqxValidator({rules:[



                        { input: '#experiencia_' + index.toString(), message: 'La experiencia no puede estar vacia!', rule: 'required' },
                        { input: '#empresa_' + index.toString(), message: 'La empresa no puede estar vacia!', rule: 'required' },

                        // Poné el fukin _
                        /*{ input: '#comida_' + index.toString(), message: 'Seleccione comida!', action: 'keyup, blur',  rule: function(){
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
                        }},*/

                        ],
                    theme:theme});

                    $("#formExperiencialaboral_" + index.toString()).on('validationSuccess', function (event) { formOK = true; });
                    $("#formExperiencialaboral_" + index.toString()).on('validationError', function (event) { formOK = false; });                        
                
                });
                                
                                
                totalExperienciaslaborales++;
                $('#tabPrincipal').jqxTabs('select', 0); 
                $("#ventanaLegajo").ajaxloader('hide');
                onLoad = false;
            }
            , 'json');
        };

        $("#cancelar").jqxButton({theme: theme, width: '70px'});
        $("#cancelar").bind('click', function (){
            $.redirect('/legajo');
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
                while (i<totalExperienciaslaborales && formOK){
                    formOK = false;
                    $("#tabPrincipal").jqxTabs('select', i+1);
                    $("#formExperiencialaboral_" + i.toString()).jqxValidator('validate');
                    i++;
                }
                if (formOK){

                    var fechaNacimiento = moment($("#fechaNacimiento").jqxDateTimeInput('val'), 'DD/MM/YYYY').format('YYYY-MM-DD');   
                    var fechaIngreso = moment($("#fechaIngreso").jqxDateTimeInput('val'), 'DD/MM/YYYY').format('YYYY-MM-DD');                    
                    var fechaEgreso = moment($("#fechaEgreso").jqxDateTimeInput('val'), 'DD/MM/YYYY').format('YYYY-MM-DD');
                    var fechaAntiguedad = moment($("#fechaAntiguedad").jqxDateTimeInput('val'), 'DD/MM/YYYY').format('YYYY-MM-DD' );                              
                    var legajo = {
                        id: $("#id").val(),
                        nombre: $("#nombre").val(),
                        apellido: $("#apellido").val(),
                        fechaNacimiento: fechaNacimiento,
                        tipoDocumento: $("#tipoDocumento").val(),
                        cuil: $("#cuil").val(),
                        nacionalidad: $("#nacionalidad").val(),
                        estadoCivil: $("#estadoCivil").val(),
                        esDiscapacitado: $("#esDiscapacitado").val(),
                        email: $("#email").val(),
                        sexo: $("#sexo").val(),
                        cargo: $("#cargo").val(),
                        fechaIngreso: fechaIngreso,
                        fechaEgreso: fechaEgreso,
                        fechaAntiguedad: fechaAntiguedad,
                        diasVacaciones: $("#diasVacaciones").val(),
                        sueldoBasico: $("#sueldoBasico").val(),
                        observaciones: $("#observaciones").val()
                    };

                    var experienciaslaborales = Array();
                    for (var h=0; h<totalExperienciaslaborales; h++){

                        var experiencialaboral = {
                            idExperiencialaboral: $('#idExperiencialaboral_' + h.toString()).val(),
                            experiencia: $('#experiencia_' + h.toString()).val(),
                            empresa: $('#empresa_' + h.toString()).val(),
                            //legajo_id: $('#legajo_id_' + h.toString()).val(),
                            fechaInicio: $('#fechaInicio_' + h.toString()).val(),
                            fechaSalida: $('#fechaSalida_' + h.toString()).val(),
                            montoMensual: $('#montoMensual_' + h.toString()).val(),
                            dependencia: $('#dependencia_' + h.toString()).val(),
                            funciones: $('#funciones_' + h.toString()).val(),
                                                                                                                        
                        };
                        
                        experienciaslaborales.push(experiencialaboral);
                    }
                    legajo.experienciaslaborales = experienciaslaborales;
                    $("#ventanaLegajo").ajaxloader();
                    $.post('/legajo/saveLegajo', legajo, function(data){
                        if (data.id > 0){
                            //if (salir){
                            $.redirect('/legajo');
                            /*}ba d:false});
                                    }
                                }
                                $('#ventanaLegajo').ajaxloader('hide');
                                $("#notificacionGuardado").jqxNotification("open");
                            }
                            */
                        } else {
                            new Messi('Hubo un error guardando el legajo', {title: 'Error', 
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                            $('#ventanaLegajo').ajaxloader('hide');
                        }
                    }, 'json');
                }
            }            
        }

        $('#generarLegajo').jqxButton({ theme: theme, width: '160px' });
        $('#generarLegajo').bind('click', function () {
            var data = {id: $("#id").val(),
                            envioPorMail: JSON.stringify('false')
                        };
            $.post('/legajo/getFicha', data, function(datos){
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
            /*{ input: '#fechaPresentacion', message: 'La fecha de presentación no es válida!', rule: function(){
                var fechaPresentacion = moment($("#fechaPresentacion").jqxDateTimeInput('val','date'));
                return moment().diff(fechaPresentacion) >= 0;
            }},
//            { input: '#fechaEstado', message: 'La fecha de certificadoPymeVencimiento no es válida!', rule: function(){
//                    var fechaEstado = moment($("#fechaEstado").jqxDateTimeInput('val','date'));
//                    return (fechaEstado.isValid());
//                }},*/
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