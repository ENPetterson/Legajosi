<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Latam extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'latam/grilla', 
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
    
    function saveLatam(){
        $this->load->model('Latam_model');
        $this->Latam_model->id = $this->input->post('id');
        $this->Latam_model->instrumento = $this->input->post('instrumento');
        $this->Latam_model->coupon = $this->input->post('coupon');
        $this->Latam_model->price = $this->input->post('price');
        $this->Latam_model->yield = $this->input->post('yield');
        $this->Latam_model->ytm = $this->input->post('ytm');
        $this->Latam_model->duration = $this->input->post('duration');
        $this->Latam_model->bp = $this->input->post('bp');
        $this->Latam_model->fechaActualizacion = $this->input->post('fechaActualizacion');
        
        $id = $this->Latam_model->saveLatam();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    
    function getFechaActualizacion(){
        $this->load->model('Latam_model');
        $fechaActualizacion = $this->Latam_model->getFechaActualizacion();
        echo json_encode($fechaActualizacion);
    }
    
    public function grillaLatamFecha(){

        $fechaActualizacion = $this->input->post('fechaActualizacion');
        
        $this->load->model('Latam_model');
        $this->Latam_model->fechaActualizacion = $fechaActualizacion;
        $resultado = $this->Latam_model->grillaLatamFecha();
        
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