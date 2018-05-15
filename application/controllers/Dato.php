<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dato extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'dato/grilla', 
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
            'dato/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveDato(){
        $this->load->model('Dato_model');
        
        
        $this->Dato_model->id = $this->input->post('id');
        $this->Dato_model->bono = $this->input->post('bono');
        $this->Dato_model->fecha = $this->input->post('fecha');
        $this->Dato_model->VNActualizado = $this->input->post('VNActualizado');
        $this->Dato_model->VRActualizado = $this->input->post('VRActualizado');
        $this->Dato_model->cuponAmortizacion = $this->input->post('cuponAmortizacion');
        $this->Dato_model->cuponInteres = $this->input->post('cuponInteres');
        $this->Dato_model->totalFlujo = $this->input->post('totalFlujo');
        $this->Dato_model->fechaActualizacion = $this->input->post('fechaActualizacion');
        
        
        
        $id = $this->Dato_model->saveDato();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    
    function getDatosFecha(){
        $this->load->model('Dato_model');
        $this->Dato_model->bono = $this->input->post('bono');        
        $dato = $this->Dato_model->getDato();
        echo json_encode($dato);
    }
    
    function getDatos(){
        $this->load->model('Dato_model');
        $datos = $this->Dato_model->getDatos();
        echo json_encode($datos);
    }
 
    
    public function delDatos(){
        $datos = $this->input->post('datos');
        $this->load->model('Dato_model');
        $this->Dato_model->datos = $datos;
        $this->Dato_model->delDatos();
        echo json_encode(array('resultado'=>'Datos borrados exitosamente'));
    }
    
    

}