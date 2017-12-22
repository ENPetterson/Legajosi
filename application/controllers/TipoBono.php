<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TipoBono extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'tipobono/grilla', 
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
            'tipobono/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    function saveTipoBono(){
        $this->load->model('TipoBono_model');
        $this->TipoBono_model->id = $this->input->post('id');
        $this->TipoBono_model->nombre = $this->input->post('nombre');
        $id = $this->TipoBono_model->saveTipoBono();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function getTipoBono(){
        $this->load->model('TipoBono_model');
        $this->TipoBono_model->id = $this->input->post('id');
        $tipoBono = $this->TipoBono_model->getTipoBono();
        echo json_encode($tipoBono);
    }
    
    function getTiposBono(){
        $this->load->model('TipoBono_model');
        $tiposBono = $this->TipoBono_model->getTiposBono();
        echo json_encode($tiposBono);
    }

    function delTipoBono(){
        $this->load->model('TipoBono_model');
        $this->TipoBono_model->id = $this->input->post('id');
        $this->TipoBono_model->delTipoBono();
        echo json_encode(array('resultado'=>'Tipo de Bono borrado exitosamente'));
    }
}