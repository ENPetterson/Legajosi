<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TipoTasaVariable extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'tipotasavariable/grilla', 
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
            'tipotasavariable/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveTipoTasaVariable(){
        $this->load->model('TipoTasaVariable_model');
        $this->TipoTasaVariable_model->id = $this->input->post('id');
        $this->TipoTasaVariable_model->nombre = $this->input->post('nombre');
        $id = $this->TipoTasaVariable_model->saveTipoTasaVariable();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function getTipoTasaVariable(){
        $this->load->model('TipoTasaVariable_model');
        $this->TipoTasaVariable_model->id = $this->input->post('id');
        $tipotasavariable = $this->TipoTasaVariable_model->getTipoTasaVariable();
        echo json_encode($tipotasavariable);
    }
    
    function getTiposTasaVariable(){
        $this->load->model('TipoTasaVariable_model');
        $tiposTasaVariable = $this->TipoTasaVariable_model->getTiposTasaVariable();
        echo json_encode($tiposTasaVariable);
    }

    function delTipoTasaVariable(){
        $this->load->model('TipoTasaVariable_model');
        $this->TipoTasaVariable_model->id = $this->input->post('id');
        $this->TipoTasaVariable_model->delTipoTasaVariable();
        echo json_encode(array('resultado'=>'Tipo de Tasa borrado exitosamente'));
    }
}