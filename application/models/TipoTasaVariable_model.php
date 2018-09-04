<?php

class TipoTasaVariable_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $nombre;
    public $usuario_id;
    public $menu_id;
    public $controlador_id;
    
    public function saveTipoTasaVariable(){
        $tipotasavariable = R::load('tipotasavariable', $this->id);
        $tipotasavariable->nombre = $this->nombre;
        $this->id = R::store($tipotasavariable);
        return $this->id;
    }
    
    public function getTipoTasaVariable(){
        $tipotasavariable = R::load('tipotasavariable', $this->id);
        return $tipotasavariable->export();
    }
    
    public function getTiposTasaVariableUsuario(){
        $tiposbono = R::getCol('select tipotasavariable_id from tipotasavariable_usuario where usuario_id = ?', array($this->usuario_id));
        return $tiposTasaVariable;
    }
    
    public function getTiposTasaVariable(){
        $tiposTasaVariable = R::getAll('select * from tipotasavariable order by nombre');
        return $tiposTasaVariable;
    }
    
    public function delTipoTasaVariable(){
        $tipotasavariable = R::load('tipotasavariable', $this->id);
        R::trash($tipotasavariable);
    }
    
    public function assocUsuario(){
        $tipotasavariable = R::load('tipotasavariable', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($tipotasavariable, $usuario);
    }
    
    public function clearRelMenu(){
        $tipotasavariable = R::load('tipotasavariable', $this->id);
        R::clearRelations($tipotasavariable, 'menu');
    }
    
    public function assocMenu(){
        $tipotasavariable = R::load('tipotasavariable', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($tipotasavariable, $menu);
    }
    
    public function clearRelControlador(){
        $tipotasavariable = R::load('tipotasavariable', $this->id);
        R::clearRelations($tipotasavariable, 'controlador');
    }
    
    public function assocControlador(){
        $tipotasavariable = R::load('tipotasavariable', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($tipotasavariable, $controlador);
    }
    
    
}