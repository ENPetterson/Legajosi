<?php
class Esco extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function getComitente(){
        $numComitente = $this->input->post('numComitente');
        $this->load->model('Esco_model');
        $this->Esco_model->numComitente = $numComitente;
        $comitente = $this->Esco_model->getComitente();
        echo json_encode($comitente);
    }
}