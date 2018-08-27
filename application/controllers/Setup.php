<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Controller {

    function index() {

        //Eliminando las tablas
   
        $tables = array('usuario', 'grupo', 'menu', 'grupo_usuario', 'grupo_menu','controlador', 
            'controlador_grupo');
        
        R::exec('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tables as $value) {
            R::exec("drop table if exists {$value}");
        }
        R::exec('SET FOREIGN_KEY_CHECKS = 1;');
        
//DROP TABLE ordenes.ci_sessions;
//
//
//CREATE TABLE ordenes.ci_sessions (
//	`id` varchar(40) NOT NULL,
//	`ip_address` varchar(45) NOT NULL,
//	`timestamp` int(10) unsigned NOT NULL DEFAULT '0',
//	`data` blob NOT NULL,
//	KEY `ci_sessions_timestamp` (`timestamp`)
//  ) ENGINE=InnoDB DEFAULT CHARSET=latin1

        
        //Creando los objetos
        $this->load->model('Usuario_model');
        $this->Usuario_model->id = 1;
        $this->Usuario_model->nombreUsuario = "mpetterson";
        $this->Usuario_model->nombre = "Micaela";
        $this->Usuario_model->apellido = "Petterson";
        $this->Usuario_model->email = "micaela.petterson@allaria.com.ar";
        $this->Usuario_model->dominio = "allaria";
        $usuario_id = $this->Usuario_model->saveUsuario();

        echo "Dado de alta el usuario con id {$usuario_id} <br />";
        
        $this->load->model('Usuario_model');
        $this->Usuario_model->id = 2;
        $this->Usuario_model->nombreUsuario = "jleis";
        $this->Usuario_model->nombre = "Javier";
        $this->Usuario_model->apellido = "Leis";
        $this->Usuario_model->email = "jleis@allaria.com.ar";
        $this->Usuario_model->dominio = "allaria";
        $usuario_id = $this->Usuario_model->saveUsuario();

        echo "Dado de alta el usuario con id {$usuario_id} <br />";
        
        $this->load->model('Usuario_model');
        $this->Usuario_model->id = 3;
        $this->Usuario_model->nombreUsuario = "acoppari";
        $this->Usuario_model->nombre = "Azul";
        $this->Usuario_model->apellido = "Coppari";
        $this->Usuario_model->email = "acoppari@allaria.com.ar";
        $this->Usuario_model->dominio = "allaria";
        $usuario_id = $this->Usuario_model->saveUsuario();

        echo "Dado de alta el usuario con id {$usuario_id} <br />";        
     

        $this->load->model('grupo_model');
        $this->grupo_model->nombre = 'Administradores';
        $this->grupo_model->id = 0;
        $grupo_id = $this->grupo_model->saveGrupo();
        
        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->usuario_id = $usuario_id;
        $this->grupo_model->assocUsuario();

        echo "Dado de alta el grupo con el id {$grupo_id} <br />";
////////////////////////////////////////////////////////////////////////////////
        $padre_id = -1;

        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Calculadora';
        $this->menu_model->accion = 'calculadora';
        $menu_id = $this->menu_model->saveMenu();
        $padre_id = $menu_id;
        

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";
        
        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Importar';
        $this->menu_model->accion = 'importar';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";
        
        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Flujos';
        $this->menu_model->accion = 'flujo';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";
        
        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Datos';
        $this->menu_model->accion = 'dato';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";
        
        
        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Estructuras de Bonos';
        $this->menu_model->accion = 'estructuraBono';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";
        
        
        
////////////////////////////////////////////////////////////////////////////////
        $padre_id = -1;

        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Referenciales';
        $this->menu_model->accion = null;
        $menu_id = $this->menu_model->saveMenu();
        $padre_id = $menu_id;

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";
        
        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Monedas';
        $this->menu_model->accion = 'moneda';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";
        
        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Emisores';
        $this->menu_model->accion = 'emisor';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";
        
        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Tipos de Bono';
        $this->menu_model->accion = 'tipoBono';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";
        
        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Bonos';
        $this->menu_model->accion = 'bono';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";
        
        ////////////////////
        
        
//        $this->load->model('menu_model');
//        $this->menu_model->id = 0;
//        $this->menu_model->padre_id = $padre_id;
//        $this->menu_model->nombre = 'Datos';
//        $this->menu_model->accion = 'dato';
//        $menu_id = $this->menu_model->saveMenu();
//
//        $this->grupo_model->id = $grupo_id;
//        $this->grupo_model->menu_id = $menu_id;
//        $this->grupo_model->assocMenu();
//
//        echo "Dado de alta el menu con el id {$menu_id} <br />";
        
        
        
////////////////////////////////////////////////////////////////////////////////        
        $padre_id = -1;

        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Seguridad';
        $this->menu_model->accion = null;
        $menu_id = $this->menu_model->saveMenu();
        $padre_id = $menu_id;

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";


        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Usuarios';
        $this->menu_model->accion = 'usuarioPublico';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";

        $this->load->model('menu_model');
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->id = 0;
        $this->menu_model->nombre = 'Grupos';
        $this->menu_model->accion = 'grupo';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";

        $this->load->model('menu_model');
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->id = 0;
        $this->menu_model->nombre = 'Menues';
        $this->menu_model->accion = 'menu';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";

        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Controladores';
        $this->menu_model->accion = 'controlador';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";

        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Vistas';
        $this->menu_model->accion = 'vista';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";
        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Permisos';
        $this->menu_model->accion = null;
        $menu_id = $this->menu_model->saveMenu();
        $padre_id = $menu_id;

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";

        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Menues';
        $this->menu_model->accion = 'permiso/menu';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";

        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Controladores';
        $this->menu_model->accion = 'permiso/controlador';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";

        $this->load->model('menu_model');
        $this->menu_model->id = 0;
        $this->menu_model->padre_id = $padre_id;
        $this->menu_model->nombre = 'Vistas';
        $this->menu_model->accion = 'permiso/vista';
        $menu_id = $this->menu_model->saveMenu();

        $this->grupo_model->id = $grupo_id;
        $this->grupo_model->menu_id = $menu_id;
        $this->grupo_model->assocMenu();

        echo "Dado de alta el menu con el id {$menu_id} <br />";
        
        //Agregado
        
        $feriados = R::dispense('feriados');
        
        $feriados->anio = '2017';
        $feriados->mes = '01';
        $feriados->dia = '01';
        
        R::store($feriados);

        $feriados = R::dispense('feriados');
        
        $feriados->anio = '2017';
        $feriados->mes = '11';
        $feriados->dia = '20';
        
        R::store($feriados);
        
        $feriados = R::dispense('feriados');
        
        $feriados->anio = '2018';
        $feriados->mes = '01';
        $feriados->dia = '01';
        
        R::store($feriados);
        
        $feriados = R::dispense('feriados');
        
        $feriados->anio = '2018';
        $feriados->mes = '11';
        $feriados->dia = '20';
        
        R::store($feriados);
        
        
        
        $flujo = R::dispense('flujo');
        $flujo->fechapagos = '3-Feb-02';
        $flujo->amortizacion = 100.98;
        $flujo->vr = 100.99;
        $flujo->interes = 0.038750;
        $flujo->fechaActualizacion = '30-10-2017';
        R::store($flujo);
        
        
        
        $this->load->model('Moneda_model');
        
        $this->Moneda_model->id = 1;
        $this->Moneda_model->nombre = 'Moneda1';
        $moneda = $this->Moneda_model->saveMoneda();
        
        echo "Dada de alta la Moneda con el id {$moneda['id']} <br>";
        
        $this->Moneda_model->id = 2;
        $this->Moneda_model->nombre = 'Moneda2';
        $moneda = $this->Moneda_model->saveMoneda();
        
        echo "Dada de alta la Moneda con el id {$moneda['id']} <br>";
        
        
        $this->load->model('Emisor_model');
        
        $this->Emisor_model->id = 1;
        $this->Emisor_model->nombre = 'Emisor1';
        $emisor = $this->Emisor_model->saveEmisor();
        
        echo "Dado de alta el Emisor con el id {$emisor['id']} <br>";
        
        $this->Emisor_model->id = 2;
        $this->Emisor_model->nombre = 'Emisor2';
        $emisor = $this->Emisor_model->saveEmisor();
        
        echo "Dado de alta el Emisor con el id {$emisor['id']} <br>";

////////////////////////////////////////////////////////////////////////////////        
        
        $this->load->model('TipoBono_model');
        
        $this->TipoBono_model->id = 1;
        $this->TipoBono_model->nombre = 'Tipo1';
        $tipoBono = $this->TipoBono_model->saveTipoBono();
        
        echo "Dado de alta el Tipo de Bono con el id {$tipoBono['id']} <br>";
        
        $this->TipoBono_model->id = 2;
        $this->TipoBono_model->nombre = 'Tipo2';
        $tipoBono = $this->TipoBono_model->saveTipoBono();
        
        echo "Dado de alta el Tipo de Bono con el id {$tipoBono['id']} <br>";
 
////////////////////////////////////////////////////////////////////////////////
        

        
        
        
        $this->load->model('Dato_model');
                
        $this->Dato_model->id = 1;
        $this->Dato_model->bono = 'AA19';
        $this->Dato_model->fecha = '2018-06-19';
        
        $this->Dato_model->vr = '2';
        $this->Dato_model->amortizacion = '2';

        $this->Dato_model->VNActualizado = '2';
        $this->Dato_model->VRActualizado = '2';
        $this->Dato_model->cuponAmortizacion = '2';
        $this->Dato_model->cuponInteres = '2';
        $this->Dato_model->totalFlujo = '2';
        $this->Dato_model->fechaActualizacion = '2018-05-02';
        
        $dato = $this->Dato_model->saveDato();
        
        echo "Dado de alta el Dato con el id {$dato['id']} <br>";

        
////////////////////////////////////////////////////////////////////////////////
        
        
        $this->load->model('EstructuraBono_model');
        
        $this->EstructuraBono_model->id = 1;
        
        $this->EstructuraBono_model->especieByma = 'Lala';
        $this->EstructuraBono_model->tipoInstrumentoImpuesto = 'Lala';
        $this->EstructuraBono_model->tipoAjuste = 'Lala';
        $this->EstructuraBono_model->tipoInstrumento = 'Lala';
        $this->EstructuraBono_model->nombreConocido = 'Lala';
        $this->EstructuraBono_model->tipoEmisor = 'Lala';
        $this->EstructuraBono_model->emisor = 'Lala';
        $this->EstructuraBono_model->monedacobro = 'Lala';
        $this->EstructuraBono_model->monedaEmision = 'Lala';
        $this->EstructuraBono_model->cerInicial = 6.1534;
        $this->EstructuraBono_model->diasPreviosCer = 100000;
        $this->EstructuraBono_model->especieCaja = 925800;
        $this->EstructuraBono_model->isin = 'Lala';
        $this->EstructuraBono_model->nombre = 'Lala';
        $this->EstructuraBono_model->fechaEmision = '06/07/2016';
        $this->EstructuraBono_model->fechaVencimiento = '06/07/2016';
        $this->EstructuraBono_model->oustanding = 104.399;
        $this->EstructuraBono_model->ley = 'Lala';
        $this->EstructuraBono_model->amortizacion = '120 cuotas mensuales, iguales y sucesivas, equivalentes las 119 primeras al 0,83% y una última equivalente al 1,23% del monto emitido y ajustado por aplicación del CER, más los intereses capitalizados hasta el 15/03/2014. La primera cuota vencerá el 15/04/2014.';
        $this->EstructuraBono_model->tipoTasa = 'Lala';
        $this->EstructuraBono_model->tipoTasaVariable = 'Lala';
        $this->EstructuraBono_model->spread = 2.50;
        $this->EstructuraBono_model->tasaMinima = 17;
        $this->EstructuraBono_model->tasaMaxima = 10;
        $this->EstructuraBono_model->cuponAnual = 18.200;
        $this->EstructuraBono_model->cantidadCuponesAnio = 0.5;
        $this->EstructuraBono_model->frecuenciaCobro = 'Lala';
        $this->EstructuraBono_model->fechasCobroCupon = 'Período Tasa Anual 01.12.2005 hasta el 01.11.2009 1% 02.11.2009 hasta el 01.11.2013 2% 02.11.2013 hasta el 01.11.2017 3% 02.11.2017 hasta el 01.05.2020 4% Las fechas de pago de intereses serán el 1° de mayo y el 1° de noviembre de cada año, comenzando a partir del 01.05.06. ';
        $this->EstructuraBono_model->formulaCalculoInteres = 'Lala';
        $this->EstructuraBono_model->diasPreviosRecord = 'Lala';
        $this->EstructuraBono_model->proximoCobroInteres = '29-11-18';
        $this->EstructuraBono_model->proximoCobroCapital = '29-11-18';
        $this->EstructuraBono_model->duration = 12.570007278459402;
        $this->EstructuraBono_model->precioMonedaOrigen = 0.0342;
        $this->EstructuraBono_model->lastYtm = 52.708;
        $this->EstructuraBono_model->paridad = 196.885;
        $this->EstructuraBono_model->currentYield = 10.063;
        $this->EstructuraBono_model->interesesCorridos = 5.16;
        $this->EstructuraBono_model->valorResidual = 100.000;
        $this->EstructuraBono_model->valorTecnico = 101.89;
        $this->EstructuraBono_model->mDuration = 10.11;
        $this->EstructuraBono_model->convexity = 111.87;
        $this->EstructuraBono_model->denominacionMinima = 150000;
        $this->EstructuraBono_model->spreadSinTasa = 1.083;
        $this->EstructuraBono_model->ultimaTna = 43.7;
        $this->EstructuraBono_model->diasInicioCupon = 'Lala';
        $this->EstructuraBono_model->diasFinalCupon = 'Lala';
        $this->EstructuraBono_model->capitalizacionInteres = 'Lala';
        $this->EstructuraBono_model->precioPesos = 2725.91;    
        $this->EstructuraBono_model->fechaActualizacion = '30-10-2017';
        
        $estructura = $this->EstructuraBono_model->saveEstructuraBono();
        
        echo "Dado de alta la estructura bono con el id {$estructura['id']} <br>";
        
        
        
////////////////////////////////////////////////////////////////////////////////
        
        
        $this->load->model('Bono_model');
        
        $this->Bono_model->id = 1;
        $this->Bono_model->nombre = 'AA19';
        $this->Bono_model->emisor_id = '1';
        $this->Bono_model->tipobono_id = '1';
        $this->Bono_model->codigocaja = 'codigocaja1';
        $this->Bono_model->codigoisin = 'codigoisin1';
        $this->Bono_model->monedacobro = 'monedacobro1';
        $this->Bono_model->monedabono = 'monedabono1';
        $this->Bono_model->tipotasa = 'tipotasa1';
        $this->Bono_model->tipotasavariable  = 'tipotasavariable1';
        $this->Bono_model->cer = 1;
        $this->Bono_model->cupon = 1.2525;
        $this->Bono_model->cantidadcuponanual = 1;
        $this->Bono_model->vencimiento = '30-10-2017';
        $this->Bono_model->capitalresidual = 100;
        $this->Bono_model->ultimoprecio = 107.46;
        $this->Bono_model->oustanding = 1750;
        $this->Bono_model->proximointeres = '30-10-2017';  
        $this->Bono_model->proximoamortizacion = '30-10-2017';       
        $this->Bono_model->legislacion = 'legislacion1';        
        $this->Bono_model->denominacionminima = 100000;        
        $this->Bono_model->libro = 'C:\BONOS.xlsx'; 
        $this->Bono_model->hoja = 'Hoja1'; 
        $this->Bono_model->actualizacionAutomatica = 'true';
        $this->Bono_model->fechaActualizacion = '30-10-2017';
        
        
        
        
        
        $bono = $this->Bono_model->saveBono();
        
        echo "Dado de alta el Bono con el id {$bono['id']} <br>";

////////////////////////////////////////////////////////////////////////////////
    

        //agregado
        
        $sql = "create or replace view v_bono as 
                SELECT b.id, b.nombre, e.id as emisor_id, e.nombre as emisor_nombre, 
                t.id as tipobono_id, t.nombre as tipobono_nombre, b.codigocaja, 
                b.codigoisin, b.monedacobro, b.monedabono, b.tipotasa, b.tipotasavariable, 
                b.cer, b.cupon, b.cantidadcuponanual, b.vencimiento, b.capitalresidual, 
                b.ultimoprecio, b.oustanding, b.proximointeres, b.proximoamortizacion, 
                b.legislacion, b.denominacionminima, b.actualizacionAutomatica
                FROM bono b
                LEFT JOIN emisor e ON b.emisor_id = e.id
                LEFT JOIN tipobono t ON b.tipobono_id = t.id;";
        
        R::exec($sql);
        
        echo "Creada la vista v_bono <br />";
        
        ////
  
        $controladores = array('*');

        foreach ($controladores as $controlador) {
            
            echo "Controlador : $controlador <br>";
            
            $this->load->model('Controlador_model');
            $this->Controlador_model->id = 0;
            $this->Controlador_model->nombre = $controlador;
            $controlador_id = $this->Controlador_model->saveControlador();
            
            echo "Dado de alta el controlador con el id {$controlador_id} <br>";

            $this->load->model('Grupo_model');
            $this->Grupo_model->id = $grupo_id;
            $this->Grupo_model->controlador_id = $controlador_id;
            $this->Grupo_model->assocControlador();
        }
        
    }
    
}