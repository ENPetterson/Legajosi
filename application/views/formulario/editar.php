<script>
    $(function(){
        
        var theme = getTheme();
        var formOK = false;
        var totalTitulares;
        
        var onLoad = false;
        
        $("#ventanaFormulario").jqxWindow({showCollapseButton: false, height: 950, width: 840, maxWidth: 1000, maxHeight:2000, theme: theme,
        resizable: false, keyboardCloseKey: -1});
    
        $("#ventanaFormulario").ajaxloader();
        
        $("#tabPrincipal").jqxTabs({width: '100%', height: 870, position: 'top', theme: theme});
        
        var srcResponsables = {
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'responsable'}
            ],
            id: 'id',
            url: '/formulario/getResponsables'
        };
        var DAResponsables = new $.jqx.dataAdapter(srcResponsables);
        $("#responsable").jqxDropDownList({ width: '200', height: '20', theme: theme, source: DAResponsables, displayMember: 'responsable', valueMember: 'id'});
        
        $("#fechaPresentacion").jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy HH:mm:ss', theme: theme, disabled: true });
        var srcEstados = {
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'descripcion'}
            ],
            id: 'id',
            url: '/estado/getAll'
        };
        var DAEstados = new $.jqx.dataAdapter(srcEstados);        
        $("#estado").jqxDropDownList({ width: '200', height: '20', theme: theme, source: DAEstados, displayMember: 'descripcion', valueMember: 'id'});
        
        $("#estado").on('change', function(event){
            var args = event.args;
            if (args){
                var estado_id = args.item.value;
                $.post('/estado/get', {id: estado_id}, function(estado){
                    if (estado.esAlta == 1){
                        $('.esAlta').show();
                    } else {
                        $('.esAlta').hide();
                    }
                }, 'json');
            }
        });
        
        var srcTramiteAlta = {
            datatype: 'json',
            datafields: [
                {name: 'id'},
                {name: 'descripcion'}
            ],
            id: 'id',
            url: '/tramiteAlta/getAll'
        };
        
        var DATramiteAlta = new $.jqx.dataAdapter(srcTramiteAlta);
        
        $("#tramiteAlta").jqxDropDownList({width: '200', height: '20', theme: theme, source: DATramiteAlta, displayMember: 'descripcion', valueMember: 'id'});
        
        
        $("#fechaEstado").jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy HH:mm:ss', theme: theme, disabled: true });
        
        $('#observaciones').jqxTextArea({ height: 90, width: 200, theme: theme, disabled: false});
        
        /*
        var srcActuaPor = {
            datatype: 'json',
            datafields: [
                {name: 'clave'},
                {name: 'descripcion'}
            ],
            id: 'clave',
            url: '/actuaPor/getAll'
        };
        var DAActuaPor = new $.jqx.dataAdapter(srcActuaPor);
        $("#actuaPor").jqxDropDownList({ width: '200', height: '20', theme: theme, source: DAActuaPor, displayMember: 'descripcion', valueMember: 'clave'});
        $("#esBeneficiarioFinal").jqxCheckBox({ width: 200, height: 20, theme: theme });
        $("#beneficiarioFinal").jqxInput({height: 20, width: 200, theme: theme });
        */
        var srcComoNosConocio = {
            datatype: 'json',
            datafields: [
                {name: 'clave'},
                {name: 'descripcion'}
            ],
            id: 'clave',
            url: '/comoNosConocio/getAll'
        };
        var DAComoNosConocio = new $.jqx.dataAdapter(srcComoNosConocio);
        $("#comoNosConocio").jqxDropDownList({ width: '200', height: '20', theme: theme, source: DAComoNosConocio, displayMember: 'descripcion', valueMember: 'clave'});
        $("#contacto").jqxInput({height: 20, width: 200, theme: theme });
        $('#comentarios').jqxTextArea({ height: 90, width: 200, theme: theme});
        $("#numComitente").jqxNumberInput({ width: '110px', height: '25px', decimalDigits: 0, digits: 9, groupSeparator: ' ', max: 999999999, theme: theme});
        $(".esAlta").hide();
        var srcToleranciaRiesgo = [
            {id: 'B', valor: 'Riesgo Bajo'},
            {id: 'M', valor: 'Riesgo Medio'},
            {id: 'A', valor: 'Riesgo Alto'}
        ];
        $("#toleranciaRiesgo").jqxDropDownList({ width: '200', height: '20', theme: theme, source: srcToleranciaRiesgo, displayMember: 'valor', valueMember: 'id'});
        var srcPerfilCuenta = [
            {id: 'men250K', valor: 'Menor a 250.000'},
            {id: 'may250K', valor: 'Mayor a 250.000'}
        ];
        $("#perfilCuenta").jqxDropDownList({ width: '200', height: '20', theme: theme, source: srcPerfilCuenta, displayMember: 'valor', valueMember: 'id'});

        var srcAsociarCuenta = [
            {id: 'S', valor: 'Si'},
            {id: 'N', valor: 'No'}
        ];
        $("#asociarCuenta").jqxDropDownList({ width: '200', height: '20', theme: theme, source: srcAsociarCuenta, displayMember: 'valor', valueMember: 'id'}); 
        
        $("#asociarCuenta").on('change', function(event){
            var value = event.args.item.value;
            if (value === 'S'){
                $(".banco").show();
            } else {
                $(".banco").hide();
            }
        });
                    
        $("#banco").jqxInput({ width: 200, height: 20, theme: theme});
        
        var srcTipoCuentaBanco = [
            {id: 'CC', valor: 'Cuenta Corriente'},
            {id: 'CA', valor: 'Caja de Ahorros'}
        ];
        $("#tipoCuentaBanco").jqxDropDownList({ width: '200', height: '20', theme: theme, source: srcTipoCuentaBanco, displayMember: 'valor', valueMember: 'id'}); 
        
        $("#numeroCuenta").jqxInput({height: 20, width: 200, theme: theme });
        
        var srcMoneda = [
            {id: 'P', valor: 'Pesos'},
            {id: 'D', valor: 'Dólares'}
        ];
        $("#moneda").jqxDropDownList({ width: '200', height: '20', theme: theme, source: srcMoneda, displayMember: 'valor', valueMember: 'id', disabled: true}); 
        
        $("#titular").jqxInput({height: 20, width: 200, theme: theme });
        $("#cbu").jqxMaskedInput({ width: '200', height: '20', mask: '######## ##############', theme: theme});
        $("#cuitCuenta").jqxMaskedInput({ width: '200', height: '20', mask: '##-########-#', theme: theme});
        

        var srcOficiales = {
            datatype: "json",
            datafields: [
                { name: 'oficial' }
            ],
            url: '/formulario/getOficiales'
        };
        
        var DAOficiales = new $.jqx.dataAdapter(srcOficiales);
        
        $("#oficial").jqxInput({height: 20, width: 200, theme: theme, source: DAOficiales, displayMember: 'oficial', valueMember: 'oficial' });
        
        var srcAdministradores = {
            datatype: "json",
            datafields: [
                { name: 'administrador' }
            ],
            url: '/formulario/getAdministradores'
        };
        
        var DAAdministradores = new $.jqx.dataAdapter(srcAdministradores);
        
        $("#administrador").jqxInput({height: 20, width: 200, theme: theme, source: DAAdministradores, displayMember: 'administrador', valueMember: 'administrador' });
        
        var srcTercerosNoIntermediarios = {
            datatype: "json",
            datafields: [
                { name: 'terceroNoIntermediario' }
            ],
            url: '/formulario/getTercerosNoIntermediarios'
        };
        
        var DATercerosNoIntermediarios = new $.jqx.dataAdapter(srcTercerosNoIntermediarios);
        
        $("#terceroNoIntermediario").jqxInput({height: 20, width: 200, theme: theme, source: DATercerosNoIntermediarios, displayMember: 'terceroNoIntermediario', valueMember: 'terceroNoIntermediario' });
        
        $("#terceroNoIntermediario").on('change', function(){
            if (!onLoad){
                var terceroNoIntermediario = null;
                if ($("#terceroNoIntermediario").val()){
                    if (typeof $("#terceroNoIntermediario").val() === 'object'){
                        terceroNoIntermediario = $("#terceroNoIntermediario").val().value;
                    } else {
                        terceroNoIntermediario = $("#terceroNoIntermediario").val();
                    }
                }
                $.post('/formulario/getDatosTercero', {terceroNoIntermediario: $('#terceroNoIntermediario').val().value}, function(datos){
                    if (datos){
                        $("#dniTerceroNoIntermediario").val(datos.dniTerceroNoIntermediario);
                        $("#emailTerceroNoInscripto").val(datos.emailTerceroNoInscripto);
                        $("#numeroProductor").val(datos.numeroProductor);
                    }
                }, 'json');
            }
        });
        
        $("#dniTerceroNoIntermediario").jqxNumberInput({ width: '200', height: '20', decimalDigits: 0, digits: 8, groupSeparator: '.', max: 99999999, theme: theme});
        $("#emailTerceroNoInscripto").jqxInput({height: 20, width: 200, theme: theme });
        $("#numeroProductor").jqxNumberInput({ width: 200, height: 20, decimalDigits: 0, digits: 6, groupSeparator: '', max: 999999, theme: theme});
        
        $("#adjuntos").jqxListBox({ width: 440, height: 50, theme: theme});
        $("#descargarAdjunto").jqxButton({width: 90, height: 50, theme: theme});
        
        
        $("#esBeneficiarioFinal").on('change', function(event){
            var checked = event.args.checked;
            if (checked){
                $("#beneficiarioFinal").hide();
            } else {
                $("#beneficiarioFinal").show();
            }
        });
        
        $("#comoNosConocio").on('change', function (event){
            var args = event.args;
            if (args){
                var item = args.item;
                if (item.value == 'P'){
                    $(".contacto").show();
                } else {
                    $(".contacto").hide();
                }
            }
        });
        
        
        var srcTipoDocumento = [
            {id: 'dni', valor: 'Documento Nacional de Identidad'},
            {id: 'le', valor: 'Libreta de Enrolamiento'},
            {id: 'lc', valor: 'Libreta Cívica'},
            {id: 'pas', valor: 'Pasaporte'}
        ];
        var srcPais = {
            datatype: 'json',
            datafields: [
                {name: 'codigo'},
                {name: 'nombre'}
            ],
            //id: 'codigo',
            async: false,
            url: '/pais/getAll'
        };
        
        var DAPais = new $.jqx.dataAdapter(srcPais);
        var srcEstadoCivil = [
            {id: 'S', valor: 'Soltero'},
            {id: 'C', valor: 'Casado'},
            {id: 'D', valor: 'Divorciado'},
            {id: 'V', valor: 'Viudo'}
        ];
        var srcCondicionIVA = [
            {id: 'I', valor: 'Responsable Inscripto'},
            {id: 'M', valor: 'Responsable Monotributo'},
            {id: 'E', valor: 'Exento'},
            {id: 'N', valor: 'No Categorizado'}
        ];
        var srcCondicionGanancias = [
            {id: 'I', valor: 'Inscripto'},
            {id: 'N', valor: 'No Inscripto'}
        ];
        var srcOcupacion = [
            {id: 'D', valor: 'Relación de Dependencia'},
            {id: 'M', valor: 'Monotributista o Autónomo'},
            {id: 'E', valor: 'Estudiante / Ama de Casa / Desempleado'},
            {id: 'J', valor: 'Jubilado'}
        ];
        
        var srcEsCargoPublico = [
            {id: 'N', valor: 'No'},
            {id: 'A', valor: 'Actualmente'},
            {id: 'P', valor: 'En el pasado'}
        ];
        
        if ($("#id").val() == 0){
            $("#titulo").text('Nuevo Formulario de Suscripción');
            $("#ventanaFormulario").ajaxloader('hide');
        } else {
            $("#titulo").text('Editar Formulario de Suscripción');
            datos = {
                id: $("#id").val()
            };
            $.post('/formulario/get', datos, function(data){
                onLoad = true;
                setDropDown("#responsable", data.responsable_id);
                $("#fechaPresentacion").jqxDateTimeInput('val', data.fechaPresentacion);
                setDropDown("#estado", data.estado_id);
                setDropDown("#tramiteAlta", data.tramitealta_id);
                $("#fechaEstado").jqxDateTimeInput('val', data.fechaEstado);
                $("#observaciones").val(data.observaciones);
                /*
                setDropDown("#actuaPor", data.actuaPor);
                if (data.esBeneficiarioFinal == 'S'){
                    $("#esBeneficiarioFinal").val(true);
                } else {
                    $("#esBeneficiarioFinal").val(false);
                    $("#beneficiarioFinal").val(data.beneficiarioFinal);
                }
                */
                setDropDown("#comoNosConocio", data.comoNosConocio);
                if (data.comoNosConocio === 'P'){
                    $("#contacto").val(data.contacto);
                }
                $("#comentarios").val(data.comentarios);
                if (data.numComitente){
                    $("#numComitente").val(data.numComitente);
                } else {
                    $("#numComitente").val(0);
                }
                
                setDropDown("#toleranciaRiesgo", data.toleranciaRiesgo);
                setDropDown("#perfilCuenta", data.perfilCuenta);
                
                setDropDown("#asociarCuenta", data.asociarCuenta);
                if (data.asociarCuenta === 'S'){
                    $("#banco").val(data.banco);
                    setDropDown("#tipoCuentaBanco", data.tipoCuentaBanco);
                    $("#numeroCuenta").val(data.numeroCuenta);
                    setDropDown("#moneda", data.moneda);
                    $("#titular").val(data.titular);
                    $("#cbu").val(data.cbu);
                    $("#cuitCuenta").val(data.cuitCuenta);
                }
                
                
                $("#oficial").val(data.oficial);
                $("#administrador").val(data.administrador);
                $("#terceroNoIntermediario").val(data.terceroNoIntermediario);
                if (data.dniTerceroNoIntermediario){
                    $("#dniTerceroNoIntermediario").val(data.dniTerceroNoIntermediario);
                } else {
                    $("#dniTerceroNoIntermediario").val(0);
                }
                $("#emailTerceroNoInscripto").val(data.emailTerceroNoInscripto);
                if (data.numeroProductor){
                    $("#numeroProductor").val(data.numeroProductor);
                } else {
                    $("#numeroProductor").val(0);
                }
                
                $.each(data.titulares, function(index, titular){
                    totalTitulares = index;
                    var htmlTitular = "\n\
                        <table width='98%' style=\"margin: 10px; padding: 3px; border-spacing: 5px; border-collapse: separate \"><tr><td> \n\
                        <div id='informeAFIP_" + index.toString() + "' style=' width: 100%; height: 100%; overflow: hidden; padding: 3px; box-sizing: border-box; margin: 0;'><div>Informe AFIP</div><div id='contenidoInformeAFIP_" + index.toString() + "'></div></div> \n\
                        </td></tr>\n\
                        <form id='formTitular_" + index.toString() + "'> \n\
                        <input type='hidden' id='id_" + index.toString() + "' value=''> \n\
                        <table style=\"margin: 10px; padding: 3px; border-spacing: 5px; border-collapse: separate \"> \n\
                        <tr> \n\
                            <td>Nombre:</td> \n\
                            <td><input type='text' id='nombre_" + index.toString() + "'></td> \n\
                            <td>Apellido:</td> \n\
                            <td><input type='text' id='apellido_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Tipo Documento:</td> \n\
                            <td><div id='tipoDocumento_" + index.toString() + "'></div></td> \n\
                            <td>Numero Documento:</td> \n\
                            <td><div id='numeroDocumento_" + index.toString() + "'></div></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Frente Documento:</td> \n\
                            <td><img id='imagenDocumento_" + index.toString() + "' style=\"width: 200px; height: 100px\"></td> \n\
                            <td>Dorso Documento:</td> \n\
                            <td><img id='imagenDorso_" + index.toString() + "' style=\"width: 200px; height: 100px\"></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Nacionalidad:</td> \n\
                            <td><div id='nacionalidad_" + index.toString() + "'></div></td> \n\
                            <td>&nbsp;</td> \n\
                            <td>&nbsp;</td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Fecha Nacimiento:</td> \n\
                            <td><div id='fechaNacimiento_" + index.toString() + "'></div></td> \n\
                            <td>Lugar Nacimiento:</td> \n\
                            <td><input type='text' id='lugarNacimiento_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Domicilio Particular:</td> \n\
                            <td><input type='text' id='domicilioParticular_" + index.toString() + "'></td> \n\
                            <td>Codigo Postal:</td> \n\
                            <td><input type='text' id='codigoPostalParticular_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Provincia:</td> \n\
                            <td><input type='text' id='provinciaParticular_" + index.toString() + "'></td> \n\
                            <td>Localidad:</td> \n\
                            <td><input type='text' id='localidadParticular_" + index.toString() + "'</td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Imagen Servicio:</td> \n\
                            <td><img id='imagenServicio_" + index.toString() + "' style=\"width: 200px; height: 100px\"></td> \n\
                            <td>Residencias:</td> \n\
                            <td><div id='residencias_" + index.toString() + "'></div></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Telefono Particular:</td> \n\
                            <td><input type='text' id='telefonoParticular_" + index.toString() + "'></td> \n\
                            <td>Telefono Celular:</td> \n\
                            <td><input type='text' id='telefonoCelular_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Email 1:</td> \n\
                            <td><input type='text' id='email1_" + index.toString() + "'><img src=\"/images/flecha-verde.png\" id='email1Flecha_" + index.toString() + "'></td> \n\
                            <td>Email 2:</td> \n\
                            <td><input type='text' id='email2_" + index.toString() + "'><img src=\"/images/flecha-verde.png\" id='email2Flecha_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Estado Civil:</td> \n\
                            <td><div id='estadoCivil_" + index.toString() + "'></div></td> \n\
                            <td>&nbsp;</td> \n\
                            <td>&nbsp;</td> \n\
                        </tr> \n\
                        <tr class='conyuge_" + index.toString() + "' > \n\
                            <td>Nombre Conyuge:</td> \n\
                            <td><input type='text' id='nombreConyuge_" + index.toString() + "'></td> \n\
                            <td>Apellido Conyuge:</td> \n\
                            <td><input type='text' id='apellidoConyuge_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>CUIT:</td> \n\
                            <td><div id='cuit_" + index.toString() + "'></div></td> \n\
                            <td>Condicion IVA:</td> \n\
                            <td><div id='condicionIVA_" + index.toString() + "'></div></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Condicion Ganancias:</td> \n\
                            <td><div id='condicionGanancias_" + index.toString() + "'></div></td> \n\
                            <td class='actividad_" + index.toString() + "'>Actividad:</td> \n\
                            <td class='actividad_" + index.toString() + "'><input type='text' id='actividad_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Ocupacion:</td> \n\
                            <td><div id='ocupacion_" + index.toString() + "'></div></td> \n\
                            <td class='empleador_" + index.toString() + "'>Empleador:</td> \n\
                            <td class='empleador_" + index.toString() + "'><input type='text' id='empleador_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr class='laboral_" + index.toString() + "'> \n\
                            <td>Domicilio Laboral:</td> \n\
                            <td><input type='text' id='domicilioLaboral_" + index.toString() + "'></td> \n\
                            <td>Código Postal:</td> \n\
                            <td><input type='text' id='codigoPostalLaboral_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr class='laboral_" + index.toString() + "'> \n\
                            <td>Provincia:</td> \n\
                            <td><input type='text' id='provinciaLaboral_" + index.toString() + "'></td> \n\
                            <td>Localidad:</td> \n\
                            <td><input type='text' id='localidadLaboral_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr class='laboral_" + index.toString() + "'> \n\
                            <td>Telefono Laboral:</td> \n\
                            <td><input type='text' id='telefonoLaboral_" + index.toString() + "'></td> \n\
                            <td>&nbsp;</td> \n\
                            <td>&nbsp;</td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td>Cargo Publico:</td> \n\
                            <td><div id='esCargoPublico_" + index.toString() + "'></div></td> \n\
                            <td class='detalleCargo_" + index.toString() + "'>Detalle del Cargo:</td> \n\
                            <td class='detalleCargo_" + index.toString() + "'><input type='text' id='detalleCargoPublico_" + index.toString() + "'></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td class='fechaIngreso_" + index.toString() + "'>Fecha Ingreso:</td> \n\
                            <td class='fechaIngreso_" + index.toString() + "'><div id='fechaIngreso_" + index.toString() + "'></div></td> \n\
                            <td class='fechaEgreso_" + index.toString() + "'>Fecha de Egreso:</td> \n\
                            <td class='fechaEgreso_" + index.toString() + "'><div id='fechaEgreso_" + index.toString() + "'></div></td> \n\
                        </tr> \n\
                        <tr> \n\
                            <td><div id='esPEP_" + index.toString() + "'>Es PEP:</div></td> \n\
                            <td><input type='text' id='detallePEP_" + index.toString() + "'></td> \n\
                            <td><div id='esUIF_" + index.toString() + "'>Es UIF:</div></td> \n\
                            <td><img id='imagenUIF_" + index.toString() + "' style=\"width: 200px; height: 100px\"></td> \n\
                        </tr> \n\
                    </table> \n\
                    </form>";
                    
                    
                    $("#tabPrincipal").jqxTabs('addLast', titular.nombre + ' ' + titular.apellido, htmlTitular);
                    $("#nombre_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#apellido_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#tipoDocumento_" + index.toString()).jqxDropDownList({ width: '200', height: '20', theme: theme, source: srcTipoDocumento, displayMember: 'valor', valueMember: 'id'});
                    $("#numeroDocumento_" + index.toString()).jqxNumberInput({ width: '200', height: '20', decimalDigits: 0, digits: 8, groupSeparator: '.', max: 99999999, theme: theme});
                    $("#nacionalidad_" + index.toString()).jqxDropDownList({ width: '200', height: '20', theme: theme, source: DAPais, displayMember: 'nombre', valueMember: 'codigo'});
                    $("#fechaNacimiento_" + index.toString()).jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy', theme: theme });
                    $("#lugarNacimiento_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#domicilioParticular_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#codigoPostalParticular_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#provinciaParticular_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#localidadParticular_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#residencias_" + index.toString()).jqxListBox({height: 100, width: 200, theme: theme});
                    $("#telefonoParticular_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#telefonoCelular_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#email1_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#email2_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#estadoCivil_" + index.toString()).jqxDropDownList({ width: '200', height: '20', theme: theme, source: srcEstadoCivil, displayMember: 'valor', valueMember: 'id'});
                    $("#nombreConyuge_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#apellidoConyuge_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#cuit_" + index.toString()).jqxMaskedInput({ width: '200', height: '20', mask: '##-########-#', theme: theme});
                    $("#condicionIVA_" + index.toString()).jqxDropDownList({ width: '200', height: '20', theme: theme, source: srcCondicionIVA, displayMember: 'valor', valueMember: 'id'});
                    $("#condicionGanancias_" + index.toString()).jqxDropDownList({ width: '200', height: '20', theme: theme, source: srcCondicionGanancias, displayMember: 'valor', valueMember: 'id'});
                    $("#actividad_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#ocupacion_" + index.toString()).jqxDropDownList({ width: '200', height: '20', theme: theme, source: srcOcupacion, displayMember: 'valor', valueMember: 'id'});
                    $("#empleador_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#domicilioLaboral_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#codigoPostalLaboral_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#provinciaLaboral_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#localidadLaboral_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#telefonoLaboral_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#esCargoPublico_" + index.toString()).jqxDropDownList({ width: '200', height: '20', theme: theme, source: srcEsCargoPublico, displayMember: 'valor', valueMember: 'id'});
                    $("#detalleCargoPublico_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#fechaIngreso_" + index.toString()).jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy', theme: theme });
                    $("#fechaEgreso_" + index.toString()).jqxDateTimeInput({ width: '200px', height: '20px',  formatString: 'dd/MM/yyyy', theme: theme });
                    $("#esPEP_" + index.toString()).jqxCheckBox({ height: 20, theme: theme });
                    $("#detallePEP_" + index.toString()).jqxInput({height: 20, width: 200, theme: theme });
                    $("#esUIF_" + index.toString()).jqxCheckBox({ height: 20, theme: theme });
                    
                    $("#estadoCivil_" + index.toString()).on('change', function (event){
                        var args = event.args;
                        if (args){
                            var item = args.item;
                            if (item.value == 'C'){
                                $(".conyuge_" + index.toString()).show();
                            } else {
                                $(".conyuge_" + index.toString()).hide();
                            }
                        }
                    });
                    
                    $("#ocupacion_" + index.toString()).on('change', function (event){
                        var args = event.args;
                        if (args){
                            var item = args.item;
                            switch (item.value){
                                case 'D':
                                    $('.empleador_' + index.toString()).show();
                                    $('.actividad_' + index.toString()).hide();
                                    $('.laboral_' + index.toString()).show();
                                    break;
                                case 'M':
                                    $('.empleador_' + index.toString()).hide();
                                    $('.actividad_' + index.toString()).show();
                                    $('.laboral_' + index.toString()).show();
                                    break;
                                case 'E':
                                case 'J':
                                    $('.empleador_' + index.toString()).hide();
                                    $('.actividad_' + index.toString()).hide();
                                    $('.laboral_' + index.toString()).hide();                                    
                                    break;
                            }
                        }
                    });
                    
                    $("#esPEP_" + index.toString()).on('change', function(event){
                        var checked = event.args.checked;
                        if (checked){
                            $("#detallePEP_" + index.toString()).show();
                        } else {
                            $("#detallePEP_" + index.toString()).hide();
                        }
                    });
                    
                    $("#esUIF_" + index.toString()).on('change', function(event){
                        var checked = event.args.checked;
                        if (checked){
                            $("#imagenUIF_" + index.toString()).show();
                        } else {
                            $("#imagenUIF_" + index.toString()).hide();
                        }
                    });
                    
                    $("#esCargoPublico_" + index.toString()).on('change', function (event){
                        var args = event.args;
                        if (args){
                            var item = args.item;
                            switch (item.value){
                                case 'N':
                                    $(".detalleCargo_" + index.toString()).hide();
                                    $(".fechaIngreso_" + index.toString()).hide();
                                    $(".fechaEgreso_" + index.toString()).hide();                                    
                                    break;
                                case 'A':
                                    $(".detalleCargo_" + index.toString()).show();
                                    $(".fechaIngreso_" + index.toString()).show();
                                    $(".fechaEgreso_" + index.toString()).hide();                                    
                                    break;
                                case 'P':
                                    $(".detalleCargo_" + index.toString()).show();
                                    $(".fechaIngreso_" + index.toString()).show();
                                    $(".fechaEgreso_" + index.toString()).show();                                    
                                    break;
                            }
                        }
                    });
                    
                    
                    $("#id_" + index.toString()).val(titular.id);
                    $("#nombre_" + index.toString()).val(titular.nombre);
                    $("#apellido_" + index.toString()).val(titular.apellido);
                    setDropDown("#tipoDocumento_" + index.toString(), titular.tipoDocumento);
                    if (titular.numeroDocumento){
                        $("#numeroDocumento_" + index.toString()).val(titular.numeroDocumento);
                    } else {
                        $("#numeroDocumento_" + index.toString()).val(0);
                    }
                    $("#imagenDocumento_" + index.toString()).attr('src', titular.imagenDocumento);
                    $("#imagenDocumento_" + index.toString()).viewer();
                    $("#imagenDorso_" + index.toString()).attr('src', titular.imagenDorso);
                    $("#imagenDorso_" + index.toString()).viewer();
                    setDropDown("#nacionalidad_" + index.toString(), titular.nacionalidad);
                    $("#fechaNacimiento_" + index.toString()).jqxDateTimeInput('val', titular.fechaNacimiento);
                    $("#lugarNacimiento_" + index.toString()).val(titular.lugarNacimiento);
                    $("#domicilioParticular_" + index.toString()).val(titular.domicilioParticular);
                    $("#codigoPostalParticular_" + index.toString()).val(titular.codigoPostalParticular);
                    $("#provinciaParticular_" + index.toString()).val(titular.provinciaParticular);
                    $("#localidadParticular_" + index.toString()).val(titular.localidadParticular);
                    $("#imagenServicio_" + index.toString()).attr('src', titular.imagenServicio);
                    $("#imagenServicio_" + index.toString()).viewer();
                    
                    $.each(titular.residencias, function(indice, residencia){
                        var etiqueta = residencia.paisResidencia;
                        if (residencia.idTributaria){
                            etiqueta = etiqueta + " (" + residencia.idTributaria + ")";
                        }
                        var item = {
                            html: "<div style='height: 20px; float: left;'><img width='16' height='16' style='float: left; margin-top: 2px; margin-right: 5px;' src='/images/banderas/" + residencia.paisResidencia.toLowerCase() + ".png'/><span style='float: left; font-size: 13px; font-family: Verdana Arial;'>" + etiqueta + "</span></div>", 
                            title: residencia.paisResidencia,
                            value: residencia.id
                        };
                        
                        $("#residencias_" + index.toString()).jqxListBox('addItem', item);
                    });
                    
                    $("#residencias_" + index.toString()).dblclick(function(){
                        var item = $("#residencias_" + index.toString()).jqxListBox('getSelectedItem');
                        if (item){
                            $("#indiceTitular").val(index.toString());
                            $("#indice").val($("#residencias_" + index.toString()).jqxListBox('getSelectedIndex'));
                            $.post('/residencia/get', {id: item.value}, function(residencia){
                                $("#idResidencia").val(residencia.id);
                                $("#titular_id").val(residencia.titular_id);
                                $("#paisResidencia").val(residencia.paisResidencia);
                                if (residencia.paisResidencia !== 'AR'){
                                    $("#idTributaria").val(residencia.idTributaria);
                                } else {
                                    $("#idTributaria").val('');
                                }
                                $("#ventanaResidencia").jqxWindow('open');
                                $('#ventanaResidencia').jqxWindow('bringToFront');
                            }, 'json');
                            
                        }
                    });
        

                    
                    $("#telefonoParticular_" + index.toString()).val(titular.telefonoParticular);
                    $("#telefonoCelular_" + index.toString()).val(titular.telefonoCelular);
                    $("#email1_" + index.toString()).val(titular.email1);
                    $("#email2_" + index.toString()).val(titular.email2);
                    if (titular.email1Verificado == 1){
                        $("#email1_" + index.toString()).jqxInput({disabled: true });
                    } else {
                        $("#email1Flecha_" + index.toString()).attr('src', '/images/cruz-roja.gif');
                        $("#email1Flecha_" + index.toString()).on('click', function(){
                            new Messi('Desea verificar el email?' , {title: 'Confirmar', modal: true,  buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                                if (val === 's'){
                                    $.post('/formulario/verificarEmail',{titular_id: titular.id, numeroEmail: 1}, function(datos){
                                        $("#email1_" + index.toString()).jqxInput({disabled: true });
                                        $("#email1Flecha_" + index.toString()).attr('src', '/images/flecha-verde.png');
                                        $("#email1Flecha_" + index.toString()).unbind('click');
                                    }, 'json');
                                }
                            }});
                        });
                    }
                    if (titular.email2Verificado == 1){
                        $("#email2_" + index.toString()).jqxInput({disabled: true });
                    } else {
                        $("#email2Flecha_" + index.toString()).attr('src', '/images/cruz-roja.gif');
                        $("#email2Flecha_" + index.toString()).on('click', function(){
                            new Messi('Desea verificar el email?' , {title: 'Confirmar', modal: true,  buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                                if (val === 's'){
                                    $.post('/formulario/verificarEmail',{titular_id: titular.id, numeroEmail: 2}, function(datos){
                                        $("#email2_" + index.toString()).jqxInput({disabled: true });
                                        $("#email2Flecha_" + index.toString()).attr('src', '/images/flecha-verde.png');
                                        $("#email2Flecha_" + index.toString()).unbind('click');
                                    }, 'json');
                                }
                            }});
                        });
                    }
                    setDropDown("#estadoCivil_" + index.toString(), titular.estadoCivil);
                    $("#nombreConyuge_" + index.toString()).val(titular.nombreConyuge);
                    $("#apellidoConyuge_" + index.toString()).val(titular.apellidoConyuge);
                    $("#cuit_" + index.toString()).val(titular.cuit);
                    setDropDown("#condicionIVA_" + index.toString(), titular.condicionIVA);
                    setDropDown("#condicionGanancias_" + index.toString(), titular.condicionGanancias);
                    setDropDown("#ocupacion_" + index.toString(), titular.ocupacion);
                    switch (titular.ocupacion){
                        case 'D':
                            $("#empleador_" + index.toString()).val(titular.empleador);
                            $("#domicilioLaboral_" + index.toString()).val(titular.domicilioLaboral);
                            $("#codigoPostalLaboral_" + index.toString()).val(titular.codigoPostalLaboral);
                            $("#provinciaLaboral_" + index.toString()).val(titular.provinciaLaboral);
                            $("#localidadLaboral_" + index.toString()).val(titular.localidadLaboral);
                            $("#telefonoLaboral_" + index.toString()).val(titular.telefonoLaboral);
                            break;
                        case 'M':
                            $("#actividad_" + index.toString()).val(titular.actividad);
                            $("#domicilioLaboral_" + index.toString()).val(titular.domicilioLaboral);
                            $("#codigoPostalLaboral_" + index.toString()).val(titular.codigoPostalLaboral);
                            $("#provinciaLaboral_" + index.toString()).val(titular.provinciaLaboral);
                            $("#localidadLaboral_" + index.toString()).val(titular.localidadLaboral);
                            $("#telefonoLaboral_" + index.toString()).val(titular.telefonoLaboral);
                            break;
                    }
                    setDropDown("#esCargoPublico_" + index.toString(), titular.esCargoPublico);
                    $("#detalleCargoPublico_" + index.toString()).val(titular.detalleCargoPublico);
                    $("#fechaIngreso_" + index.toString()).jqxDateTimeInput('val', titular.fechaIngreso);
                    $("#fechaEgreso_" + index.toString()).jqxDateTimeInput('val', titular.fechaEgreso);
                    if (titular.esPEP == 'S'){
                        $("#esPEP_" + index.toString()).jqxCheckBox('check');
                    } else {
                        $("#esPEP_" + index.toString()).jqxCheckBox('uncheck');
                    }
                    $("#detallePEP_" + index.toString()).val(titular.detallePEP);
                    if (titular.esUIF == 'S'){
                        $("#esUIF_" + index.toString()).jqxCheckBox('check');
                    } else {
                        $("#esUIF_" + index.toString()).jqxCheckBox('uncheck');
                    }
                    $("#imagenUIF_" + index.toString()).attr('src', titular.imagenUIF);
                    $("#imagenUIF_" + index.toString()).viewer();
                    
                    $("#formTitular_" + index.toString()).jqxValidator({rules:[
                        { input: '#nombre_' + index.toString(), message: 'Debe ingresar el nombre!', action: 'blur',  rule: 'required' },
                        { input: '#apellido_' + index.toString(), message: 'Debe ingresar el apellido!', action: 'blur',  rule: 'required' },
                        { input: '#tipoDocumento_' + index.toString(), message: 'Debe seleccionar el tipo de documento', action: 'blur', rule: function(){
                            return ($("#tipoDocumento_" + index.toString()).jqxDropDownList('getSelectedIndex') !== -1);
                        }},
                        { input: '#numeroDocumento_' + index.toString(), message: 'El numero de documento no es valido', action: 'blur', rule: function(){
                            return ($("#numeroDocumento_" + index.toString()).val() > 1000000);
                        }},
                        { input: '#nacionalidad_' + index.toString(), message: 'Debe seleccionar el país de nacimiento', action: 'blur', rule: function(){
                            return ($("#nacionalidad_" + index.toString()).jqxDropDownList('getSelectedIndex') !== -1);
                        }},
                        { input: '#fechaNacimiento_' + index.toString(), message: 'La fecha de nacimiento no es válida!', rule: function(){
                            var fechaNacimiento = moment($("#fechaNacimiento_" + index.toString()).jqxDateTimeInput('val','date'));
                            return moment().diff(fechaNacimiento, 'years') >= 16;
                        }},
                        { input: '#lugarNacimiento_' + index.toString(), message: 'Debe ingresar el lugar de nacimiento!', action: 'blur',  rule: 'required' },
                        { input: '#domicilioParticular_' + index.toString(), message: 'Debe ingresar el domicilio!', action: 'blur',  rule: 'required' },
                        { input: '#codigoPostalParticular_' + index.toString(), message: 'Debe ingresar el código postal!', action: 'blur',  rule: 'required' },
                        { input: '#provinciaParticular_' + index.toString(), message: 'Debe ingresar la provincia!', action: 'blur',  rule: 'required' },
                        { input: '#localidadParticular_' + index.toString(), message: 'Debe ingresar la localidad!', action: 'blur',  rule: 'required' },
                        { input: '#telefonoCelular_' + index.toString(), message: 'Debe ingresar el celular!', action: 'blur',  rule: 'required' },
                        { input: '#email1_' + index.toString(), message: 'Debe ingresar al menos un e-mail!', action: 'blur',  rule: 'required' },
                        { input: '#email1_' + index.toString(), message: 'El formato del e-mail no es válido!', action: 'blur',  rule: 'email' },
                        { input: '#email2_' + index.toString(), message: 'El formato del e-mail no es válido!', action: 'blur',  rule: 'email' },
                        { input: '#nombreConyuge_' + index.toString(), message: 'Debe ingresar el nombre del cónyuge', action: 'blur', rule: function(){
                            var result = true;
                            if ($('#estadoCivil_' + index.toString()).val() == 'C'){
                                result = ($('#nombreConyuge_' + index.toString()).val().trim().length > 0);
                            }    
                            return result;
                        }},
                        { input: '#apellidoConyuge_' + index.toString(), message: 'Debe ingresar el apellido del cónyuge', action: 'blur', rule: function(){
                            result = true;
                            if ($('#estadoCivil_' + index.toString()).val() == 'C'){
                                result = ($('#apellidoConyuge_' + index.toString()).val().trim().length > 0);
                            }    
                            return result;
                        }},
                        { input: '#cuit_' + index.toString(), message: 'Debe ingresar un CUIT valido', action: 'blur', rule: function(){
                            return validaCuit($("#cuit_" + index.toString()).val().replace(/\-/g,''));
                        }},
                        { input: '#condicionIVA_' + index.toString(), message: 'Debe seleccionar la condicion ante el IVA', action: 'blur', rule: function(){
                            return ($("#condicionIVA_" + index.toString()).jqxDropDownList('getSelectedIndex') !== -1);
                        }},
                        { input: '#condicionGanancias_' + index.toString(), message: 'Debe seleccionar la condicion ante Ganancias', action: 'blur', rule: function(){
                            return ($("#condicionGanancias_" + index.toString()).jqxDropDownList('getSelectedIndex') !== -1);
                        }} ,
                        { input: '#empleador_' + index.toString(), message: 'Debe ingresar el empleador', action: 'blur', rule: function(){
                            var result = true;
                            if ($('#ocupacion_' + index.toString()).val() ==='D'){
                                result = ($('#empleador_' + index.toString()).val().trim().length > 0);
                            }    
                            return result;
                        }},
                        { input: '#actividad_' + index.toString(), message: 'Debe ingresar la actividad', action: 'blur', rule: function(){
                            var result = true;
                            if ($('#ocupacion_' + index.toString()).val() === 'M'){
                                result = ($('#actividad_' + index.toString()).val().trim().length > 0);
                            }    
                            return result;
                        }},
                        { input: '#domicilioLaboral_' + index.toString(), message: 'Debe ingresar el domicilio laboral', action: 'blur', rule: function(){
                            var result = true;
                            if ($.inArray($('#ocupacion_' + index.toString()).val(), ['D', 'M']) >= 0){
                                result = ($('#domicilioLaboral_' + index.toString()).val().trim().length > 0);
                            }    
                            return result;
                        }},
                        { input: '#codigoPostalLaboral_' + index.toString(), message: 'Debe ingresar el código postal laboral', action: 'blur', rule: function(){
                            var result = true;
                            if ($.inArray($('#ocupacion_' + index.toString()).val(), ['D', 'M']) >= 0){
                                result = ($('#codigoPostalLaboral_' + index.toString()).val().trim().length > 0);
                            }    
                            return result;
                        }},
                        { input: '#provinciaLaboral_' + index.toString(), message: 'Debe ingresar la provincia laboral', action: 'blur', rule: function(){
                            var result = true;
                            if ($.inArray($('#ocupacion_' + index.toString()).val(), ['D', 'M']) >= 0){
                                result = ($('#provinciaLaboral_' + index.toString()).val().trim().length > 0);
                            }    
                            return result;
                        }},
                        { input: '#localidadLaboral_' + index.toString(), message: 'Debe ingresar la localidad laboral', action: 'blur', rule: function(){
                            var result = true;
                            if ($.inArray($('#ocupacion_' + index.toString()).val(), ['D', 'M']) >= 0){
                                result = ($('#localidadLaboral_' + index.toString()).val().trim().length > 0);
                            }    
                            return result;
                        }},
                        { input: '#telefonoLaboral_' + index.toString(), message: 'Debe ingresar el telefono laboral', action: 'blur', rule: function(){
                            var result = true;
                            if ($.inArray($('#ocupacion_' + index.toString()).val(), ['D', 'M']) >= 0){
                                result = ($('#telefonoLaboral_' + index.toString()).val().trim().length > 0);
                            }    
                            return result;
                        }},
                        { input: '#esCargoPublico_' + index.toString(), message: 'Debe seleccionar si tiene o tuvo cargo público', action: 'blur', rule: function(){
                            return ($("#esCargoPublico_" + index.toString()).jqxDropDownList('getSelectedIndex') !== -1);
                        }},
                        { input: '#detalleCargoPublico_' + index.toString(), message: 'Debe ingresar el detalle del cargo público', action: 'blur', rule: function(){
                            var result = true;
                            if ($('#esCargoPublico_' + index.toString()).val() != 'N'){
                                result = ($('#detalleCargoPublico_' + index.toString()).val().trim().length > 0);
                            }    
                            return result;
                        }},
                        { input: '#fechaIngreso_' + index.toString(), message: 'La fecha de ingreso no es válida', action: 'blur', rule: function(){
                            var result = true;
                            if ($('#esCargoPublico_' + index.toString()).val() == 'A'){
                                var fechaIngreso = moment($("#fechaIngreso_" + index.toString()).jqxDateTimeInput('val','date'));
                                result = moment().diff(fechaIngreso, 'days') > 0;
                            }
                            return result;
                        }},
                        { input: '#fechaEgreso_' + index.toString(), message: 'La fecha de egreso no es válida', action: 'blur', rule: function(){
                            var result = true;
                            if ($('#esCargoPublico_' + index.toString()).val() == 'P'){
                                var fechaEgreso = moment($("#fechaEgreso_" + index.toString()).jqxDateTimeInput('val','date'));
                                result = moment().diff(fechaEgreso, 'days') > 0;
                            }
                            return result;
                        }} ,
                        { input: '#fechaEgreso_' + index.toString(), message: 'No coinciden las fechas de ingreso y egreso', action: 'blur', rule: function(){
                            var result = true;
                            if ($('#esCargoPublico_' + index.toString()).val() == 'P'){
                                var fechaIngreso = moment($("#fechaIngreso_" + index.toString()).jqxDateTimeInput('val','date')).format('YYYYMMDD');
                                result = moment($("#fechaEgreso_" + index.toString()).jqxDateTimeInput('val','date')).diff(fechaIngreso, 'days') >= 0;
                            }
                            return result;
                        }},
                        { input: '#detalleCargoPublico_' + index.toString(), message: 'Debe ingresar el detalle del cargo público', action: 'blur', rule: function(){
                            var result = true;
                            if ($('#esCargoPublico_' + index.toString()).val() != 'N'){
                                result = ($('#detalleCargoPublico_' + index.toString()).val().trim().length > 0);
                            }  
                            return result;
                        }},
                        { input: '#detallePEP_' + index.toString(), message: 'Debe ingresar el detalle del cargo público', action: 'blur', rule: function(){
                            var result = true;
                            if ($('#esPEP_' + index.toString()).jqxCheckBox('checked')){
                                return ($('#detallePEP_' + index.toString()).val().trim().length > 0);
                            }    
                            return result;
                        }}
                    ], theme:theme});
                
                
                    $("#formTitular_" + index.toString()).on('validationSuccess', function (event) { formOK = true; });
                    $("#formTitular_" + index.toString()).on('validationError', function (event) { formOK = false; }); 
                    
                    /********************************************************************************
                    * 
                    * Aca agrego los datos de AFIP del titular
                    * 
                    *********************************************************************************** */
                    
                    $("#informeAFIP_" + index.toString()).jqxExpander({ width: '98%', height: 130, theme: theme, toggleMode: "none", showArrow: false, expanded: true});
                    
                    var value = cuitSinGuiones($("#cuit_" + index.toString()).jqxMaskedInput('value'));
                    $.getJSON('https://afip.allariaycia.org/padron.php', {cuit: value}, function(contribuyente){
                        if (contribuyente.resultado){
                            var contenido = '\n\
                                <div>\n\
                                    <table style="margin: 2px; padding: 3px; border-spacing: 5px; border-collapse: separate">\n\
                                        <tr>\n\
                                            <td>Denominación: </td>\n\
                                            <td>' + contribuyente.denominacion + ' </td>\n\
                                            <td>Tipo de Persona: </td>\n\
                                            <td>' + contribuyente.tipoPersona + ' </td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td>Numero de Documento: </td>\n\
                                            <td>' + contribuyente.numeroDocumento + ' </td>\n\
                                            <td>Estado: </td>\n\
                                            <td>' + contribuyente.estado + ' </td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td>Direccion: </td>\n\
                                            <td>' + contribuyente.direccion + ' </td>\n\
                                            <td>Localidad: </td>\n\
                                            <td>' + contribuyente.localidad + ' </td>\n\
                                        </tr>\n\
                                        <tr>\n\
                                            <td>Provincia: </td>\n\
                                            <td>' + contribuyente.provincia + ' </td>\n\
                                        </tr>\n\
                                    </table>\n\
                                </div>';
                            $("#contenidoInformeAFIP_" + index.toString()).html(contenido);
                        }
                    });

                    

                
                });
                
                $.each(data.adjuntos, function(index, adjunto){
                    $("#adjuntos").jqxListBox('addItem', {label: adjunto.filename, value: adjunto.id});
                });
                
                totalTitulares++;
                $('#tabPrincipal').jqxTabs('select', 0); 
                $("#ventanaFormulario").ajaxloader('hide');
                onLoad = false;
            }
            , 'json');
        };
        
        $("#cancelar").jqxButton({theme: theme, width: '180px'});
        $("#cancelar").bind('click', function (){
            $.redirect('/formulario');
        });
        
        $("#notificacionGuardado").jqxNotification({
            width: 250, position: "bottom-right", opacity: 0.9,
            autoOpen: false, animationOpenDelay: 800, autoClose: true, autoCloseDelay: 3000, template: "success"
        });
        
        $("#grabar").jqxButton({theme: theme, width: '180px'});
        $("#grabar").bind('click', function (){
            grabar(false);
        });
        
        $("#grabarYSalir").jqxButton({theme: theme, width: '180px'});
        $("#grabarYSalir").bind('click', function(){
            grabar(true);
        });
        
        function grabar(salir){
            formOK = false;
            $("#tabPrincipal").jqxTabs('select', 0);
            $("#formDatosGenerales").jqxValidator('validate');
            if (formOK){
                var i = 0;
                while (i<totalTitulares && formOK){
                    formOK = false;
                    $("#tabPrincipal").jqxTabs('select', i+1);
                    $("#formTitular_" + i.toString()).jqxValidator('validate');
                    i++;
                }
                if (formOK){
                    var esBeneficiarioFinal = 'S';
                    var fechaPresentacion = moment($("#fechaPresentacion").jqxDateTimeInput('val'), 'DD/MM/YYYY HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');
                    
                    var tramitealta_id = null;
                    if ($("#tramiteAlta").jqxDropDownList('getSelectedIndex') !== -1 ){
                        tramitealta_id = $("#tramiteAlta").val();
                    }
                    
                    
                    var fechaEstado = moment($("#fechaEstado").jqxDateTimeInput('val'), 'DD/MM/YYYY HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');
                    if (!$("#esBeneficiarioFinal").val()){
                        esBeneficiarioFinal = 'N';
                    }
                    var dniTerceroNoIntermediario = 0;
                    if ($("#dniTerceroNoIntermediario").val() > 0){
                        dniTerceroNoIntermediario = $("#dniTerceroNoIntermediario").val();
                    }
                    var numeroProductor = 0;
                    if ($("#numeroProductor").val() > 0){
                        numeroProductor = $("#numeroProductor").val();
                    }
                    
                    var oficial = null;
                    if ($("#oficial").val()) {
                        if (typeof $("#oficial").val() === 'object'){
                            oficial = $("#oficial").val().value;
                        } else {
                            oficial = $("#oficial").val();
                        }
                    } 
                    
                    var administrador = null;
                    if ($("#administrador").val()) {
                        if (typeof $("#administrador").val() === 'object'){
                            administrador = $("#administrador").val().value;
                        } else {
                            administrador = $("#administrador").val();
                        }
                    } 
                    
                    var terceroNoIntermediario = null;
                    if ($("#terceroNoIntermediario").val()){
                        if (typeof $("#terceroNoIntermediario").val() === 'object'){
                            terceroNoIntermediario = $("#terceroNoIntermediario").val().value;
                        } else {
                            terceroNoIntermediario = $("#terceroNoIntermediario").val();
                        }
                    }
                    
                    var formulario = {
                        id: $("#id").val(),
                        responsable_id: $("#responsable").val(),
                        fechaPresentacion: fechaPresentacion,
                        estado_id: $("#estado").val(),
                        tramitealta_id: tramitealta_id,
                        observaciones: $("#observaciones").val(),
                        /*
                        actuaPor: $("#actuaPor").val(),
                        esBeneficiarioFinal: esBeneficiarioFinal,
                        beneficiarioFinal: $("#beneficiarioFinal").val(),
                        */
                        comoNosConocio: $("#comoNosConocio").val(),
                        contacto: $("#contacto").val(),
                        comentarios: $("#comentarios").val(),
                        numComitente: $("#numComitente").val(),
                        toleranciaRiesgo: $("#toleranciaRiesgo").val(),
                        perfilCuenta: $("#perfilCuenta").val(),
                        oficial: oficial,
                        administrador: administrador,
                        terceroNoIntermediario: terceroNoIntermediario,
                        dniTerceroNoIntermediario: dniTerceroNoIntermediario,
                        emailTerceroNoInscripto: $("#emailTerceroNoInscripto").val(),
                        numeroProductor: numeroProductor,
                        fechaEstado: fechaEstado
                    };
                    if ($("#asociarCuenta").val() === 'S'){
                        formulario.asociarCuenta = 'S';
                        formulario.banco = $("#banco").val();
                        formulario.tipoCuentaBanco = $("#tipoCuentaBanco").val();
                        formulario.numeroCuenta = $("#numeroCuenta").val();
                        formulario.moneda = $("#moneda").val();
                        formulario.titular = $("#titular").val();
                        formulario.cbu = $("#cbu").val();
                        formulario.cuitCuenta = $("#cuitCuenta").val();
                    } else {
                        formulario.asociarCuenta = 'N';
                    }

                    var titulares = Array();
                    for (var h=0; h<totalTitulares; h++){
                        var fechaNacimiento = moment($("#fechaNacimiento_" + h.toString()).jqxDateTimeInput('val'), 'DD/MM/YYYY').format('YYYY-MM-DD');
                        var fechaIngreso = null;
                        var fechaEgreso = null;
                        if ($("#esCargoPublico_" + h.toString()).val() !== 'N'){
                            fechaIngreso = moment($("#fechaIngreso_" + h.toString()).jqxDateTimeInput('val'), 'DD/MM/YYYY').format('YYYY-MM-DD');
                        }
                        if ($("#esCargoPublico_" + h.toString()).val() === 'P'){
                            fechaEgreso = moment($("#fechaEgreso_" + h.toString()).jqxDateTimeInput('val'), 'DD/MM/YYYY').format('YYYY-MM-DD');
                        }
                        var esPEP = 'N';
                        if ($("#esPEP_" + h.toString()).val()){
                            esPEP = 'S';
                        }
                        var esUIF = 'N';
                        if ($("#esUIF_" + h.toString()).val()){
                            esUIF = 'S';
                        }
                        var titular = {
                            id: $('#id_' + h.toString()).val(),
                            nombre: $('#nombre_' + h.toString()).val(),
                            apellido: $('#apellido_' + h.toString()).val(),
                            tipoDocumento: $("#tipoDocumento_" + h.toString()).val(),
                            numeroDocumento: $("#numeroDocumento_" + h.toString()).val(),
                            nacionalidad: $("#nacionalidad_" + h.toString()).val(),
                            fechaNacimiento: fechaNacimiento,
                            lugarNacimiento: $("#lugarNacimiento_" + h.toString()).val(),
                            domicilioParticular: $("#domicilioParticular_" + h.toString()).val(),
                            codigoPostalParticular: $("#codigoPostalParticular_" + h.toString()).val(),
                            provinciaParticular: $("#provinciaParticular_" + h.toString()).val(),
                            localidadParticular: $("#localidadParticular_" + h.toString()).val(),
                            telefonoParticular: $("#telefonoParticular_" + h.toString()).val(),
                            telefonoCelular: $("#telefonoCelular_" + h.toString()).val(),
                            email1: $("#email1_" + h.toString()).val(),
                            email2: $("#email2_" + h.toString()).val(),
                            estadoCivil: $("#estadoCivil_" + h.toString()).val(),
                            nombreConyuge: $("#nombreConyuge_" + h.toString()).val(),
                            apellidoConyuge: $("#apellidoConyuge_" + h.toString()).val(),
                            cuit: $("#cuit_" + h.toString()).val(),
                            condicionIVA: $("#condicionIVA_" + h.toString()).val(),
                            condicionGanancias: $("#condicionGanancias_" + h.toString()).val(),
                            ocupacion: $("#ocupacion_" + h.toString()).val(),
                            esPEP: esPEP,
                            detallePEP: $("#detallePEP_" + h.toString()).val(),
                            esUIF: esUIF,
                            esCargoPublico: $("#esCargoPublico_" + h.toString()).val(),
                            detalleCargoPublico: $("#detalleCargoPublico_" + h.toString()).val(),
                            fechaIngreso: fechaIngreso,
                            fechaEgreso: fechaEgreso
                        };
                        
                        switch ($("#ocupacion_" + h.toString()).val()){
                            case 'D':
                                titular.empleador = $("#empleador_" + h.toString()).val();
                                titular.domicilioLaboral = $("#domicilioLaboral_" + h.toString()).val();
                                titular.codigoPostalLaboral = $("#codigoPostalLaboral_" + h.toString()).val();
                                titular.provinciaLaboral = $("#provinciaLaboral_" + h.toString()).val();
                                titular.localidadLaboral = $("#localidadLaboral_" + h.toString()).val();
                                titular.telefonoLaboral = $("#telefonoLaboral_" + h.toString()).val();
                                break;
                            case 'M':
                                titular.actividad = $("#actividad_" + h.toString()).val();
                                titular.domicilioLaboral = $("#domicilioLaboral_" + h.toString()).val();
                                titular.codigoPostalLaboral = $("#codigoPostalLaboral_" + h.toString()).val();
                                titular.provinciaLaboral = $("#provinciaLaboral_" + h.toString()).val();
                                titular.localidadLaboral = $("#localidadLaboral_" + h.toString()).val();
                                titular.telefonoLaboral = $("#telefonoLaboral_" + h.toString()).val();
                                break;
                        }
                        
                        titulares.push(titular);
                    }
                    formulario.titulares = titulares;
                    $("#ventanaFormulario").ajaxloader();
                    $.post('/formulario/save', formulario, function(data){
                        if (data.id > 0){
                            if (salir){
                                $.redirect('/formulario');
                            } else {
                                $('#ventanaFormulario').ajaxloader('hide');
                                $("#notificacionGuardado").jqxNotification("open");
                            }
                        } else {
                            new Messi('Hubo un error guardando el formulario', {title: 'Error', 
                                buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                            $('#ventanaFormulario').ajaxloader('hide');
                        }
                    }, 'json');
                }
            }            
        }
        
        
        $('#generarFormulario').jqxButton({ theme: theme, width: '180px' });
        $('#generarFormulario').bind('click', function () {
            var data = {id: $("#id").val()};
            $.post('/formulario/getFicha', data, function(datos){
                var datosPost = {datos: JSON.stringify(datos)};
                $.redirect('/generador/ficha.php', datosPost, 'POST');
            }, 'json');
        });
        
        $('#formDatosGenerales').jqxValidator({rules:[
            { input: '#responsable', message: 'Debe seleccionar el responsable de la suscripción', action: 'blur', rule: function(){
                return ($("#responsable").jqxDropDownList('getSelectedIndex') !== -1);
            }},
            { input: '#fechaPresentacion', message: 'La fecha de presentación no es válida!', rule: function(){
                var fechaPresentacion = moment($("#fechaPresentacion").jqxDateTimeInput('val','date'));
                return moment().diff(fechaPresentacion) >= 0;
            }},
            { input: '#estado', message: 'Debe seleccionar el estado', action: 'blur', rule: function(){
                return ($("#estado").jqxDropDownList('getSelectedIndex') !== -1);
            }},
            /*
            { input: '#actuaPor', message: 'Debe seleccionar el \'actua por\'', action: 'blur', rule: function(){
                return ($("#actuaPor").jqxDropDownList('getSelectedIndex') !== -1);
            }},
            { input: '#beneficiarioFinal', message: 'Debe ingresar el beneficiario final', action: 'blur', rule: function(){
                var result = true;
                if (!$('#esBeneficiarioFinal').jqxCheckBox('checked')){
                    result = ($('#beneficiarioFinal').val().trim().length > 0);
                }
                return result;
            }},
            */
            { input: '#comoNosConocio', message: 'Debe indicar como nos conoció', action: 'blur', rule: function(){
                return ($("#comoNosConocio").jqxDropDownList('getSelectedIndex') !== -1);
            }},
            { input: '#contacto', message: 'Debe ingresar el contacto en Allaria', action: 'blur', rule: function(){
                var result = true;
                if ($('#comoNosConocio').val() === 'P'){
                    result = ($('#contacto').val().trim().length > 0);
                }    
                return result;
            }},
            { input: '#numComitente', message: 'No existe un comitente con ese número', action: 'blur', rule: function(){
                $("#formDatosGenerales").jqxValidator('hideHint', '#numComitente');
                var resultadoEsco = false;
                var value = $("#numComitente").val();
                if (value == 0){
                    resultadoEsco = true;
                } else {
                    jQuery.ajaxSetup({async:false});
                    $.post('/esco/getComitente', {numComitente: value}, function(pComitente){
                        if (pComitente){
                            $('#formDatosGenerales').jqxValidator('rules')[5].message = "No coinciden el cuit del comitente (" + pComitente.cuit + ") con ninguno de los titulares";
                            for (var i=0; i<totalTitulares; i++){
                                if (cuitSinGuiones($("#cuit_" + i.toString()).val()) == pComitente.cuit){
                                    resultadoEsco = true;
                                    break;
                                }
                            }
                            if (resultadoEsco){
                                $('#formDatosGenerales').jqxValidator('rules')[5].message = 'No coincide en ESCO (Presencial / No Presencial)';
                                //Aca verifico que esten en ambos lados cargados como presenciales / no Presenciales
                                var tramiteAlta = $("#tramiteAlta").val();
                                switch (tramiteAlta){
                                    // 1 Presencial
                                    case '1':
                                    // 2 Certificado
                                    case '2':
                                        if (pComitente.noPresencial != 0){
                                            resultadoEsco = false;
                                        }
                                        break;
                                    // 3 No Presencial
                                    case '3':
                                        if (pComitente.noPresencial != -1){
                                            resultadoEsco = false;
                                        }
                                        break;
                                }
                            }
                        } else {
                            $('#formDatosGenerales').jqxValidator('rules')[5].message = 'No existe un comitente con ese número';
                            resultadoEsco = false;
                        }    
                    },'json');
                    if (resultadoEsco){
                        $('#formDatosGenerales').jqxValidator('rules')[5].message = 'Ya existe una solicitud con ese numero de comitente';
                        datos = {
                            tabla: 'formulario',
                            campo: 'numComitente',
                            valor: $('#numComitente').val(),
                            id: $('#id').val()
                        };
                        var resultadoEsco;
                        $.post('/util/buscarDuplicado', datos, function(data){
                            if (data.resultado){
                                resultadoEsco = false;
                            } else {
                                resultadoEsco = true;
                            }
                        }
                        , 'json');                                    
                    }
                    jQuery.ajaxSetup({async:true});
                }
                return resultadoEsco;
            }},
            { input: '#banco', message: 'Debe indicar el banco de la cuenta', action: 'blur', rule: function(){
                resultado = true;
                if ($("#asociarCuenta").val() === "S" && $("#banco").val().trim().length === 0){
                    resultado = false;
                }
                return resultado;
            }},
            { input: '#tipoCuentaBanco', message: 'Debe indicar el tipo de cuenta', action: 'blur', rule: function(){
                resultado = true;
                if ($("#asociarCuenta").val() === "S" && $("#tipoCuentaBanco").jqxDropDownList('getSelectedIndex') === -1){
                    resultado = false;
                }
                return resultado;
            }},
            { input: '#numeroCuenta', message: 'Debe indicar el número de cuenta', action: 'blur', rule: function(){
                resultado = true;
                if ($("#asociarCuenta").val() === "S" && $("#numeroCuenta").val().trim().length === 0){
                    resultado = false;
                }
                return resultado;
            }},
            
            { input: '#moneda', message: 'Debe indicar la moneda de la cuenta', action: 'blur', rule: function(){
                resultado = true;
                if ($("#asociarCuenta").val() === "S" && $("#moneda").jqxDropDownList('getSelectedIndex') === -1){
                    resultado = false;
                }
                return resultado;
            }},
            { input: '#titular', message: 'Debe indicar el titular de la cuenta', action: 'blur', rule: function(){
                resultado = true;
                if ($("#asociarCuenta").val() === "S" && $("#titular").val().trim().length === 0){
                    resultado = false;
                }
                return resultado;
            }},
            { input: '#cbu', message: 'El CBU de la cuenta no es valido', action: 'blur', rule: function(){
                resultado = true;
                if ($("#asociarCuenta").val() === "S" && !validarCBU($("#cbu").val())){
                    resultado = false;
                }
                return resultado;
            }},
            { input: '#cuitCuenta', message: 'El CUIT de la cuenta no es válido', action: 'blur', rule: function(){
                resultado = true;
                if ($("#asociarCuenta").val() === "S" && !validaCuit($("#cuitCuenta").val().replace(/\-/g,''))){
                    resultado = false;
                }
                return resultado;
            }},
            { input: '#toleranciaRiesgo', message: 'Debe indicar la tolerancia al riesgo', action: 'blur', rule: function(){
                return ($("#toleranciaRiesgo").jqxDropDownList('getSelectedIndex') !== -1);
            }},
            { input: '#perfilCuenta', message: 'Debe seleccionar el perfil de la cuenta', action: 'blur', rule: function(){
                return ($("#perfilCuenta").jqxDropDownList('getSelectedIndex') !== -1);
            }},
            { input: '#oficial', message: 'Debe ingresar el oficial', action: 'blur', rule: 'required' },
            { input: '#administrador', message: 'Debe ingresar el administrador', action: 'blur', rule: 'required' }
        ], theme: theme});
        
        $('#formDatosGenerales').bind('validationSuccess', function (event) { 
            formOK = true; 
        });
        $('#formDatosGenerales').bind('validationError', function (event) { 
            formOK = false; 
        }); 












        
        /************************************************************************
        * 
        *    VENTANA RESIDENCIAS
        * 
        ***************************************************************************/
        
        
        var formResidenciaOK = false;

        $("#ventanaResidencia").jqxWindow({height: 160, width: 320, theme: theme, autoOpen: false, resizable: false, isModal: true});
        $("#paisResidencia").jqxDropDownList({ width: '200', height: '20', theme: theme, source: DAPais, displayMember: 'nombre', valueMember: 'codigo'});
        $("#idTributaria").jqxInput({width: '200', height: '20', theme: theme});
        $("#grabarResidencia").jqxButton({width: '75', height: '20', theme: theme});
        $("#borrarResidencia").jqxButton({width: '75', height: '20', theme: theme});
        
        $("#paisResidencia").on('change', function(event){
            var args = event.args;
            if (args) {
                var value = args.item.value;
                if (value == 'AR'){
                    $(".idTributaria").hide();
                } else {
                    $(".idTributaria").show();
                }
            }
        });
        
        $('#formResidencia').jqxValidator({rules:[
            { input: '#idTributaria', message: 'Debe ingresar el identificador fiscal!', rule: function(){
                    result = true;
                    if ($("#paisResidencia").val() != 'AR'){
                        result = ($('#idTributaria').val().trim().length > 0);
                    }
                    return result;
            }}
        ]});
        
        $("#grabarResidencia").bind('click', function (){
            formResidenciaOK = false;
            $("#formResidencia").jqxValidator('validate', '#fechaPresentacion');
            if (formResidenciaOK){
                var idTributaria = null;
                if ($("#paisResidencia").val() !== 'AR'){
                    idTributaria = $("#idTributaria").val();
                }
                var datos = {
                    id: $("#idResidencia").val(),
                    paisResidencia: $("#paisResidencia").val(),
                    titular_id: $("#titular_id").val(),
                    idTributaria: idTributaria
                };
                $.post('/residencia/save', datos, function(result){
                    var label = datos.paisResidencia;
                    if (datos.paisResidencia !== 'AR'){
                        label = label + ' (' + datos.idTributaria + ')';
                    }
                    var item = {
                        value: datos.id,
                        title: datos.paisResidencia,
                        html: "<div style='height: 20px; float: left;'><img width='16' height='16' style='float: left; margin-top: 2px; margin-right: 5px;' src='/images/banderas/" + datos.paisResidencia.toLowerCase() + ".png'/><span style='float: left; font-size: 13px; font-family: Verdana Arial;'>" + label + "</span></div>"
                    };
                    $("#residencias_" + $("#indiceTitular").val()).jqxListBox('updateAt', item, $("#indice").val());
                });
            }
        });
        
        $("#borrarResidencia").bind('click', function(){
            
            new Messi('Desea borrar la residencia?' , {title: 'Confirmar', modal: true,  buttons: [{id: 0, label: 'Si', val: 's'}, {id: 1, label: 'No', val: 'n'}], callback: function(val) { 
                if (val === 's'){
                    $.post('/residencia/del', {id: $("#idResidencia").val()}, function(data){
                        $("#residencias_" + $("#indiceTitular").val()).jqxListBox('removeAt', $("#indice").val());
                        $("#ventanaResidencia").jqxWindow('close');
                    }, 'json');
                }
            }});    
        });
        
        $('#formResidencia').bind('validationSuccess', function (event) { 
            formResidenciaOK = true; 
        });
        $('#formResidencia').bind('validationError', function (event) { 
            formResidenciaOK = false; 
        }); 
        
        $("#descargarAdjunto").on('click', function(){
            var item = $("#adjuntos").jqxListBox('getSelectedItem'); 
            if (item){
                $.redirect('/formulario/getAdjunto', {id: item.value}, 'POST');
            }
        });
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        /************************************************************************
        * 
        *    INFORMES
        * 
        ********************************************************************** */
        
        
        
        
        
        
    });

</script>
<input type="hidden" id="id" value="<?php echo $id;?>" >
<div id="ventanaFormulario">
    <div id="titulo">
        Editar Formulario de Solicitud
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
                            <td>Responsable:</td>
                            <td><div id="responsable"></div></td>
                            <td>Fecha Presentacion:</td>
                            <td><div id="fechaPresentacion"></div></td>
                        </tr>
                        <tr>
                            <td>Estado</td>
                            <td><div id="estado"></div></td>
                            <td>Fecha Estado:</td>
                            <td><div id="fechaEstado"></div></td>
                        <tr>
                            <td>Comentarios<br>del cliente:</td>
                            <td><textarea id="comentarios"></textarea></td>
                            <td>Observaciones:</td>
                            <td><textarea id="observaciones"></textarea></td>
                        </tr>
                        <!--
                        <tr>
                            <td>Actua por:</td>
                            <td><div id="actuaPor"></div></td>
                            <td><div id="esBeneficiarioFinal">Beneficiario final:</div></td>
                            <td><input type="text" id="beneficiarioFinal"></td>
                        </tr>
                        -->
                        <tr>
                            <td>Como nos conoció:</td>
                            <td><div id="comoNosConocio"></div></td>
                            <td class="contacto">Contacto en Allaria:</td>
                            <td class="contacto"><input type="text" id="contacto"></td>
                        </tr>
                        <tr>
                            <td>Tramite de alta:</td>
                            <td><div id="tramiteAlta"></div></td>
                            <td class="esAlta">Nro Comitente:</td>
                            <td class="esAlta"><div id="numComitente"></div></td>
                        </tr>
                        <tr>
                            <td>Tolerancia al riesgo:</td>
                            <td><div id="toleranciaRiesgo"></div></td>
                            <td>Perfil de la cuenta:</td>
                            <td><div id="perfilCuenta"></div></td>
                        </tr>
                        <tr>
                            <td>Asociar cuenta:</td>
                            <td><div id="asociarCuenta"></div></td>
                            <td class="banco">Banco:</td>
                            <td class="banco"><input id="banco"></td>
                        </tr>
                        <tr class="banco">
                            <td>Tipo de cuenta:</td>
                            <td><div id="tipoCuentaBanco"></div></td>
                            <td>Número de cuenta:</td>
                            <td><input type="text" id="numeroCuenta"></td>
                        </tr>
                        <tr class="banco">
                            <td>Moneda:</td>
                            <td><div id="moneda"></div></td>
                            <td>Titular:</td>
                            <td><input type="text" id="titular"></td>
                        </tr>
                        <tr class="banco">
                            <td>C.B.U:</td>
                            <td><div id="cbu"></div></td>
                            <td>CUIT de la cuenta:</td>
                            <td><div id="cuitCuenta"></div></td>
                        </tr>
                        <tr>
                            <td>Oficial:</td>
                            <td><input type="text" id="oficial" autocomplete="off"></td>
                            <td>Administrador:</td>
                            <td><input type="text" id="administrador" autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td>Tercero No Inter:</td>
                            <td><input type="text" id="terceroNoIntermediario" autocomplete="off"></td>
                            <td>DNI Tercero No Inter:</td>
                            <td><div id="dniTerceroNoIntermediario"></div></td>
                        </tr>
                        <tr>
                            <td>Email TNI No Insc:</td>
                            <td><input type="text" id="emailTerceroNoInscripto" autocomplete="off"></td>
                            <td>Nro Productor:</td>
                            <td><div id="numeroProductor"></div></td>
                        </tr>
                        <tr>
                            <td>Adjuntos:</td>
                            <td colspan="3">
                                <table>
                                    <tr>
                                        <td><div id="adjuntos"  style="margin-right: '10px'"></div></td>
                                        <td><input type="button" id="descargarAdjunto" value="Descargar"></td>
                                    </tr>
                                </table>
                            </td>
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
                <td><div id="generarFormulario" style="margin: 5px">Generar Formulario</div></td>
            </tr>
        </table>
    </div> 
</div>
<div id="notificacionGuardado">Datos guardados exitósamente</div>
<div id="ventanaResidencia">
        <div>
            Editar Residencia
        </div>
        <div>
            <form id="formResidencia">
                <input type="hidden" id="indiceTitular">
                <input type="hidden" id="indice">
                <input type="hidden" id="idResidencia">
                <input type="hidden" id="titular_id">
                <table style="margin: 10px; padding: 3px; border-spacing: 5px; border-collapse: separate ">
                    <tr>
                        <td>País:</td>
                        <td><div id="paisResidencia"></div></td>
                    </tr>
                    <tr class="idTributaria">
                        <td>Id Fiscal:</td>
                        <td><input type="text" id="idTributaria" ></td>
                    </tr>
                </table>
            </form>
            <table align="center">
                <tr>
                    <td><div id="grabarResidencia" style="margin: 5px">Grabar</div></td>
                    <td><div id="borrarResidencia">Borrar</div></td>
                </tr>
            </table>
        </div>
</div>
