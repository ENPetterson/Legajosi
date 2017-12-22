<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Residencia extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    function get(){
        $this->load->model('Residencia_model');
        $this->Residencia_model->id = $this->input->post('id');
        $residencia = $this->Residencia_model->get();
        echo json_encode($residencia);
    }
    
    function save(){
        $this->load->model('Residencia_model');
        $this->Residencia_model->id = $this->input->post('id');
        $this->Residencia_model->paisResidencia = $this->input->post('paisResidencia');
        $this->Residencia_model->titular_id = $this->input->post('titular_id');
        $this->Residencia_model->idTributaria = $this->input->post('idTributaria');
        $resultado = $this->Residencia_model->save();
        echo json_encode($resultado);
    }
    
    function del(){
        $this->load->model('Residencia_model');
        $this->Residencia_model->id = $this->input->post('id');
        $resultado = $this->Residencia_model->del();
        echo json_encode($resultado);
    }
}
