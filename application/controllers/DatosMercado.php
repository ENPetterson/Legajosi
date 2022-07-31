<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DatosMercado extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'datosMercado/grilla', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value);
        }
    }
//    
//    public function editar(){
//        $datos['id'] = $this->input->post('id');
//        $views = array(
//            'template/encabezado', 
//            'template/menu',
//            'bono/editar', 
//            'template/pie'
//            );
//        foreach ($views as $value) {
//            $this->load->view($value, $datos);
//        }
//    }
    
    function saveDatosMercado(){
        $this->load->model('DatosMercado_model');
        $this->DatosMercado_model->id = $this->input->post('id');
        $this->DatosMercado_model->nombre = $this->input->post('nombre');
        $this->DatosMercado_model->input = $this->input->post('input');
        $this->DatosMercado_model->fechaActualizacion = $this->input->post('fechaActualizacion');
        
        $id = $this->DatosMercado_model->saveDatosMercado();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    
    function getFechaActualizacion(){
//        $estructuraBono = $this->input->post('bono');
        $this->load->model('DatosMercado_model');
//        $this->EstructuraBono_model->bono = $bono;
        $fechaActualizacion = $this->DatosMercado_model->getFechaActualizacion();
        echo json_encode($fechaActualizacion);
    }
    
    
    
    
    public function grillaDatosMercadoFecha(){    
        
        $fechaActualizacion = $this->input->post('fechaActualizacion');
        
        $this->load->model('DatosMercado_model');
        $this->DatosMercado_model->fechaActualizacion = $fechaActualizacion;
//        $this->EstructuraBono_model->fecha = $fecha;
        $resultado = $this->DatosMercado_model->grillaDatosMercadoFecha();
        
        echo json_encode($resultado);        
    }
    
//    function getBono(){
//        $this->load->model('Bono_model');
//        $this->Bono_model->id = $this->input->post('id');
//        $bono = $this->Bono_model->getBono();
//        echo json_encode($bono);
//    }
//    
////    function getBonoId(){
////        $this->load->model('Bono_model');
////        $this->Bono_model->bono = $this->input->post('bono');
////        $bono = $this->Bono_model->getBonoId();
////        echo json_encode($bono);
////    }
//    
//    function getBonos(){
//        $this->load->model('Bono_model');
//        $bonos = $this->Bono_model->getBonos();
//        echo json_encode($bonos);
//    }
// 
//    function getCodigoCaja(){
//        $this->load->model('Bono_model');
//        $this->Bono_model->bono = $this->input->post('bono');
//        $bonos = $this->Bono_model->getCodigoCaja();
//        echo json_encode($bonos);
//    }
//    
//    function getAll(){
//        $this->load->model('Bono_model');
//        $this->Bono_model->buscador = $this->input->post('buscador');
//        $this->Bono_model->emisor_id = $this->input->post('emisor_id');
//        $this->Bono_model->tipobono_id = $this->input->post('tipobono_id');
//        $bonos = $this->Bono_model->getAll();
//        echo json_encode($bonos);
//    }
//    
//    function getSeleccionados(){
//        $this->load->model('Bono_model');
//        $bonos = $this->Bono_model->getSeleccionados();
//        echo json_encode($bonos);
//    }
//
//    function delBono(){
//        $this->load->model('Bono_model');
//        $this->Bono_model->id = $this->input->post('id');
//        $this->Bono_model->delBono();
//        echo json_encode(array('resultado'=>'Bono borrado exitosamente'));
//    }
}