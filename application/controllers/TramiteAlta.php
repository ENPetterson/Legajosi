<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class TramiteAlta extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function get(){
        $this->load->model('TramiteAlta_model');
        $this->TramiteAlta_model->id = $this->input->post('id');
        $tramiteAlta = $this->TramiteAlta_model->get();
        echo json_encode($tramiteAlta);
    }
    
    public function getAll(){
        $this->load->model('TramiteAlta_model');
        $tramitesAlta = $this->TramiteAlta_model->getAll();
        echo json_encode($tramitesAlta);
    }
}
