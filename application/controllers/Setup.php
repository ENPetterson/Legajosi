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
        $this->Usuario_model->nombreUsuario = "mpetterson";
        $this->Usuario_model->nombre = "Micaela";
        $this->Usuario_model->apellido = "Petterson";
        $this->Usuario_model->email = "micaela.petterson@allaria.com.ar";
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
        $this->Bono_model->actualizacionAutomatica = 0;
        $this->Bono_model->fechaActualizacion = '30-10-2017';
        
        $bono = $this->Bono_model->saveBono();
        
        echo "Dado de alta el Bono con el id {$bono['id']} <br>";

////////////////////////////////////////////////////////////////////////////////
    
//        $this->load->model('Dato_model');
//        
//        
//        echo "Lo carga";
//        
//        
//        $this->Dato_model->id = 1;
//        $this->Dato_model->bono = 'AA19';
//        $this->Dato_model->fecha = '2';
//        $this->Dato_model->VNActualizado = '2';
//        $this->Dato_model->VRActualizado = '2';
//        $this->Dato_model->cuponAmortizacion = '2';
//        $this->Dato_model->cuponInteres = '2';
//        $this->Dato_model->totalFlujo = '2';
//        $this->Dato_model->fechaActualizacion = '2';
//        
//        $dato = $this->Dato_model->saveDato();
//        
//        echo "Dado de alta el Dato con el id {$dato['id']} <br>";
////////////////////////////////////////////////////////////////////////////////

        $this->load->model('Dato_model');
                
        $this->Dato_model->id = 1;
        $this->Dato_model->bono = 'AA19';
        $this->Dato_model->fecha = '22-Apr-16';
        $this->Dato_model->VNActualizado = '2';
        $this->Dato_model->VRActualizado = '2';
        $this->Dato_model->cuponAmortizacion = '2';
        $this->Dato_model->cuponInteres = '2';
        $this->Dato_model->totalFlujo = '2';
        $this->Dato_model->fechaActualizacion = '2018-05-02';
        
        $dato = $this->Dato_model->saveDato();
        
        echo "Dado de alta el Dato con el id {$dato['id']} <br>";

        
////////////////////////////////////////////////////////////////////////////////
        
        
        
        
        
        
        
        
        
        
        
        
         
//        $dato = R::dispense('dato');
//        $dato->id = 1;
//        $dato->bono = 'AA19';
//        $dato->fecha = '22-Apr-16';
//        $dato->VNActualizado = '2';
//        $dato->VRActualizado = '2';
//        $dato->cuponAmortizacion = '2';
//        $dato->cuponInteres = '2';
//        $dato->totalFlujo = '2';
//        $dato->fechaActualizacion = '2';
//        R::store($dato);
         
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