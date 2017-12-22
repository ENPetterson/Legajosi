<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class ActuaPor_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public function getAll(){
        $actuaPor = R::getAll('select * from actuapor order by descripcion');
        return $actuaPor;
    }
}
