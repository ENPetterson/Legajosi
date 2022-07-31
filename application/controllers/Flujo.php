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
    
    
    function saveFlujo(){
        
        $this->load->model('Flujo_model');
        
        $this->Flujo_model->id = $this->input->post('id');
        $this->Flujo_model->bono = $this->input->post('bono');
        
//        $this->Flujo_model->fecha = $this->input->post('fecha');
        $this->Flujo_model->fechapagos = $this->input->post('fechapagos');
        
        $this->Flujo_model->vr = $this->input->post('vr');
        $this->Flujo_model->amortizacion = $this->input->post('amortizacion');
        $this->Flujo_model->interes = $this->input->post('interes');
        
        $this->Flujo_model->VNActualizado = $this->input->post('VNActualizado');
        $this->Flujo_model->VRActualizado = $this->input->post('VRActualizado');
        $this->Flujo_model->cuponAmortizacion = $this->input->post('cuponAmortizacion');
        $this->Flujo_model->cuponInteres = $this->input->post('cuponInteres');
        $this->Flujo_model->totalFlujo = $this->input->post('totalFlujo');
        
        $this->Flujo_model->fechaActualizacion = $this->input->post('fechaActualizacion');        
        
        $id = $this->Flujo_model->saveFlujo();
        $resultado = array('id'=>$id);
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
    
    /*
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
    */
    
    public function getImportarFlujosAllBonos(){
        
//        $planilla = $this->input->post('planilla');        

        $this->load->model('Flujo_model');        
        $this->Flujo_model->planilla = $this->input->post('planilla');
        $flujos = $this->Flujo_model->getImportarFlujosAllBonos();
        
        echo json_encode($flujos);
    }
    
    /*
    public function getImportarFlujosAllProvinciales(){
        
        $this->load->model('Flujo_model');
        $flujos = $this->Flujo_model->getImportarFlujosAllProvinciales();
        
        echo json_encode($flujos);
    }
     * */
    
    
//    public function getImportarDatosAllBonos(){
//        
//        $this->load->model('Flujo_model');
//        $flujos = $this->Flujo_model->getImportarDatosAllBonos();
//        
//        echo json_encode($flujos);
//    }
//    
//    public function getImportarDatosAllProvinciales(){
//        
//        $this->load->model('Flujo_model');
//        $flujos = $this->Flujo_model->getImportarDatosAllProvinciales();
//        
//        echo json_encode($flujos);
//    }
    
    public function getImportarEstructurasBonos(){
        

        $this->load->model('Flujo_model');
        
        $flujos = $this->Flujo_model->getImportarEstructurasBonos();
        
//        $datosMercado = $this->Flujo_model->getImportarDatosMercado();
//        
//        $latam = $this->Flujo_model->getImportarLatam();
//        
//        $treasuries = $this->Flujo_model->getImportarTreasuries();
                
        echo json_encode($flujos);
    }
    
    
    
    public function getImportarAll(){

        
        $this->load->model('Flujo_model');
        
        $estructuras = $this->Flujo_model->getImportarEstructurasBonos();
                
        $datosMercado = $this->Flujo_model->getImportarDatosMercado();
        
        $latam = $this->Flujo_model->getImportarLatam();
        
        $treasuries = $this->Flujo_model->getImportarTreasuries();
        
        $planillas = array("bonos", "provinciales");              
        foreach($planillas as $planilla){    
            $this->Flujo_model->planilla = $planilla;        
            $flujos[$planilla] = $this->Flujo_model->getImportarFlujosAllBonos();
        }

        
        
        ////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //Log
//            $log = new Log('test','calculadora');
//            $log->log_msg('fizzy soda : 45 bubbles remaining per cubic centimeter');

        $fechaNombre = new DateTime('NOW');
        $fechaNombre = $fechaNombre->format('Ymdhis');  
        $log_name = "test".$fechaNombre.".txt";
//        $page_name = "Calculadora";
        $app_id = uniqid();//give each process a unique ID for differentiation

//            if(file_exists('/var/www/calculadora/generador/'.$log_name)){                 
//                $log_name = 'a_default_log.txt'; 
//            }

        $log = fopen('/var/www/calculadora/generador/importacionLog/'.$log_name,'a');
        $log_line = join(array( date(DATE_RFC822), chr(13).chr(10), $app_id, chr(13).chr(10), $datosMercado['log'], $latam['log'], $treasuries['log'], $estructuras['log'], $flujos['bonos']['log'], $flujos['provinciales']['log'] ) );
        fwrite($log, $log_line."\n");
//            $this->log_msg("Closing log");
        fclose($log);
        ////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////

        echo json_encode(array('resultadoFlujosBonos'=>$flujos['bonos'], 'resultadoFlujosProvinciales'=>$flujos['provinciales'], 'resultadoEstructuras'=>$estructuras, 'resultadoMercado'=>$datosMercado, 'resultadoLatam'=>$latam, 'resultadoTreasuries'=>$treasuries, 'logName'=>$log_name));    

        
//        
//        echo json_encode($flujos);
    }
    
    
//    function Descargar($ElFichero){ 
//
//        $TheFile = basename($ElFichero); 
//
//        header( "Content-Type: application/octet-stream");  
//        header( "Content-Length: ".filesize($ElFichero));  
//        header( "Content-Disposition: attachment; filename=".$TheFile."");  
//        readfile($ElFichero);  
//    } 

    

    
    
    public function getDescargarLog(){
            
       
        $logName = $this->input->post('logName');
        
        $this->load->helper('download');
        
        //Obtengo datos
//        $this->load->model('Bono_model');
//        $this->Bono_model->id = 2;
//        $adjunto = $this->Bono_model->getBono();
//        print_r($adjunto);
//        die;
        
        $contenido = file_get_contents("/var/www/calculadora/generador/importacionLog/" . $logName);
        
        force_download($logName, $contenido);


    }
    
//    public function getImportarFlujosAll(){
//        
//        $this->load->model('Flujo_model');
//        $flujos = $this->Flujo_model->getImportarFlujosAll();
//        
//        echo json_encode($flujos);
//    }

//    public function getImportarFlujoss(){
//        
//        $this->load->model('Flujo_model');
//        $flujos = $this->Flujo_model->getImportarFlujos();
//        
//        echo json_encode($flujos);
//    }
    
//    public function getImportarFlujosss(){
//        
//        $this->load->model('Flujo_model');
//        $flujos = $this->Flujo_model->getImportarFlujos();
//        
//        echo json_encode($flujos);
//    }
    
    
//            $handle = fopen("test.txt", "w");
//            fwrite($handle, "text1.....");
//            fclose($handle);
//
//            header('Content-Type: application/octet-stream');
//            header('Content-Disposition: attachment; filename='.basename('file.txt'));
//            header('Expires: 0');
//            header('Cache-Control: must-revalidate');
//            header('Pragma: public');
//            header('Content-Length: ' . filesize('file.txt'));
//            readfile('file.txt');
//            exit;

        
        
    ////////////////////////////////////////////////////////////////////////////
            //$archivo = FCPATH . 'generadas/' . date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-lebacs.dat';
            
//            $data = "lala";
//            $archivo = date('Y-m-d-H-i-s') . '-' . $data['colocacion'] . '-bonos.txt';
//            $archivo = 'test.txt';

//            $this->zip->add_data($archivo, 'otraData');
            //file_put_contents($archivo, $data['datos']);
            //array_push($uris, base_url() . 'generadas/' . basename($archivo));

//        $nombreZip = FCPATH . 'test2.zip';
//        $this->zip->archive($nombreZip);
        
    ////////////////////////////////////////////////////////////////////////////
        
//        $file = fopen('test.txt', "r") or exit("Unable to open file!");
//        //Output a line of the file until the end is reached
//        while(!feof($file)){
//            echo fgets($file). "<br />";
//        }
        
    ////////////////////////////////////////////////////////////////////////////    
        
//        fclose($file);
        
//        print_r($file);
//        die;
//        Descargar("test.txt"); 
//        
    ////////////////////////////////////////////////////////////////////////////
        /*
        echo file_get_contents("/var/www/calculadora/generador/importacionLog/test20181115122351.txt");

        //para meterlo a una variable y procesarlo:
        $contenido=file_get_contents("/var/www/calculadora/generador/importacionLog/test20181115122351.txt");

        //para guardarlo a disco:
        file_put_contents("test.txt",$contenido);
        
        $file = fopen('lala.txt', "r") or exit("Unable to open file!");
        */
        
    ////////////////////////////////////////////////////////////////////////////
        
        
//        $dir = __DIR__.'/test.txt';
//        
//        $content = 'test content';
//        $tmp = new File($content, null, null, $dir);
//        $fileName = $tmp->getFileName();
//        $this->assertEquals($dir, dirname($fileName));
//
//        unset($tmp);
//        @rmdir($dir);
    ////////////////////////////////////////////////////////////////////////////
    
    
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