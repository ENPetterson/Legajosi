<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Residencia_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $paisResidencia;
    public $titular_id;
    public $idTributaria;
    
    public function get(){
        $residencia = R::load('residencia', $this->id);
        return $residencia->export();
    }
    
    public function save(){
        $titular = R::load('titular', $this->titular_id);
        $residencia = R::load('residencia', $this->id);
        $residencia->paisResidencia = $this->paisResidencia;
        $residencia->titular = $titular;
        $residencia->idTributaria = $this->idTributaria;
        R::store($residencia);
        
        return $residencia->export();
    }
    
    public function del(){
        $residencia = R::load('residencia', $this->id);
        R::trash($residencia);
        return array('resultado'=>'Residencia Borrada');
    }
}
