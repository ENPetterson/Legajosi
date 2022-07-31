<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Experiencialaboral extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'experiencialaboral/grilla', 
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
            'experiencialaboral/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveExperiencialaboral(){
        $this->load->model('Experiencialaboral_model');
        $this->Experiencialaboral_model->id = $this->input->post('id');
        $this->Experiencialaboral_model->experiencia = $this->input->post('experiencia');
        $this->Experiencialaboral_model->empresa = $this->input->post('empresa');
        $this->Experiencialaboral_model->legajo_id = $this->input->post('legajo_id');
        $this->Experiencialaboral_model->fechaInicio = $this->input->post('fechaInicio');
        $this->Experiencialaboral_model->fechaSalida = $this->input->post('fechaSalida');
        $this->Experiencialaboral_model->montoMensual = $this->input->post('montoMensual');
        $this->Experiencialaboral_model->dependencia = $this->input->post('dependencia');
        $this->Experiencialaboral_model->funciones = $this->input->post('funciones');                             
        $id = $this->Experiencialaboral_model->saveExperiencialaboral();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }

    
    function getExperiencialaboral(){
        $this->load->model('Experiencialaboral_model');
        $this->Experiencialaboral_model->id = $this->input->post('id');
        $experiencialaboral = $this->Experiencialaboral_model->getExperiencialaboral();
        echo json_encode($experiencialaboral);
    }

    function getExperienciaslaborales(){
        $this->load->model('Experiencialaboral_model');
        $experiencialaboral = $this->Experiencialaboral_model->getExperienciaslaborales();
        echo json_encode($experiencialaboral);
    }

    function delExperiencialaboral(){
        $this->load->model('Experiencialaboral_model');
        $this->Experiencialaboral_model->id = $this->input->post('id');
        $this->Experiencialaboral_model->delExperiencialaboral();
        echo json_encode(array('resultado'=>'Experiencia laboral borrada exitosamente'));
    }


    public function grabarExcel(){
        
        $archivo = $this->input->post('file');
        //$cierre = $this->input->post('cierre');
        
        $this->load->model('Experiencialaboral_model');
        $this->Experiencialaboral_model->archivo = $archivo;
        //$this->Canje_model->cierre = $cierre;
        
        $resultado = $this->Experiencialaboral_model->grabarExcel();
        echo json_encode($resultado);
        
    }
}