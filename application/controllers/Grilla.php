<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Grilla  extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    
    public function grilla($tabla,$pCampos,$orden,$filtro = "1 = 1"){
        
        $campos = explode('-', $pCampos);       
        $this->load->model('Grilla_model');
        $this->Grilla_model->tabla = $tabla;
        $this->Grilla_model->campos = $campos;
        $this->Grilla_model->orden = $orden;
        $this->Grilla_model->filtro =$filtro;
        $datos = $this->Grilla_model->getGrilla();
        echo json_encode($datos);
        
    }
    
    function _remap($tabla, $parametros){
        
        if (count($parametros) == 3){
            $this->grilla($tabla, $parametros[0], $parametros[1], urldecode($parametros[2]));
        } else {
            $this->grilla($tabla, $parametros[0], $parametros[1]);
        }
    }
    
       
}