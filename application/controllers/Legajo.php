<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Legajo extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'legajo/grilla', 
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
            'legajo/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }

    public function getLegajoExperiencialaboralId(){
        $this->load->model('Legajo_model');
        $this->Legajo_model->id = $this->input->post('id');
        $legajo = $this->Legajo_model->getLegajoExperiencialaboralId();
        echo json_encode($legajo);
    }
    
    function saveLegajo(){
        $this->load->model('Legajo_model');
        $this->Legajo_model->id = $this->input->post('id');
        $this->Legajo_model->nombre = $this->input->post('nombre');
        $this->Legajo_model->apellido = $this->input->post('apellido');
        $this->Legajo_model->fechaNacimiento = $this->input->post('fechaNacimiento');
        $this->Legajo_model->tipoDocumento = $this->input->post('tipoDocumento');
        $this->Legajo_model->cuil = $this->input->post('cuil');  
        $this->Legajo_model->nacionalidad = $this->input->post('nacionalidad');
        $this->Legajo_model->estadoCivil = $this->input->post('estadoCivil');
        $this->Legajo_model->esDiscapacitado = $this->input->post('esDiscapacitado');
        $this->Legajo_model->email = $this->input->post('email');
        $this->Legajo_model->sexo = $this->input->post('sexo');
        $this->Legajo_model->cargo = $this->input->post('cargo');
        $this->Legajo_model->fechaIngreso = $this->input->post('fechaIngreso');
        $this->Legajo_model->fechaEgreso = $this->input->post('fechaEgreso');   
        $this->Legajo_model->fechaAntiguedad = $this->input->post('fechaAntiguedad');   
        $this->Legajo_model->diasVacaciones = $this->input->post('diasVacaciones');   
        $this->Legajo_model->sueldoBasico = $this->input->post('sueldoBasico');   
        $this->Legajo_model->observaciones = $this->input->post('observaciones');                   
              
        $this->Legajo_model->experienciaslaborales = $this->input->post('experienciaslaborales');
        
        $resultado = $this->Legajo_model->saveLegajo();
        echo json_encode($resultado);
    }
    
    function getLegajo(){
        $this->load->model('Legajo_model');
        $this->Legajo_model->id = $this->input->post('id');
        $legajo = $this->Legajo_model->getLegajo();
        echo json_encode($Legajo);
    }

    function getLegajos(){
        $this->load->model('Legajo_model');
        $legajo = $this->Legajo_model->getLegajos();
        echo json_encode($legajo);
    }

    function getLegajosExperiencialaboral(){
        $this->load->model('Legajo_model');
        $legajos = $this->Legajo_model->getLegajosExperiencialaboral();
        echo json_encode($legajos);
    }
    
    function delLegajo(){
        $this->load->model('Legajo_model');
        $this->Legajo_model->id = $this->input->post('id');
        $this->Legajo_model->delLegajo();
        echo json_encode(array('resultado'=>'Legajo borrado exitosamente'));
    }

    public function grabarExcel(){
        
        $archivo = $this->input->post('file');
        //$cierre = $this->input->post('cierre');
        
        $this->load->model('Legajo_model');
        $this->Legajo_model->archivo = $archivo;
        //$this->Canje_model->cierre = $cierre;
        
        $resultado = $this->Legajo_model->grabarExcel();
        echo json_encode($resultado);
        
    }
}