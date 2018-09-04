<?php

class Bono_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $nombre;
    public $emisor_id;
    public $tipobono_id;
    public $codigocaja;
    public $codigoisin;
    
    public $usuario_id;
    public $menu_id;
    public $controlador_id;
    
    public $monedacobro;
    public $monedabono;
    public $tipotasa;
    public $tipotasavariable;
    public $cer;
    public $cupon;
    public $cantidadcuponanual;
    public $vencimiento;
    public $capitalresidual;
    public $ultimoprecio;
    public $oustanding;
    public $proximointeres;
    public $proximoamortizacion;
    public $legislacion;
    public $denominacionminima;    
    public $libro;      
    public $hoja;  
    
    
    public function saveBono(){
        
        $bono = R::load('bono', $this->id);
        $emisor = R::load('emisor', $this->emisor_id); 
        $tipobono = R::load('tipobono', $this->tipobono_id);
        
        $monedacobro = R::load('moneda', $this->monedacobro_id); 
        $monedabono = R::load('moneda', $this->monedabono_id);
        
        $tipotasa = R::load('tipotasa', $this->tipotasa_id); 
        $tipotasavariable = R::load('tipotasavariable', $this->tipotasavariable_id);
        
        $legislacion = R::load('legislacion', $this->legislacion_id);
        
        
        $bono->nombre = $this->nombre;
        
        $bono->emisor = $emisor;
        $bono->tipobono = $tipobono;
        
        $bono->codigocaja = $this->codigocaja;
        $bono->codigoisin = $this->codigoisin;
        
        $bono->monedacobro = $monedacobro;
        $bono->monedabono = $monedabono;
        
        $bono->tipotasa = $tipotasa;
        $bono->tipotasavariable  = $tipotasavariable;
        $bono->cer = $this->cer;
        $bono->cupon = $this->cupon;
        $bono->cantidadcuponanual = $this->cantidadcuponanual;
        $bono->vencimiento = $this->vencimiento;
        $bono->capitalresidual = $this->capitalresidual;
        $bono->ultimoprecio = $this->ultimoprecio;
        $bono->oustanding = $this->oustanding;
        $bono->proximointeres = $this->proximointeres;
        $bono->proximoamortizacion = $this->proximoamortizacion;   
        
        $bono->legislacion = $legislacion;    
        
        $bono->denominacionminima = $this->denominacionminima;
        
        
        
        $bono->libro = $this->libro;
        $bono->hoja = $this->hoja;
        
        $bono->actualizacionAutomatica = $this->actualizacionAutomatica;
        $bono->fechaActualizacion = $this->fechaActualizacion;
        
        
        $this->id = R::store($bono);
        return $this->id;
    }
    
    public function getBono(){
        $bono = R::load('bono', $this->id);
        return $bono->export();
    }
    
    public function getBonoId(){
        $bono = $this->bono;    
        $sql = "select id from bono WHERE nombre = '{$bono}' "; 
        $bono = R::getRow($sql);         
        return $bono;
        
    }
    
    
    public function getBonosUsuario(){
        $bonos = R::getCol('select bono_id from bono_usuario where usuario_id = ?', array($this->usuario_id));
        return $bonos;
    }
    
    public function getBonos(){
        $bonos = R::getAll('select * from bono order by nombre');
        return $bonos;
    }
    
    
    public function getBonosBonos(){
//        $bonos = R::getAll('SELECT * FROM bono WHERE libro = {$this->bono}');
        
        $this->bono = 'BONOS.xlsm';           
        $sql = "select * from bono WHERE libro = '{$this->bono}' order by nombre";
        $bonos = R::getAll($sql); 
        return $bonos;
    }
    
    public function getBonosProvinciales(){
//        $bonos = R::getAll('SELECT * FROM bono WHERE libro = {$this->bono}');
        
        $this->bono = 'provinciales.xlsm';           
        $sql = "select * from bono WHERE libro = '{$this->bono}' order by nombre";
        $bonos = R::getAll($sql); 
        return $bonos;
    }
    
    

    public function getCodigoCaja(){
        $bono = $this->bono;    
        $sql = "select codigocaja from bono WHERE id = {$this->bono}"; 
        $bonos = R::getAll($sql);       
        
        //foreach ($bonos as $bono) {
        //    $b = $bono['codigocaja'];
        //}
                
        return $bonos;
        
    }

    public function getAll(){

        $buscador = $this->buscador;
        $emisor_id = $this->emisor_id;
        $tipobono_id = $this->tipobono_id;
               
        $sql = "select * from bono WHERE 1 = 1"; 
        
        if(($emisor_id > 0) && ($tipobono_id > 0) && ($buscador != '')){
            $sql.=" AND nombre LIKE '%{$this->buscador}%' AND (emisor_id = {$this->emisor_id} AND tipobono_id = {$this->tipobono_id})  ";
        }elseif(($emisor_id > 0) && ($tipobono_id > 0)){
            $sql.=" AND emisor_id = {$this->emisor_id} AND tipobono_id = {$this->tipobono_id}";
        
            
        }elseif (($buscador > 0) && ($emisor_id > 0) && ($tipobono_id == '')){
            $sql.=" AND nombre LIKE '%{$this->buscador}%' AND emisor_id = {$this->emisor_id} ";
        }elseif (($buscador > 0) && ($emisor_id == '') && ($tipobono_id > 0)){
            $sql.=" AND nombre LIKE '%{$this->buscador}%' AND tipobono_id = {$this->tipobono_id} ";
        }
        
        elseif ($buscador > 0){
            $sql.=" AND nombre LIKE '%{$this->buscador}%' ";
        }elseif($emisor_id > 0){
            $sql.=" AND emisor_id = {$this->emisor_id}";
        }elseif ($tipobono_id > 0){
            $sql.=" AND tipobono_id = {$this->tipobono_id}";
        }
        
        $bonos = R::getAll($sql);
                
        return $bonos;
    }
    
    public function delBono(){
        $bono = R::load('bono', $this->id);
        R::trash($bono);
    }
    
    public function assocUsuario(){
        $bono = R::load('bono', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($bono, $usuario);
    }
    
    public function clearRelMenu(){
        $bono = R::load('bono', $this->id);
        R::clearRelations($bono, 'menu');
    }
    
    public function assocMenu(){
        $bono = R::load('bono', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($bono, $menu);
    }
    
    public function clearRelControlador(){
        $bono = R::load('bono', $this->id);
        R::clearRelations($bono, 'controlador');
    }
    
    public function assocControlador(){
        $bono = R::load('bono', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($bono, $controlador);
    }

}