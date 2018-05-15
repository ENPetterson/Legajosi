<?php

class Dato_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $nombre;
    public $usuario_id;
    public $menu_id;
    public $controlador_id;
    
    public function saveDato(){

        $dato = R::load('dato', $this->id);
        $dato->bono = $this->bono;
        $dato->fecha = $this->fecha;
        $dato->VNActualizado = $this->VNActualizado;
        $dato->VRActualizado = $this->VRActualizado;
        $dato->cuponAmortizacion = $this->cuponAmortizacion;        
        $dato->cuponInteres = $this->cuponInteres;
        $dato->totalFlujo = $this->totalFlujo;
        $dato->fechaActualizacion = $this->fechaActualizacion;

        $this->id = R::store($dato);
        return $this->id;
    }
    
    public function getDatosFecha(){
        $dato = R::getAll('SELECT * FROM dato WHERE bono = ? AND fechaActualizacion = ?', array($this->bono, $this->fechaActualizacion));
        return $dato;
    }
    
    
    public function getDatos(){
        $datos = R::getAll('select * from dato order by bono');
        return $datos;
    }
    
    public function delDatos(){
        foreach ($this->datos as $id){            
            $dato = R::load('dato', $id['id']);
            R::trash($dato);
        }        
    }
    
    public function assocUsuario(){
        $dato = R::load('dato', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($dato, $usuario);
    }
    
    public function clearRelMenu(){
        $dato = R::load('dato', $this->id);
        R::clearRelations($dato, 'menu');
    }
    
    public function assocMenu(){
        $dato = R::load('dato', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($dato, $menu);
    }
    
    public function clearRelControlador(){
        $dato = R::load('dato', $this->id);
        R::clearRelations($dato, 'controlador');
    }
    
    public function assocControlador(){
        $dato = R::load('dato', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($dato, $controlador);
    }
    
    
}