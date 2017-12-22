<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Estado extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function get(){
        $this->load->model('Estado_model');
        $this->Estado_model->id = $this->input->post('id');
        $estado = $this->Estado_model->get();
        echo json_encode($estado);
    }
    
    public function getAll(){
        $this->load->model('Estado_model');
        $estados = $this->Estado_model->getAll();
        echo json_encode($estados);
    }
}
