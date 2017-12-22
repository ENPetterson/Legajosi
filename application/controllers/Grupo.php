<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grupo extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'grupo/grilla', 
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
            'grupo/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveGrupo(){
        $this->load->model('Grupo_model');
        $this->Grupo_model->id = $this->input->post('id');
        $this->Grupo_model->nombre = $this->input->post('nombre');
        $id = $this->Grupo_model->saveGrupo();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function getGrupo(){
        $this->load->model('Grupo_model');
        $this->Grupo_model->id = $this->input->post('id');
        $grupo = $this->Grupo_model->getGrupo();
        echo json_encode($grupo);
    }
    
    function getGrupos(){
        $this->load->model('Grupo_model');
        $grupos = $this->Grupo_model->getGrupos();
        echo json_encode($grupos);
    }

    function delGrupo(){
        $this->load->model('Grupo_model');
        $this->Grupo_model->id = $this->input->post('id');
        $this->Grupo_model->delGrupo();
        echo json_encode(array('resultado'=>'Grupo borrado exitosamente'));
    }
}