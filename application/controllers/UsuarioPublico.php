<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsuarioPublico extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }

        public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'usuario/grilla', 
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
            'usuario/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveUsuario(){
        $this->load->model('Usuario_model');
        $this->Usuario_model->id = $this->input->post('id');
        $this->Usuario_model->nombreUsuario = $this->input->post('nombreUsuario');
        $this->Usuario_model->dominio = $this->input->post('dominio');
        $this->Usuario_model->nombre = $this->input->post('nombre');
        $this->Usuario_model->apellido = $this->input->post('apellido');
        $this->Usuario_model->email = $this->input->post('email');
        $this->Usuario_model->grupos = $this->input->post('grupos');
        $id = $this->Usuario_model->saveUsuario();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function delUsuario(){
        $this->load->model('Usuario_model');
        $this->Usuario_model->id = $this->input->post('id');
        $this->Usuario_model->delUsuario();
        echo json_encode(array('resultado'=>'Usuario borrado exitosamente'));
    }    
    
    function getUsuarios(){
        $this->load->model('Usuario_model');
        $usuarios = $this->Usuario_model->getUsuarios();
        echo json_encode($usuarios);
    }
    
}