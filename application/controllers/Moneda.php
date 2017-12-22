<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Moneda extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'moneda/grilla', 
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
            'moneda/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveMoneda(){
        $this->load->model('Moneda_model');
        $this->Moneda_model->id = $this->input->post('id');
        $this->Moneda_model->nombre = $this->input->post('nombre');
        $id = $this->Moneda_model->saveMoneda();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function getMoneda(){
        $this->load->model('Moneda_model');
        $this->Moneda_model->id = $this->input->post('id');
        $moneda = $this->Moneda_model->getMoneda();
        echo json_encode($moneda);
    }
    
    function getMonedas(){
        $this->load->model('Moneda_model');
        $monedas = $this->Moneda_model->getMonedas();
        echo json_encode($monedas);
    }

    function delMoneda(){
        $this->load->model('Moneda_model');
        $this->Moneda_model->id = $this->input->post('id');
        $this->Moneda_model->delMoneda();
        echo json_encode(array('resultado'=>'Moneda borrado exitosamente'));
    }
}