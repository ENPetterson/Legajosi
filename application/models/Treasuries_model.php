<?php

class Treasuries_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $nombre;
    public $treasuries;
    
    public $usuario_id;
    public $menu_id;
    public $controlador_id;

    
    
    public function saveTreasuries(){
        
        $treasuries = R::load('treasuries', $this->id);
        
        $treasuries->usTreas = $this->usTreas;
        $treasuries->ytm = $this->ytm;
        $treasuries->bp = $this->bp;
        $treasuries->semana = $this->semana;
        $treasuries->mes = $this->mes;
        $treasuries->anio = $this->anio;
        $treasuries->fechaActualizacion = $this->fechaActualizacion;        
        
        $this->id = R::store($treasuries);
        return $this->id;
    }
    
    
    
    public function getFechaActualizacion(){
        $fechaActualizacion = R::getAll('SELECT DISTINCT fechaActualizacion from treasuries');        

        return $fechaActualizacion;
    }
    
    
        public function grillaTreasuriesFecha(){
        
        
        $sql = "SELECT 
                id,
                usTreas,
                ytm,
                bp,
                semana,
                mes,
                anio
                FROM treasuries
                WHERE fechaActualizacion = ?
                ORDER BY id"; 
        
        $resultado = R::getAll($sql, array($this->fechaActualizacion));

        return $resultado;
    }
    
    
    
    
    
    
    
    
    
    
//    public function getTreasuries(){
//        $treasuries = R::load('treasuries', $this->id);
//        return $treasuries->export();
//    }
//    
//    public function getTreasuriesId(){
//        $treasuries = $this->treasuries;    
//        $sql = "select id from treasuries WHERE nombre = '{$treasuries}' "; 
//        $treasuries = R::getRow($sql);         
//        return $treasuries;
//        
//    }
//    
//    

//   

//    
//    public function delTreasuries(){
//        $treasuries = R::load('treasuries', $this->id);
//        R::trash($treasuries);
//    }
    
    public function assocUsuario(){
        $treasuries = R::load('treasuries', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($treasuries, $usuario);
    }
    
    public function clearRelMenu(){
        $treasuries = R::load('treasuries', $this->id);
        R::clearRelations($treasuries, 'menu');
    }
    
    public function assocMenu(){
        $treasuries = R::load('treasuries', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($treasuries, $menu);
    }
    
    public function clearRelControlador(){
        $treasuries = R::load('treasuries', $this->id);
        R::clearRelations($treasuries, 'controlador');
    }
    
    public function assocControlador(){
        $treasuries = R::load('treasuries', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($treasuries, $controlador);
    }

}