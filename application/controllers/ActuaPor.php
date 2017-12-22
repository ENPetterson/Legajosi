<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ActuaPor extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function getAll(){
        $this->load->model('ActuaPor_model');
        $actuaPor = $this->ActuaPor_model->getAll();
        echo json_encode($actuaPor);
    }
}
