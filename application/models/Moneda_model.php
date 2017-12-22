<?php

class Moneda_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $nombre;
    public $usuario_id;
    public $menu_id;
    public $controlador_id;
    
    public function saveMoneda(){
        $moneda = R::load('moneda', $this->id);
        $moneda->nombre = $this->nombre;
        $this->id = R::store($moneda);
        return $this->id;
    }
    
    public function getMoneda(){
        $moneda = R::load('moneda', $this->id);
        return $moneda->export();
    }
    
    public function getMonedasUsuario(){
        $monedas = R::getCol('select moneda_id from moneda_usuario where usuario_id = ?', array($this->usuario_id));
        return $monedas;
    }
    
    public function getMonedas(){
        $monedas = R::getAll('select * from moneda order by nombre');
        return $monedas;
    }
    
    public function delMoneda(){
        $moneda = R::load('moneda', $this->id);
        R::trash($moneda);
    }
    
    public function assocUsuario(){
        $moneda = R::load('moneda', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($moneda, $usuario);
    }
    
    public function clearRelMenu(){
        $moneda = R::load('moneda', $this->id);
        R::clearRelations($moneda, 'menu');
    }
    
    public function assocMenu(){
        $moneda = R::load('moneda', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($moneda, $menu);
    }
    
    public function clearRelControlador(){
        $moneda = R::load('moneda', $this->id);
        R::clearRelations($moneda, 'controlador');
    }
    
    public function assocControlador(){
        $moneda = R::load('moneda', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($moneda, $controlador);
    }
    
    
}