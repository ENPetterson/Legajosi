<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/PHPExcel.php";

class Util extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    
    public function buscarDuplicado(){
        $tabla = $this->input->post('tabla');
        $campo = $this->input->post('campo');
        $valor = $this->input->post('valor');
        $id = $this->input->post('id');
        $this->load->model('Util_model');
        $resultado = $this->Util_model->buscarDuplicado($tabla, $campo, $valor, $id);
        echo json_encode(array('resultado'=>$resultado));
    }
    
    
    
    
    public function grid2Excel(){
        $titulo = $this->input->post('title');
        $datos = array();
        $tituloColumna = (array) json_decode($this->input->post('columnTitle'));
        $arreglo = json_decode($this->input->post('data'));
        foreach ($arreglo as $item) {
            $datos[] = (array) $item;
        }
        $this->load->library('excel');
        $sheet = new PHPExcel();
        $sheet->getProperties()->setTitle($titulo)->setDescription($titulo);
        $sheet->setActiveSheetIndex(0);
        $sheet->getActiveSheet()->fromArray($tituloColumna, NULL, 'A1');
        $sheet->getActiveSheet()->fromArray($datos, NULL, 'A2', true);
        $sheet_writer = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $titulo .date('dMy').'.xls"');
        header('Cache-Control: max-age=0');
        $sheet_writer->save('php://output');
    }
    
    
    
    
    
    
    
    
    
    
    
//    public function grid2Excel(){
//        $titulo = $this->input->post('title');
//        $datos = array();
//        $tituloColumna = (array) json_decode($this->input->post('columnTitle'));
//       
//        //Agregado
//        $arreglo = preg_replace('/:\s*(\-?\d+(\.\d+)?([e|E][\-|\+]\d+)?)/', ': "$1"', $this->input->post('data'));
//        $arreglo = json_decode($arreglo);
//        
////        $arreglo = json_decode($this->input->post('data'));
//
//        foreach ($arreglo as $item) {
//            $datos[] = (array) $item;
//        }
//        
////        echo "<pre>";
////        var_dump($arreglo);
////        echo "</pre>";
////        die;
//
//        $this->load->library('excel');
//                
////        var_dump(8.2);
//        
//        //Agregado
////        $defaultPrecision = ini_get('precision');
//        
//        $sheet = new PHPExcel();
//        
////        var_dump(8.2);       
////        die;
//        
//        //Agregado
//        ini_set('precision', 16);
//        
////        var_dump(8.2);
////        die;
//        
//        $sheet->getProperties()->setTitle($titulo)->setDescription($titulo);
//        $sheet->setActiveSheetIndex(0);
////                $sheet->getActiveSheet()
////                    ->setFormatCode(PHPExcel_Cell_DataType::TYPE_STRING);
//        
//        $sheet->getActiveSheet()->fromArray($tituloColumna, NULL, 'A1');
//        $sheet->getActiveSheet()->fromArray($datos, NULL, 'A2')
//                                ;
////                                ->getStyle('J4')
////                                ->getNumberFormat()
////                                ->setFormatCode(PHPExcel_Style_NumberFormat::TYPE_STRING);
//                
////                                ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
////                                
////                                ->setFormatCode('0.0000000000000000000000000');  
////                                ->setFormatCode('0');
//        
//        
//        
//        
//        
////        echo "<pre>";
////        var_dump($sheet->getActiveSheet()->fromArray($datos, NULL, 'A2')); 
////        echo "</pre>";
//        
//        $n = 20;
//        $z = 40;
//        $y = 60;
//        foreach ($datos as $k => $dato){
//           $n++;
//           $z++;
//           $y++;
//           $sheet->getActiveSheet()->setCellValueByColumnAndRow('A', $n, 'a'.$dato['totalFlujo']);
//           $sheet->getActiveSheet()->setCellValueByColumnAndRow('A', $z, strval($dato['totalFlujo']))
//                                   ->getStyle('A23')
//                                   ->getNumberFormat()
////                                ->setFormatCode(PHPExcel_Style_NumberFormat::TYPE_STRING);
//                                   ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
//           $sheet->getActiveSheet()->setCellValueByColumnAndRow('A', $y, sprintf('%0.0f',$dato['totalFlujo']));
//        }
//        
//        
////        var_dump($sheet->getActiveSheet()->setCellValueByColumnAndRow('A', $n, 'a'.$dato['totalFlujo']));
////        die;
//        
////        
////        echo "<pre>";
////        var_dump($datos);
////        echo "</pre>";
////        
////        echo "<pre>";
////        var_dump($sheet->getActiveSheet()->fromArray($datos['totalFlujo'], NULL, 'A2')); 
////        echo "</pre>";
//        
////        
////        echo "<pre>";
////        var_dump($datos); 
////        echo "</pre>";
////        
////        
////        echo "<pre>";
////        var_dump($sheet); 
////        echo "</pre>";
////        die;
//        
////        0.9887467855306017
//        
////        0.988746785530601690616947507806 
//        
////        $sheet->getActiveSheet()->setCellValue('J4', 1513789642);
////        // Set a number format mask to display the value as 11 digits with leading zeroes
////        $sheet->getActiveSheet()->getStyle('J4')
////            ->getNumberFormat()
////            ->setFormatCode('0000000000000000000000');
//        
//        // Set a number format mask to display the value as 11 digits with leading zeroes
////        $sheet->getActiveSheet()->getStyle('J4')
////            ->getNumberFormat()
////            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
//        
////        // Set a number format mask to display the value as 11 digits with leading zeroes
////        $sheet->getActiveSheet()->getStyle('J4')
////            ->getNumberFormat()
////            ->setFormatCode('0.00000000000000000000');      
//        
////        $textFormat='0.0000000000000000000000000000000';//'General','0.00','@'
////        // Set a number format mask to display the value as 11 digits with leading zeroes
////        $sheet->getActiveSheet()->getStyle('J4')
////            ->getNumberFormat()
////            ->setFormatCode($textFormat);
//                
////            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_GENERAL);  
//
//        $sheet_writer = PHPExcel_IOFactory::createWriter($sheet, 'Excel2007');
//        header('Content-Type: application/vnd.ms-excel');
//        header('Content-Disposition: attachment;filename="' . $titulo .date('dMy').'.xlsx"');
//        header('Cache-Control: max-age=0');
//        
////        print_r($sheet_writer);
////        
////        die;
//        
//        $sheet_writer->save('php://output');
//    }
    

    public function adjuntarExcel(){
        
        $inputFileName = $this->input->post('archivo');
        
//        $inputFileName = 'c:/' . $inputFileName;        
//        $inputFileName = '/var/www/calculadora/' . $inputFileName;
          $inputFileName = '/var/research/' . $inputFileName;
//        print_r($inputFileName); die;
        
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
        $worksheetList = $objReader->listWorksheetNames($inputFileName);
        
        echo json_encode(array('resultado'=>$worksheetList));

        }
    
    


    
}