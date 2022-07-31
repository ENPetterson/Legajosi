<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Solicitud extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'solicitud/grilla', 
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
            'solicitud/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }

    public function getSolicitudPerfilId(){
        $this->load->model('Solicitud_model');
        $this->Solicitud_model->id = $this->input->post('id');
        $solicitud = $this->Solicitud_model->getSolicitudPerfilId();
        echo json_encode($solicitud);
    }
    
    function saveSolicitud(){
        $this->load->model('Solicitud_model');
        $this->Solicitud_model->id = $this->input->post('id');
        $this->Solicitud_model->fechaPresentacion = $this->input->post('fechaPresentacion');
        $this->Solicitud_model->fechaEstado = $this->input->post('fechaEstado');
        $this->Solicitud_model->estado_id = $this->input->post('estado_id');
        $this->Solicitud_model->observaciones = $this->input->post('observaciones');
        $this->Solicitud_model->fechaActualizacion = $this->input->post('fechaActualizacion');  
              
        $this->Solicitud_model->perfiles = $this->input->post('perfiles');
        
        $resultado = $this->Solicitud_model->saveSolicitud();
        echo json_encode($resultado);
    }
    
    function getSolicitud(){
        $this->load->model('Solicitud_model');
        $this->Solicitud_model->id = $this->input->post('id');
        $solicitud = $this->Solicitud_model->getSolicitud();
        echo json_encode($solicitud);
    }

    function getSolicitudes(){
        $this->load->model('Solicitud_model');
        $solicitud = $this->Solicitud_model->getSolicitudes();
        echo json_encode($solicitud);
    }

    function getSolicitudesPerfil(){
        $this->load->model('Solicitud_model');
        $solicitudes = $this->Solicitud_model->getSolicitudesPerfil();
        echo json_encode($solicitudes);
    }
    
    function delSolicitud(){
        $this->load->model('Solicitud_model');
        $this->Solicitud_model->id = $this->input->post('id');
        $this->Solicitud_model->delSolicitud();
        echo json_encode(array('resultado'=>'Solicitud borrada exitosamente'));
    }

    public function grabarExcel(){
        
        $archivo = $this->input->post('file');
        //$cierre = $this->input->post('cierre');
        
        $this->load->model('Solicitud_model');
        $this->Solicitud_model->archivo = $archivo;
        //$this->Canje_model->cierre = $cierre;
        
        $resultado = $this->Solicitud_model->grabarExcel();
        echo json_encode($resultado);
        
    }
}