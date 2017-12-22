<?php

class TipoBono_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $nombre;
    public $usuario_id;
    public $menu_id;
    public $controlador_id;
    
    public function saveTipoBono(){
        $tipoBono = R::load('tipobono', $this->id);
        $tipoBono->nombre = $this->nombre;
        $this->id = R::store($tipoBono);
        return $this->id;
    }
    
    public function getTipoBono(){
        $tipoBono = R::load('tipobono', $this->id);
        return $tipoBono->export();
    }
    
    public function getTiposBonoUsuario(){
        $tiposbono = R::getCol('select tipobono_id from tipobono_usuario where usuario_id = ?', array($this->usuario_id));
        return $tiposBono;
    }
    
    public function getTiposBono(){
        $tiposBono = R::getAll('select * from tipobono order by nombre');
        return $tiposBono;
    }
    
    public function delTipoBono(){
        $tipoBono = R::load('tipobono', $this->id);
        R::trash($tipoBono);
    }
    
    public function assocUsuario(){
        $tipoBono = R::load('tipobono', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($tipoBono, $usuario);
    }
    
    public function clearRelMenu(){
        $tipoBono = R::load('tipobono', $this->id);
        R::clearRelations($tipoBono, 'menu');
    }
    
    public function assocMenu(){
        $tipoBono = R::load('tipobono', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($tipoBono, $menu);
    }
    
    public function clearRelControlador(){
        $tipoBono = R::load('tipobono', $this->id);
        R::clearRelations($tipoBono, 'controlador');
    }
    
    public function assocControlador(){
        $tipoBono = R::load('tipobono', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($tipoBono, $controlador);
    }
    
    
}