<?php

class Orden_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $fecha;
    public $tipo;
    public $descripcion;
    public $observaciones;  
    public $usuario;    
    public $usuario_id;
    public $menu_id;
    public $controlador_id;
    
    public function saveOrden(){
        $orden = R::load('orden', $this->id);
        $orden->fecha = $this->fecha;
        $orden->tipo = $this->tipo;
        $orden->descripcion = $this->descripcion;
        $orden->observaciones = $this->observaciones;
        $orden->usuario = $this->usuario;
        $this->id = R::store($orden);
        return $this->id;
    }
    
    public function getOrden(){
        $orden = R::load('orden', $this->id);
        return $orden->export();
    }
    
    public function getOrdenesUsuario(){
        $ordenes = R::getCol('select orden_id from orden_usuario where usuario_id = ?', array($this->usuario_id));
        return $ordenes;
    }
    
    public function getOrdenes(){
        $ordenes = R::getAll('select * from orden order by fecha');
        return $ordenes;
    }
    
    public function delOrden(){
        $orden = R::load('orden', $this->id);
        R::trash($orden);
    }
    
    public function assocUsuario(){
        $orden = R::load('orden', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($orden, $usuario);
    }
    
    public function clearRelMenu(){
        $orden = R::load('orden', $this->id);
        R::clearRelations($orden, 'menu');
    }
    
    public function assocMenu(){
        $orden = R::load('orden', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($orden, $menu);
    }
    
    public function clearRelControlador(){
        $orden = R::load('orden', $this->id);
        R::clearRelations($orden, 'controlador');
    }
    
    public function assocControlador(){
        $orden = R::load('orden', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($orden, $controlador);
    }
    
    
}