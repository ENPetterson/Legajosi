<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH."/third_party/PHPExcel.php";

class Flujo extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }

    
    public function importarSeries(){
        //$inputFileName = 'C:\Users\mpetterson\Desktop\Mica.xlsx'; 
        //$inputFileName = 'C:\Users\mpetterson\Desktop\TAREAS\171024 - Calculadora\calculadora cp.xls';
        $inputFileName = 'C:\Users\mpetterson\Desktop\TAREAS\171024 - Calculadora\BONOS.xlsm';
        
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        
        //$sheetname = 'Hoja2'; 
        //$sheetname = 'códigos';
        $sheetname = 'NF18';
        
        $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
        $objReader->setLoadSheetsOnly($sheetname); 
        $objPHPExcel = $objReader->load($inputFileName); 
        $sheet = $objPHPExcel->getSheetByName($sheetname);

        //echo "<pre>";
        //print_r($sheet);
        //echo "</pre>";
        
        //$test = $sheet->getCell('A6')->getValue();
        //print_r($test);
        
        /*
        $keyCell = $sheet->getCellByColumnAndRow(0,5)->getFormattedValue();
        echo "<pre>";
        print_r($keyCell);
        echo "</pre>";
        */
        
        /*
        $highestRow = $sheet->getHighestRow();
        print_r($highestRow);
        
        $fechaPagos = $objPHPExcel->getActiveSheet()->rangeToArray('A19:A300', NULL, True, True);
        echo "<pre>";
        print_r($fechaPagos);
        echo "</pre>";
        
        $amortizacion = $objPHPExcel->getActiveSheet()->rangeToArray('F19:F300', NULL, True, True);
        echo "<pre>";
        print_r($amortizacion);
        echo "</pre>";
        
        $vr = $objPHPExcel->getActiveSheet()->rangeToArray('G19:G300', NULL, True, True);
        echo "<pre>";
        print_r($vr);
        echo "</pre>";
        */
        
        
        /*
        //$firstRow = 1;
        //$highestColumn = $sheet->getHighestColumn();     //BG  
        //$highestRow = 5;
        //$myDataArray = $objPHPExcel->getActiveSheet()->rangeToArray('A' . $firstRow . ':' . $highestColumn . $highestRow, NULL, True, True);
        
        //$myDataArray = $objPHPExcel->getActiveSheet()->rangeToArray('A1:Z2', NULL, True, True);
        
        echo "<pre>";
        print_r($myDataArray);
        echo "</pre>";
        */
        
        /*
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();     //BG   
       
        //$highestRow = 4;
        //$highestRow = 20;
        $highestRow = 2;
        
        
        for ($row = 1; $row <= 5; $row++){ //400 lee a partir de 750 no trae todas
            $rowData = $sheet->rangeToArray('A' . $highestRow . ':' . $highestColumn . $row,
                                            NULL,
                                            TRUE,
                                            FALSE);   
            
        }
        echo"<pre>";
        print_r($rowData);
        echo"</pre>";
        */

/*
    function validateDate($date) {
        $fecha = substr($date, 0, 10);
        $d = DateTime::createFromFormat('Y-m-d', $fecha);
        
        return $d && $d->format('Y-m-d') == $fecha;
    }
    
    function devolverFecha($date){
        $fecha = substr($date, 0, 10);
        $d = DateTime::createFromFormat('Y-m-d', $fecha);
        $fecha = $d->format('d/m/Y');
        return $fecha;
    }
*/

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
        $maxcell = 0;

        for ($row = 19; $row <= $highestRow; $row++){ 
        $cell = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();

//        $valores = explode('-', $cell);
//        $month = strftime("%m", strtotime($valores[1]));
        
//        if(checkdate($month, $valores[0], $valores[2])){
//	print_r("ok");
//        $maxcell++;
//        }else{
//            print_r("Lala");
            //break;
//        }
        
        if(validarfecha($cell)){
	print_r("ok");
        $maxcell++;
        }else{
            print_r("Lala");
            break;
        }
        
        
        
        print_r($maxcell);
        echo"<pre>";
        print_r($cell);
        echo"</pre>";
        }
        
        
    }

    
    public function TraerInfo(){  
        try {
            $inputFileName = 'C:\Users\mpetterson\Desktop\Mica.xlsx';
            //$inputFileName = 'C:\Users\mpetterson\Desktop\TAREAS\171024 - Calculadora\calculadora cp.xls';
            //$inputFileName = 'C:\Users\mpetterson\Desktop\TAREAS\171024 - Calculadora\BONOS.xlsm';
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