<?php

class Legislacion_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $nombre;
    public $usuario_id;
    public $menu_id;
    public $controlador_id;
    
    public function saveLegislacion(){
        $legislacion = R::load('legislacion', $this->id);
        $legislacion->nombre = $this->nombre;
        $this->id = R::store($legislacion);
        return $this->id;
    }
    
    public function getLegislacion(){
        $legislacion = R::load('legislacion', $this->id);
        return $legislacion->export();
    }
    
    public function getTiposLegislacionUsuario(){
        $tiposbono = R::getCol('select legislacion_id from legislacion_usuario where usuario_id = ?', array($this->usuario_id));
        return $tiposLegislacion;
    }
    
    public function getTiposLegislacion(){
        $tiposLegislacion = R::getAll('select * from legislacion order by nombre');
        return $tiposLegislacion;
    }
    
    public function delLegislacion(){
        $legislacion = R::load('legislacion', $this->id);
        R::trash($legislacion);
    }
    
    public function assocUsuario(){
        $legislacion = R::load('legislacion', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($legislacion, $usuario);
    }
    
    public function clearRelMenu(){
        $legislacion = R::load('legislacion', $this->id);
        R::clearRelations($legislacion, 'menu');
    }
    
    public function assocMenu(){
        $legislacion = R::load('legislacion', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($legislacion, $menu);
    }
    
    public function clearRelControlador(){
        $legislacion = R::load('legislacion', $this->id);
        R::clearRelations($legislacion, 'controlador');
    }
    
    public function assocControlador(){
        $legislacion = R::load('legislacion', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($legislacion, $controlador);
    }
    
    
}