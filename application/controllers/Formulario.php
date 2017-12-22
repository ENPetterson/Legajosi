<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Formulario extends MY_AuthController {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'formulario/grilla', 
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
            'formulario/editar', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value, $datos);
        }
    }
    
    public function get(){
        $this->load->model('Formulario_model');
        $this->Formulario_model->id = $this->input->post('id');
        $formulario = $this->Formulario_model->get();
        echo json_encode($formulario);
    }
    
    public function getRapido(){
        $this->load->model('Formulario_model');
        $this->Formulario_model->id = $this->input->post('id');
        $formulario = $this->Formulario_model->getRapido();
        echo json_encode($formulario);
    }
    
    public function saveRapido(){
        $this->load->model('Formulario_model');
        $this->Formulario_model->id = $this->input->post('id');
        $this->Formulario_model->estado_id = $this->input->post('estado_id');
        $this->Formulario_model->tramitealta_id = $this->input->post('tramitealta_id');
        $this->Formulario_model->observaciones = $this->input->post('observaciones');
        $this->Formulario_model->numComitente = $this->input->post('numComitente');
        $resultado = $this->Formulario_model->saveRapido();
        echo json_encode($resultado);        
    }
    
    public function save(){
        $this->load->model('Formulario_model');
        $this->Formulario_model->id = $this->input->post('id');
        $this->Formulario_model->fechaPresentacion = $this->input->post('fechaPresentacion');
        $this->Formulario_model->estado_id = $this->input->post('estado_id');
        $this->Formulario_model->tramitealta_id = $this->input->post('tramitealta_id');
        $this->Formulario_model->observaciones = $this->input->post('observaciones');
        /*
        $this->Formulario_model->actuaPor = $this->input->post('actuaPor');
        $this->Formulario_model->esBeneficiarioFinal = $this->input->post('esBeneficiarioFinal');
        $this->Formulario_model->beneficiarioFinal = $this->input->post('beneficiarioFinal');
         */
        $this->Formulario_model->comoNosConocio = $this->input->post('comoNosConocio');
        $this->Formulario_model->contacto = $this->input->post('contacto');
        $this->Formulario_model->comentarios = $this->input->post('comentarios');
        $this->Formulario_model->numComitente = $this->input->post('numComitente');
        $this->Formulario_model->toleranciaRiesgo = $this->input->post('toleranciaRiesgo');
        $this->Formulario_model->perfilCuenta = $this->input->post('perfilCuenta');
        if ($this->input->post('asociarCuenta') == 'S'){
            $this->Formulario_model->asociarCuenta = 'S';
            $this->Formulario_model->banco = $this->input->post('banco');
            $this->Formulario_model->tipoCuentaBanco = $this->input->post('tipoCuentaBanco');
            $this->Formulario_model->numeroCuenta = $this->input->post('numeroCuenta');
            $this->Formulario_model->moneda = $this->input->post('moneda');
            $this->Formulario_model->titular = $this->input->post('titular');
            $this->Formulario_model->cbu = $this->input->post('cbu');
            $this->Formulario_model->cuitCuenta = $this->input->post('cuitCuenta');
        } else {
            $this->Formulario_model->asociarCuenta = 'N';
        }
        
        
        $this->Formulario_model->oficial = $this->input->post('oficial');
        $this->Formulario_model->administrador = $this->input->post('administrador');
        $this->Formulario_model->terceroNoIntermediario = $this->input->post('terceroNoIntermediario');
        $this->Formulario_model->dniTerceroNoIntermediario = $this->input->post('dniTerceroNoIntermediario');
        $this->Formulario_model->emailTerceroNoInscripto = $this->input->post('emailTerceroNoInscripto');
        $this->Formulario_model->numeroProductor = $this->input->post('numeroProductor');
        $this->Formulario_model->responsable_id = $this->input->post('responsable_id');
        $this->Formulario_model->titulares = $this->input->post('titulares');
        
        $resultado = $this->Formulario_model->save();
        echo json_encode($resultado);
    }
    
    public function getFicha(){
        $this->load->model('Formulario_model');
        $this->Formulario_model->id = $this->input->post('id');
        $ficha = $this->Formulario_model->getFicha();
        echo json_encode($ficha);
        
    }
    
    public function getResponsables(){
        $this->load->model('Usuario_model');
        $operadores = $this->Usuario_model->getResponsables();
        echo json_encode($operadores);
    }
    
    public function getOficiales(){
        $this->load->model('Formulario_model');
        $oficiales = $this->Formulario_model->getOficiales();
        echo json_encode($oficiales);
    }
    
    public function getTercerosNoIntermediarios(){
        $this->load->model('Formulario_model');
        $tercerosNoIntermediarios = $this->Formulario_model->getTercerosNoIntermediarios();
        echo json_encode($tercerosNoIntermediarios);
    }
    
    public function getDatosTercero(){
        $this->load->model('Formulario_model');
        $this->Formulario_model->terceroNoIntermediario = $this->input->post('terceroNoIntermediario');
        $datosTercero = $this->Formulario_model->getDatosTercero();
        echo json_encode($datosTercero);
    }
    
    public function getAdministradores(){
        $this->load->model('Formulario_model');
        $administradores = $this->Formulario_model->getAdministradores();
        echo json_encode($administradores);
    }
    
    public function verificarEmail(){
        $this->load->model('Formulario_model');
        $this->Formulario_model->titular_id = $this->input->post('titular_id');
        $this->Formulario_model->numeroEmail = $this->input->post('numeroEmail');
        $resultado = $this->Formulario_model->verificarEmail();
        echo json_encode($resultado);
    }
    
    public function del(){
        $this->load->model('Formulario_model');
        $this->Formulario_model->id = $this->input->post('id');
        $resultado = $this->Formulario_model->del();
        echo json_encode($resultado);
    }
    
    public function getAdjunto(){
        $this->load->helper('download');
        $this->load->model('Formulario_model');
        $this->Formulario_model->id = $this->input->post('id');
        $adjunto = $this->Formulario_model->getAdjunto();
        force_download($adjunto['filename'], base64_decode($adjunto['contenido']));
    }
    

    
}