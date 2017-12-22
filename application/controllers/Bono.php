<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bono extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'bono/grilla', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value);
        }
    }
    
    public function editar(){
        $datos['id'] = $this->input->post('id');
        $views = array(
            'template/encabezado', 
            'template/menu',
            'bono/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveBono(){
        $this->load->model('Bono_model');
        $this->Bono_model->id = $this->input->post('id');
        $this->Bono_model->nombre = $this->input->post('nombre');
        $this->Bono_model->emisor_id = $this->input->post('emisor_id');
        $this->Bono_model->tipobono_id = $this->input->post('tipobono_id');
        $this->Bono_model->codigocaja = $this->input->post('codigocaja');
        $this->Bono_model->codigoisin = $this->input->post('codigoisin');
        
        $this->Bono_model->monedacobro = $this->input->post('monedacobro');
        $this->Bono_model->monedabono = $this->input->post('monedabono');
        $this->Bono_model->tipotasa = $this->input->post('tipotasa');
        $this->Bono_model->tipotasavariable  = $this->input->post('tipotasavariable');
        $this->Bono_model->cer = $this->input->post('cer');
        $this->Bono_model->cupon = $this->input->post('cupon');
        $this->Bono_model->cantidadcuponanual = $this->input->post('cantidadcuponanual');
        $this->Bono_model->vencimiento = $this->input->post('vencimiento');
        $this->Bono_model->capitalresidual = $this->input->post('capitalresidual');
        $this->Bono_model->ultimoprecio = $this->input->post('ultimoprecio');
        $this->Bono_model->oustanding = $this->input->post('oustanding');
        $this->Bono_model->proximointeres = $this->input->post('proximointeres');
        $this->Bono_model->proximoamortizacion = $this->input->post('proximoamortizacion');   
        $this->Bono_model->legislacion = $this->input->post('legislacion');     
        $this->Bono_model->denominacionminima = $this->input->post('denominacionminima');
        $this->Bono_model->libro = $this->input->post('libro');
        $this->Bono_model->hoja = $this->input->post('hoja');
        
        $id = $this->Bono_model->saveBono();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function getBono(){
        $this->load->model('Bono_model');
        $this->Bono_model->id = $this->input->post('id');
        $bono = $this->Bono_model->getBono();
        echo json_encode($bono);
    }
    
    function getBonos(){
        $this->load->model('Bono_model');
        $bonos = $this->Bono_model->getBonos();
        echo json_encode($bonos);
    }
 
    function getCodigoCaja(){
        $this->load->model('Bono_model');
        $this->Bono_model->bono = $this->input->post('bono');
        $bonos = $this->Bono_model->getCodigoCaja();
        echo json_encode($bonos);
    }
    
    function getAll(){
        $this->load->model('Bono_model');
        $this->Bono_model->buscador = $this->input->post('buscador');
        $this->Bono_model->emisor_id = $this->input->post('emisor_id');
        $this->Bono_model->tipobono_id = $this->input->post('tipobono_id');
        $bonos = $this->Bono_model->getAll();
        echo json_encode($bonos);
    }
    
    function getSeleccionados(){
        $this->load->model('Bono_model');
        $bonos = $this->Bono_model->getSeleccionados();
        echo json_encode($bonos);
    }

    function delBono(){
        $this->load->model('Bono_model');
        $this->Bono_model->id = $this->input->post('id');
        $this->Bono_model->delBono();
        echo json_encode(array('resultado'=>'Bono borrado exitosamente'));
    }
}