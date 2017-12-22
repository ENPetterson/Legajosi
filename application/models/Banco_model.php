<?php
class Banco_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function getAll(){
        $bancos = R::getAll('select * from banco order by nombre');
        return $bancos;
    }
}