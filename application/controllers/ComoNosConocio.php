<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class ComoNosConocio extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function getAll(){
        $this->load->model('ComoNosConocio_model');
        $comoNosConocio = $this->ComoNosConocio_model->getAll();
        echo json_encode($comoNosConocio);
    }
}
