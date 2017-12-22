<?php
class Pais extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function getAll(){
        $this->load->model('Pais_model');
        $paises = $this->Pais_model->getAll();
        echo json_encode($paises);
    }
}