<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'cliente/grilla', 
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
            'cliente/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveCliente(){
        $this->load->model('Cliente_model');
        $this->Cliente_model->id = $this->input->post('id');
        $this->Cliente_model->nombre = $this->input->post('nombre');
        $this->Cliente_model->apellido = $this->input->post('apellido');
        $this->Cliente_model->direccion = $this->input->post('direccion');
        $this->Cliente_model->email = $this->input->post('email');
        $id = $this->Cliente_model->saveCliente();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function getCliente(){
        $this->load->model('Cliente_model');
        $this->Cliente_model->id = $this->input->post('id');
        $cliente = $this->Cliente_model->getCliente();
        echo json_encode($cliente);
    }
    
    function getClientes(){
        $this->load->model('Cliente_model');
        $cliente = $this->Cliente_model->getClientes();
        echo json_encode($cliente);
    }

    function delCliente(){
        $this->load->model('Cliente_model');
        $this->Cliente_model->id = $this->input->post('id');
        $this->Cliente_model->delCliente();
        echo json_encode(array('resultado'=>'Cliente borrado exitosamente'));
    }
}