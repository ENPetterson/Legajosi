<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Estado_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    
    public function get(){
        $estado = R::load('estado', $this->id);
        return $estado->export();
    }
    
    public function getAll(){
        $estados = R::getAll('select * from estado order by descripcion');
        return $estados;
    }
}
