<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class ComoNosConocio_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function getAll(){
        $comoNosConocio = R::getAll('select * from comonosconocio order by descripcion');
        return $comoNosConocio;
    }
}
