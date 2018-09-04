<?php

class TipoTasa_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $nombre;
    public $usuario_id;
    public $menu_id;
    public $controlador_id;
    
    public function saveTipoTasa(){
        $tipotasa = R::load('tipotasa', $this->id);
        $tipotasa->nombre = $this->nombre;
        $this->id = R::store($tipotasa);
        return $this->id;
    }
    
    public function getTipoTasa(){
        $tipotasa = R::load('tipotasa', $this->id);
        return $tipotasa->export();
    }
    
    public function getTiposTasaUsuario(){
        $tiposbono = R::getCol('select tipotasa_id from tipotasa_usuario where usuario_id = ?', array($this->usuario_id));
        return $tiposTasa;
    }
    
    public function getTiposTasa(){
        $tiposTasa = R::getAll('select * from tipotasa order by nombre');
        return $tiposTasa;
    }
    
    public function delTipoTasa(){
        $tipotasa = R::load('tipotasa', $this->id);
        R::trash($tipotasa);
    }
    
    public function assocUsuario(){
        $tipotasa = R::load('tipotasa', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($tipotasa, $usuario);
    }
    
    public function clearRelMenu(){
        $tipotasa = R::load('tipotasa', $this->id);
        R::clearRelations($tipotasa, 'menu');
    }
    
    public function assocMenu(){
        $tipotasa = R::load('tipotasa', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($tipotasa, $menu);
    }
    
    public function clearRelControlador(){
        $tipotasa = R::load('tipotasa', $this->id);
        R::clearRelations($tipotasa, 'controlador');
    }
    
    public function assocControlador(){
        $tipotasa = R::load('tipotasa', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($tipotasa, $controlador);
    }
    
    
}