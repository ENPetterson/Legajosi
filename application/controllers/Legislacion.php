<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Legislacion extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'legislacion/grilla', 
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
            'legislacion/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveLegislacion(){
        $this->load->model('Legislacion_model');
        $this->Legislacion_model->id = $this->input->post('id');
        $this->Legislacion_model->nombre = $this->input->post('nombre');
        $id = $this->Legislacion_model->saveLegislacion();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function getLegislacion(){
        $this->load->model('Legislacion_model');
        $this->Legislacion_model->id = $this->input->post('id');
        $legislacion = $this->Legislacion_model->getLegislacion();
        echo json_encode($legislacion);
    }
    
    function getTiposLegislacion(){
        $this->load->model('Legislacion_model');
        $tiposLegislacion = $this->Legislacion_model->getTiposLegislacion();
        echo json_encode($tiposLegislacion);
    }

    function delLegislacion(){
        $this->load->model('Legislacion_model');
        $this->Legislacion_model->id = $this->input->post('id');
        $this->Legislacion_model->delLegislacion();
        echo json_encode(array('resultado'=>'Tipo de Legislacion borrado exitosamente'));
    }
}