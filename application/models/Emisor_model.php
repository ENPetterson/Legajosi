<?php
class Emisor_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $nombre;
    public $usuario_id;
    public $menu_id;
    public $controlador_id;
    
    public function saveEmisor(){
        $emisor = R::load('emisor', $this->id);
        $emisor->nombre = $this->nombre;
        $this->id = R::store($emisor);
        return $this->id;
    }
    
    public function getEmisor(){
        $emisor = R::load('emisor', $this->id);
        return $emisor->export();
    }
    
    public function getEmisoresUsuario(){
        $emisores = R::getCol('select emisor_id from emisor_usuario where usuario_id = ?', array($this->usuario_id));
        return $emisores;
    }
    
    public function getEmisores(){
        $emisores = R::getAll('select * from emisor order by nombre');
        return $emisores;
    }
    
    public function delEmisor(){
        $emisor = R::load('emisor', $this->id);
        R::trash($emisor);
    }
    
    public function assocUsuario(){
        $emisor = R::load('emisor', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($emisor, $usuario);
    }
    
    public function clearRelMenu(){
        $emisor = R::load('emisor', $this->id);
        R::clearRelations($emisor, 'menu');
    }
    
    public function assocMenu(){
        $emisor = R::load('emisor', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($emisor, $menu);
    }
    
    public function clearRelControlador(){
        $emisor = R::load('emisor', $this->id);
        R::clearRelations($emisor, 'controlador');
    }
    
    public function assocControlador(){
        $emisor = R::load('emisor', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($emisor, $controlador);
    }
    
    
}