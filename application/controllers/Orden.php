<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orden extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'orden/grilla', 
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
            'orden/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveOrden(){
        $this->load->model('Orden_model');
        $this->Orden_model->id = $this->input->post('id');
        $this->Orden_model->fecha = $this->input->post('fecha');
        $this->Orden_model->tipo = $this->input->post('tipo');
        $this->Orden_model->descripcion = $this->input->post('descripcion');
        $this->Orden_model->observaciones = $this->input->post('observaciones');
        $this->Orden_model->usuario = $this->input->post('usuario');
        $id = $this->Orden_model->saveOrden();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function getOrden(){
        $this->load->model('Orden_model');
        $this->Orden_model->id = $this->input->post('id');
        $orden = $this->Orden_model->getOrden();
        echo json_encode($orden);
    }
    
    function getOrdenes(){
        $this->load->model('Orden_model');
        $orden = $this->Orden_model->getOrdenes();
        echo json_encode($orden);
    }

    function delOrden(){
        $this->load->model('Orden_model');
        $this->Orden_model->id = $this->input->post('id');
        $this->Orden_model->delOrden();
        echo json_encode(array('resultado'=>'Orden borrado exitosamente'));
    }
}