<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vista extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'vista/grilla', 
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
            'vista/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveVista(){
        $this->load->model('Vista_model');
        $this->Vista_model->id = $this->input->post('id');
        $this->Vista_model->nombre = $this->input->post('nombre');
        $id = $this->Vista_model->saveVista();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function getVista(){
        $this->load->model('Vista_model');
        $this->Vista_model->id = $this->input->post('id');
        $vista = $this->Vista_model->getVista();
        echo json_encode($vista);
    }
    
    function getVistas(){
        $this->load->model('Vista_model');
        $vistas = $this->Vista_model->getVistas();
        echo json_encode($vistas);
    }

    function delVista(){
        $this->load->model('Vista_model');
        $this->Vista_model->id = $this->input->post('id');
        $this->Vista_model->delVista();
        echo json_encode(array('resultado'=>'Vista borrada exitosamente'));
    }
    
    
}