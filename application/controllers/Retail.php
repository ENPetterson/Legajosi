<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Retail extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'retail/grilla', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value);
        }
    }
    
    public function prospectos(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'retail/grillaProspectos', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value);
        }
        
    }
    
    public function grillaProspectos(){
        $this->load->model('Retail_model');
        $prospectos = $this->Retail_model->grillaProspectos();
        echo json_encode($prospectos);
    }

}