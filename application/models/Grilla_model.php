<?php


class Grilla_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $tabla;
    public $campos;
    public $orden;
    public $filtro;
    
    public function getGrilla() {
        
        $sql = "select ";
        $sql .= implode(', ', $this->campos);
        $sql .= " from " . $this->tabla;
        $sql .= " where " . $this->filtro;
        $sql .= " order by " . urldecode($this->orden);
        
        $datos = R::getAll($sql);
        
        return $datos;
        
    }
    
}


