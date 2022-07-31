<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gatito extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'gatito/grilla', 
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
            'gatito/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveGatito(){
        $this->load->model('Gatito_model');
        $this->Gatito_model->id = $this->input->post('id');
        $this->Gatito_model->nombre = $this->input->post('nombre');
        $this->Gatito_model->apellido = $this->input->post('apellido');
        $this->Gatito_model->domicilio = $this->input->post('domicilio');
        $id = $this->Gatito_model->saveGatito();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function getGatito(){
        $this->load->model('Gatito_model');
        $this->Gatito_model->id = $this->input->post('id');
        $gatito = $this->Gatito_model->getGatito();
        echo json_encode($gatito);
    }
    
    function getGatitos(){
        $this->load->model('Gatito_model');
        $gatito = $this->Gatito_model->getGatitos();
        echo json_encode($gatito);
    }

    function delGatito(){
        $this->load->model('Gatito_model');
        $this->Gatito_model->id = $this->input->post('id');
        $this->Gatito_model->delGatito();
        echo json_encode(array('resultado'=>'Gatito borrado exitosamente'));
    }
}