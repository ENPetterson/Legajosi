<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Derivador extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->model('Derivador_model');
        $destino = $this->Derivador_model->getDestino();
        redirect($destino);
    }
}