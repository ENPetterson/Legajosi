<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH."/third_party/PHPExcel.php";
include APPPATH."/third_party/financial_class.php";

class Flujo extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }

    
    public function importarSeries(){

        $inputFileName = 'C:\BONOS.xlsm';
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        //$sheetname = 'NF18';
        //$sheetname = 'AE22';
        $sheetname = 'AA19';
        $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
        $objReader->setLoadSheetsOnly($sheetname); 
        $objPHPExcel = $objReader->load($inputFileName); 
        $sheet = $objPHPExcel->getSheetByName($sheetname);

        function validarfecha($fecha){
            if($fecha != ''){
                $valores = explode('-', $fecha);
                $month = strftime("%m", strtotime($valores[1]));
                if(checkdate($month, $valores[0], $valores[2])){
                    return true;
                }
            }
            return false;
        }
        
        $highestRow = $sheet->getHighestRow(); 

        for ($row = 19; $row <= $highestRow; $row++){ 
            $fechapagos = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();

            if(validarfecha($fechapagos)){
                //print_r("OK");
            }else{
                break;
            }
            
            $amortizacion = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue();
            $vr = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue();
            $interes = $sheet->getCellByColumnAndRow(7,$row)->getOldCalculatedValue(); 

            $amortizacion = str_replace('%', '', $amortizacion);
            $vr = str_replace('%', '', $vr);
            $interes = str_replace('%', '', $interes);          
            
            $flujo = R::dispense('flujo');
            
            $flujo->bono = $sheetname;
            $flujo->fechapagos = $fechapagos;
            $flujo->amortizacion = (double)$amortizacion;
            $flujo->vr = (double)$vr;
            $flujo->interes = (double)$interes;
            R::store($flujo);
            
            echo"<pre>";
            print_r($sheetname . ' ' . $fechapagos . ' ' . $amortizacion. ' ' . $vr . ' '. $interes);
            echo"</pre>";
        }
    }
    
    public function importarBonos(){

        //Taer los nombres de los bonos, 
        $this->load->model('Bono_model');
        $bonos = $this->Bono_model->getBonos();
        
        $inputFileName = 'C:\BONOS.xlsm';
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        
        function validarfechas($fecha){
            if($fecha != ''){
                $valores = explode('-', $fecha);
                $month = strftime("%m", strtotime($valores[1]));
                if(checkdate($month, $valores[0], $valores[2])){
                    return true;
                }
            }
            return false;
        }
        
        foreach ($bonos as $bono){
            //$nombre[] = $bono['nombre'];
            $sheetname = $bono['nombre'];
            $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
            $objReader->setLoadSheetsOnly($sheetname); 
            $objPHPExcel = $objReader->load($inputFileName); 
            $sheet = $objPHPExcel->getSheetByName($sheetname);
            if($sheet){
                $highestRow = $sheet->getHighestRow();
                for ($row = 19; $row <= $highestRow; $row++){ 
                    $fechapagos = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
                    //Si la fecha viene en formato 01-01-10 la convierte.
                    $patrón = '/[0-9]{2}-[0-9]{2}-[0-9]{2}/';         
                    if(preg_match($patrón, $fechapagos, $coincidencias)){
                        $valores = explode('-', $fechapagos);
                        $newfechapagos = ($valores[2].'-'.$valores[0].'-'.$valores[1]);
                        $fechapagos = date('d-M-y', strtotime($newfechapagos));
                    }

                    if(validarfechas($fechapagos)){
                    $amortizacion = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue();
                    $vr = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue();
                    
                    //$interes = $sheet->getCellByColumnAndRow(7,$row)->getCalculatedValue();
                    //$interes = $sheet->getCellByColumnAndRow(7,$row)->getValue();                    
                    //$interes = $sheet->getCellByColumnAndRow(7,$row)->getFormattedValue();
                    $interes = $sheet->getCellByColumnAndRow(7,$row)->getOldCalculatedValue();

                    
                    $amortizacion = str_replace('%', '', $amortizacion);
                    $vr = str_replace('%', '', $vr);
                    $interes = str_replace('%', '', $interes);          

                    $flujo = R::dispense('flujo');

                    $flujo->bono = $sheetname;
                    $flujo->fechapagos = $fechapagos;
                    $flujo->amortizacion = (double)$amortizacion;
                    $flujo->vr = (double)$vr;
                    $flujo->interes = (double)$interes;
                    R::store($flujo);

                    echo"<pre>";
                    print_r($sheetname . ' ' . $fechapagos . ' ' . $amortizacion. ' ' . $vr . ' '. $interes);
                    echo"</pre>";
                    }else{
                        break;
                    }
                }
            } else {
                print_r("No se pudo cargar");
            }
        }
    }
    
    
    
    
    
    
    
    
    
    
    public function calcularFlujo(){
        
        //Bono
        $bono = $this->input->post('bono');        
        $precio = $this->input->post('precio');

        $this->load->model('Flujo_model');
        $this->Flujo_model->precio = $this->input->post('precio');
        $this->Flujo_model->bono = $this->input->post('bono');
        $flujos = $this->Flujo_model->getCalcularFlujo();
        
        echo json_encode($flujos);

    }

/////Funcion que anda Ok
    
    
/*    
    public function calcularFlujossss(){
        
        
        //Bono
        //$bono = $this->input->post('bono');
        
       
        //Calcular Flujo
        
        //Precio
        $precio = $this->input->post('precio');
        $flujo = $precio * -1;
        
        //Traer los datos de la tabla Flujo
        $this->load->model('Flujo_model');
        $this->Flujo_model->bono = $this->input->post('bono');
        $datos = $this->Flujo_model->getFlujos();

        $this->load->model('Flujo_model');
        $feriados = $this->Flujo_model->getFeriados();
        
        //Calcular fecha sumando dos días hábiles al día de hoy.           
        $mañana = date('n/j/Y', strtotime("+1 days"));
        function esFestivo($mañana, $feriados) {      
            $dias_saltados = array(0,6);
            $w = date("w", strtotime($mañana)); // dia de la semana en formato 0-6
            if(in_array($w, $dias_saltados)) { return true; }
            
            $n = date("n", strtotime($mañana)); // mes en formato 1 - 12
            $j = date("j", strtotime($mañana)); // dia en formato 1 - 31
            $y = date("Y", strtotime($mañana)); // año en formato XXXX                    
            foreach ($feriados as $key => $value) {
                if(($value['anio'] == $y) && ($value['mes'] == $n) && ($value['dia'] == $j)){
                   return true; 
                }
            }
            return false;
        }
        for ($diascontados = 0; $diascontados < 2; $diascontados++){
            if(esFestivo($mañana, $feriados)){
                $diascontados--;
            }
            $fechaFuturaExcel = date('d-M-y', strtotime($mañana)); //Fecha como tendría que mostrarse en pantalla.
            $fechaFutura = strtotime($mañana);
            $mañana = date('n/j/Y',(strtotime('+1 day', strtotime($mañana))));
        }
        
        //Traer datos qimportados del Excel Bonos a tabla flujos 
        $fechaHoy = date('d-M-y');
        foreach($datos as $dato){
            if(strtotime($dato['fechapagos']) > strtotime($fechaHoy)){
                $fechasExcel[] = $dato['fechapagos']; //Fecha como tendría que mostrarse en el excel.
                $fechas[] = strtotime($dato['fechapagos']);
                $flujos[] = ($dato['interes'] * 100) + ($dato['amortizacion']);
            }
        }
        //Resultados
        array_unshift($flujos, $flujo);
        array_unshift($fechas, $fechaFutura);
        array_unshift($fechasExcel, $fechaFuturaExcel);
        
        $f = new Financial;
        $xirr = $f->XIRR($flujos, $fechas, 0.1) * 100;
        echo json_encode(array('flujos'=>$flujos, 'fechasExcel'=>$fechasExcel, 'xirr'=>$xirr));
    }

*/

////    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function TraerInfo(){  
        try {
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
        /*
        
        //$highestRow = $sheet->getHighestRow(); 
        //$highestColumn = $sheet->getHighestColumn();
         
        //$fila = 4;
        //$fila = 20;
        $fila = 18;
        
        for ($row = 1; $row <= 40; $row++){ 
            $rowData = $sheet->rangeToArray('A' . $fila . ':' . $highestColumn . $row,
                                            NULL,
                                            TRUE,
                                            FALSE);    
        }
        echo"<pre>";
        print_r($rowData);
        echo"</pre>";
         
        */
    }
    
    public function TraerNombresHojas(){
        try {
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