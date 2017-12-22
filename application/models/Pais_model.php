<?php
class Pais_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function getAll(){
        $paises = R::getAll('select * from pais order by nombre');
        return $paises;
    }
}