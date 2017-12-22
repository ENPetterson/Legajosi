<?php
class Banco extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }
    
    public function getAll(){
        $this->load->model('Banco_model');
        $bancos = $this->Banco_model->getAll();
        echo json_encode($bancos);
    }
}