<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controlador extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'controlador/grilla', 
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
            'controlador/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveControlador(){
        $this->load->model('Controlador_model');
        $this->Controlador_model->id = $this->input->post('id');
        $this->Controlador_model->nombre = $this->input->post('nombre');
        $id = $this->Controlador_model->saveControlador();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function getControlador(){
        $this->load->model('Controlador_model');
        $this->Controlador_model->id = $this->input->post('id');
        $controlador = $this->Controlador_model->getControlador();
        echo json_encode($controlador);
    }
    
    function getAllControladores(){
        $this->load->model('Controlador_model');
        $controladores = $this->Controlador_model->getAllControladores();
        echo json_encode($controladores);
    }

    function delControlador(){
        $this->load->model('Controlador_model');
        $this->Controlador_model->id = $this->input->post('id');
        $this->Controlador_model->delControlador();
        echo json_encode(array('resultado'=>'Controlador borrado exitosamente'));
    }
    
}