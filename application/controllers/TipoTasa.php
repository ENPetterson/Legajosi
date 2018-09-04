<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TipoTasa extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'tipotasa/grilla', 
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
            'tipotasa/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveTipoTasa(){
        $this->load->model('TipoTasa_model');
        $this->TipoTasa_model->id = $this->input->post('id');
        $this->TipoTasa_model->nombre = $this->input->post('nombre');
        $id = $this->TipoTasa_model->saveTipoTasa();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function getTipoTasa(){
        $this->load->model('TipoTasa_model');
        $this->TipoTasa_model->id = $this->input->post('id');
        $tipotasa = $this->TipoTasa_model->getTipoTasa();
        echo json_encode($tipotasa);
    }
    
    function getTiposTasa(){
        $this->load->model('TipoTasa_model');
        $tiposTasa = $this->TipoTasa_model->getTiposTasa();
        echo json_encode($tiposTasa);
    }

    function delTipoTasa(){
        $this->load->model('TipoTasa_model');
        $this->TipoTasa_model->id = $this->input->post('id');
        $this->TipoTasa_model->delTipoTasa();
        echo json_encode(array('resultado'=>'Tipo de Tasa borrado exitosamente'));
    }
}