<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class TramiteAlta_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    
    public function get(){
        $tramiteAlta = R::load('TramiteAlta_model', $this->id);
        return $tramiteAlta->export();
    }
    
    public function getAll(){
        $tramitesAlta = R::getAll('select * from tramitealta order by descripcion');
        return $tramitesAlta;
    }
}
