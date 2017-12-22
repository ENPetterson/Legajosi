<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Emisor extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'emisor/grilla', 
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
            'emisor/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveEmisor(){
        $this->load->model('Emisor_model');
        $this->Emisor_model->id = $this->input->post('id');
        $this->Emisor_model->nombre = $this->input->post('nombre');
        $id = $this->Emisor_model->saveEmisor();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function getEmisor(){
        $this->load->model('Emisor_model');
        $this->Emisor_model->id = $this->input->post('id');
        $emisor = $this->Emisor_model->getEmisor();
        echo json_encode($emisor);
    }
    
    function getEmisores(){
        $this->load->model('Emisor_model');
        $emisores = $this->Emisor_model->getEmisores();
        echo json_encode($emisores);
    }

    function delEmisor(){
        $this->load->model('Emisor_model');
        $this->Emisor_model->id = $this->input->post('id');
        $this->Emisor_model->delEmisor();
        echo json_encode(array('resultado'=>'Emisor borrado exitosamente'));
    }
}