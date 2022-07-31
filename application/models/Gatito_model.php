<?php

class Gatito_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $nombre;
    public $apellido;
    public $domicilio;   
    public $usuario_id;
    public $menu_id;
    public $controlador_id;
    
    public function saveGatito(){
        $gatito = R::load('gatito', $this->id);
        $gatito->nombre = $this->nombre;
        $gatito->apellido = $this->apellido;
        $gatito->domicilio = $this->domicilio;
        $this->id = R::store($gatito);
        return $this->id;
    }
    
    public function getGatito(){
        $gatito = R::load('gatito', $this->id);
        return $gatito->export();
    }
    
    public function getGatitosUsuario(){
        $gatitos = R::getCol('select gatito_id from gatito_usuario where usuario_id = ?', array($this->usuario_id));
        return $gatitos;
    }
    
    public function getGatitos(){
        $gatitos = R::getAll('select * from gatito order by nombre');
        return $gatitos;
    }
    
    public function delGatito(){
        $gatito = R::load('gatito', $this->id);
        R::trash($gatito);
    }
    
    public function assocUsuario(){
        $gatito = R::load('gatito', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($gatito, $usuario);
    }
    
    public function clearRelMenu(){
        $gatito = R::load('gatito', $this->id);
        R::clearRelations($gatito, 'menu');
    }
    
    public function assocMenu(){
        $gatito = R::load('gatito', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($gatito, $menu);
    }
    
    public function clearRelControlador(){
        $gatito = R::load('gatito', $this->id);
        R::clearRelations($gatito, 'controlador');
    }
    
    public function assocControlador(){
        $gatito = R::load('gatito', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($gatito, $controlador);
    }
    
    
}