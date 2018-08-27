<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH."/third_party/PHPExcel.php";
include APPPATH."/third_party/financial_class.php";

class Flujo extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }

    
    public function index(){
                
        $views = array(
            'template/encabezado', 
            'template/menu',
            'flujo/grilla', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value);
        }
    }    
    
    
    public function grillaFlujo(){
      
        $bono = $this->input->post('bono');
        $fecha = $this->input->post('fecha');
        
//        print_r($bono);
//        print_r($fecha);
//        die;
        
        $this->load->model('Flujo_model');
        $this->Flujo_model->bono = $bono;
        $this->Flujo_model->fecha = $fecha;
        $resultado = $this->Flujo_model->grillaFlujo();
        
        echo json_encode($resultado);        
    }
    
    function getFechaActualizacion(){
        $bono = $this->input->post('bono');
        $this->load->model('Flujo_model');
        $this->Flujo_model->bono = $bono;
        $fechaActualizacion = $this->Flujo_model->getFechaActualizacion();
        echo json_encode($fechaActualizacion);
    }
    
    public function getCierreInstrumento(){
        $instrumento_id = $this->input->post('instrumento_id');
        $this->load->model('Cierre_model');
        $this->Cierre_model->instrumento_id = $instrumento_id;
        $cierre = $this->Cierre_model->getCierreInstrumento();
        echo json_encode($cierre);
    }
    
    
    public function calcularFlujo(){
        
        $bono = $this->input->post('bono');        
        $precio = $this->input->post('precio');

        $this->load->model('Flujo_model');
        $this->Flujo_model->precio = $this->input->post('precio');
        $this->Flujo_model->bono = $this->input->post('bono');
        $flujos = $this->Flujo_model->getCalcularFlujo();
        
        echo json_encode($flujos);
    }
    
    
    public function getImportarFlujos(){
        
        $this->load->model('Flujo_model');
        $flujos = $this->Flujo_model->getImportarFlujos();
        
        echo json_encode($flujos);
        
//        $bono = $this->input->post('bono');
        //$bono = 'AE22';
        //$this->load->model('Flujo_model');
        //$this->Flujo_model->bono = $bono;
        //$flujos = $this->Flujo_model->getImportarFlujos();
        //echo json_encode($flujos);
    }
    
    public function getImportarFlujosAllBonos(){
        
        $this->load->model('Flujo_model');
        $flujos = $this->Flujo_model->getImportarFlujosAllBonos();
        
        echo json_encode($flujos);
    }
    
    public function getImportarFlujosAllProvinciales(){
        
        $this->load->model('Flujo_model');
        $flujos = $this->Flujo_model->getImportarFlujosAllProvinciales();
        
        echo json_encode($flujos);
    }
    
    public function getImportarDatosAllBonos(){
        
        $this->load->model('Flujo_model');
        $flujos = $this->Flujo_model->getImportarDatosAllBonos();
        
        echo json_encode($flujos);
    }
    
    public function getImportarDatosAllProvinciales(){
        
        $this->load->model('Flujo_model');
        $flujos = $this->Flujo_model->getImportarDatosAllProvinciales();
        
        echo json_encode($flujos);
    }
    
    public function getImportarEstructurasBonos(){
        

        $this->load->model('Flujo_model');
        $flujos = $this->Flujo_model->getImportarEstructurasBonos();
        
        echo json_encode($flujos);
    }
    
    public function getImportarFlujosAll(){
        
        $this->load->model('Flujo_model');
        $flujos = $this->Flujo_model->getImportarFlujosAll();
        
        echo json_encode($flujos);
    }

    public function getImportarFlujoss(){
        
        $this->load->model('Flujo_model');
        $flujos = $this->Flujo_model->getImportarFlujos();
        
        echo json_encode($flujos);
    }
    
    public function getImportarFlujosss(){
        
        $this->load->model('Flujo_model');
        $flujos = $this->Flujo_model->getImportarFlujos();
        
        echo json_encode($flujos);
    }
    
    

    
    
    public function TraerInfo(){
        try {
            
//            Cambiar esto en test
//            $file = 'BONOS.xlsm';
//            $inputFileName = '/var/research/' . $file;
            
            
//            $inputFileName = 'C:\Users\mpetterson\Desktop\Mica.xlsx';
            //$inputFileName = 'C:\Users\mpetterson\Desktop\TAREAS\171024 - Calculadora\calculadora cp.xls';
            $inputFileName = 'C:\BONOS.xlsm';
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        
        $sheet = $objPHPExcel->getSheetByName('Hoja2');
        //$sheet = $objPHPExcel->getSheetByName('códigos');
        //$sheet = $objPHPExcel->getSheetByName('NF18');


        echo "<pre>";
        print_r($sheet);
        echo "</pre>";

    }
    
    public function TraerNombresHojas(){
        try {
            
            //            $file = 'BONOS.xlsm';
//            $inputFileName = '/var/research/' . $file;
            
            //$inputFileName = 'C:\Users\mpetterson\Desktop\TAREAS\171024 - Calculadora\BONOS.xlsm';
            $inputFileName = 'C:\Users\mpetterson\Desktop\TAREAS\171024 - Calculadora\calculadora cp.xls';
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        //$inputFileType = 'Excel2007'; 
        //$inputFileName = $this->input->post('archivo');
        //$objReader = PHPExcel_IOFactory::createReader($inputFileType); 
        $worksheetList = $objReader->listWorksheetNames($inputFileName);
        print_r($worksheetList);
        //echo json_encode(array('resultado'=>$worksheetList));    
    }

}