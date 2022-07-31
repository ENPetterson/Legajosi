<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EstructuraBono extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'estructuraBono/grilla', 
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
    
    function saveEstructuraBono(){
        $this->load->model('EstructuraBono_model');
        $this->EstructuraBono_model->id = $this->input->post('id');
        
        $this->EstructuraBono_model->bono = $this->input->post('bono');
        $this->EstructuraBono_model->tipoInstrumentoImpuesto = $this->input->post('tipoInstrumentoImpuesto');
        $this->EstructuraBono_model->tipoAjuste = $this->input->post('tipoAjuste');
        $this->EstructuraBono_model->tipoInstrumento = $this->input->post('tipoInstrumento');
        $this->EstructuraBono_model->nombreConocido = $this->input->post('nombreConocido');
        $this->EstructuraBono_model->tipoEmisor = $this->input->post('tipoEmisor');
        $this->EstructuraBono_model->emisor = $this->input->post('emisor');
        $this->EstructuraBono_model->monedacobro = $this->input->post('monedacobro');
        $this->EstructuraBono_model->monedaEmision = $this->input->post('monedaEmision');
        $this->EstructuraBono_model->cerInicial = $this->input->post('cerInicial');
        $this->EstructuraBono_model->diasPreviosCer = $this->input->post('diasPreviosCer');
        $this->EstructuraBono_model->especieCaja = $this->input->post('especieCaja');
        $this->EstructuraBono_model->isin = $this->input->post('isin');
        $this->EstructuraBono_model->nombre = $this->input->post('nombre');
        $this->EstructuraBono_model->fechaEmision = $this->input->post('fechaEmision');
        $this->EstructuraBono_model->fechaVencimiento = $this->input->post('fechaVencimiento');
        $this->EstructuraBono_model->oustanding = $this->input->post('oustanding');
        $this->EstructuraBono_model->ley = $this->input->post('ley');
        $this->EstructuraBono_model->amortizacion = $this->input->post('amortizacion');
        $this->EstructuraBono_model->tipoTasa = $this->input->post('tipoTasa');
        $this->EstructuraBono_model->tipoTasaVariable = $this->input->post('tipoTasaVariable');
        $this->EstructuraBono_model->spread = $this->input->post('spread');
        $this->EstructuraBono_model->tasaMinima = $this->input->post('tasaMinima');
        $this->EstructuraBono_model->tasaMaxima = $this->input->post('tasaMaxima');
        $this->EstructuraBono_model->cuponAnual = $this->input->post('cuponAnual');
        $this->EstructuraBono_model->cantidadCuponesAnio = $this->input->post('cantidadCuponesAnio');
        $this->EstructuraBono_model->frecuenciaCobro = $this->input->post('frecuenciaCobro');
        $this->EstructuraBono_model->fechasCobroCupon = $this->input->post('fechasCobroCupon');
        $this->EstructuraBono_model->formulaCalculoInteres = $this->input->post('formulaCalculoInteres');
        $this->EstructuraBono_model->diasPreviosRecord = $this->input->post('diasPreviosRecord');
        $this->EstructuraBono_model->proximoCobroInteres = $this->input->post('proximoCobroInteres');
        $this->EstructuraBono_model->proximoCobroCapital = $this->input->post('proximoCobroCapital');
        $this->EstructuraBono_model->duration = $this->input->post('duration');
        $this->EstructuraBono_model->precioMonedaOrigen = $this->input->post('precioMonedaOrigen');
        $this->EstructuraBono_model->lastYtm = $this->input->post('lastYtm');
        $this->EstructuraBono_model->paridad = $this->input->post('paridad');
        $this->EstructuraBono_model->currentYield = $this->input->post('currentYield');
        $this->EstructuraBono_model->interesesCorridos = $this->input->post('interesesCorridos');
        $this->EstructuraBono_model->valorResidual = $this->input->post('valorResidual');
        $this->EstructuraBono_model->valorTecnico = $this->input->post('valorTecnico');
        $this->EstructuraBono_model->mDuration = $this->input->post('mDuration');
        $this->EstructuraBono_model->convexity = $this->input->post('convexity');
        $this->EstructuraBono_model->denominacionMinima = $this->input->post('denominacionMinima');
        $this->EstructuraBono_model->spreadSinTasa = $this->input->post('spreadSinTasa');
        $this->EstructuraBono_model->ultimaTna = $this->input->post('ultimaTna');
        $this->EstructuraBono_model->diasInicioCupon = $this->input->post('diasInicioCupon');
        $this->EstructuraBono_model->diasFinalCupon = $this->input->post('diasFinalCupon');
        $this->EstructuraBono_model->capitalizacionInteres = $this->input->post('capitalizacionInteres');
        $this->EstructuraBono_model->precioPesos = $this->input->post('precioPesos');  
        
        
        $this->EstructuraBono_model->especiesRelacionadas = $this->input->post('especiesRelacionadas');  
        $this->EstructuraBono_model->curva = $this->input->post('curva');  
        $this->EstructuraBono_model->variableCurva = $this->input->post('variableCurva');  
        $this->EstructuraBono_model->tnaUltimaLicitacion = $this->input->post('tnaUltimaLicitacion');  
        $this->EstructuraBono_model->diasVencimiento = $this->input->post('diasVencimiento');  
        $this->EstructuraBono_model->variableLicitacionPb = $this->input->post('variableLicitacionPb'); 
        $this->EstructuraBono_model->cuponPbiD = $this->input->post('cuponPbiD'); 
        $this->EstructuraBono_model->cuponPbiW = $this->input->post('cuponPbiW'); 
        
        
        
        $this->EstructuraBono_model->fechaActualizacion = $this->input->post('fechaActualizacion');
        
        $id = $this->EstructuraBono_model->saveEstructuraBono();
        $resultado = array('id'=>$id);
        echo json_encode($resultado);
    }
    
    function getEstructuraBono(){
        $this->load->model('EstructuraBono_model');
        $this->EstructuraBono_model->id = $this->input->post('id');
        $estructuraBono = $this->EstructuraBono_model->getEstructuraBono();
        echo json_encode($estructuraBono);
    }
    
    function getEstructuraBonos(){
        $this->load->model('EstructuraBono_model');
        $estructuraBonos = $this->EstructuraBono_model->getEstructuraBonos();
        echo json_encode($estructuraBonos);
    }
    
    function getFechaActualizacion(){
//        $estructuraBono = $this->input->post('bono');
        $this->load->model('EstructuraBono_model');
//        $this->EstructuraBono_model->bono = $bono;
        $fechaActualizacion = $this->EstructuraBono_model->getFechaActualizacion();
        echo json_encode($fechaActualizacion);
    }
    
    
    public function grillaEstructuraBono(){
      
        $bono = $this->input->post('bono');
//        $fecha = $this->input->post('fecha');
        
        $this->load->model('EstructuraBono_model');
        $this->EstructuraBono_model->bono = $bono;
//        $this->EstructuraBono_model->fecha = $fecha;
        $resultado = $this->EstructuraBono_model->grillaEstructuraBono();
        
        echo json_encode($resultado);        
    }
    
    public function grillaEstructuraBonoFecha(){
      
        
        
        $fechaActualizacion = $this->input->post('fechaActualizacion');
        
        $this->load->model('EstructuraBono_model');
        $this->EstructuraBono_model->fechaActualizacion = $fechaActualizacion;
//        $this->EstructuraBono_model->fecha = $fecha;
        $resultado = $this->EstructuraBono_model->grillaEstructuraBonoFecha();
        
        echo json_encode($resultado);        
    }
    

}