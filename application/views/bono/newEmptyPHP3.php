<?php

//require_once APPPATH."/third_party/PHPExcel.php"; 




class Util extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    
    
    
    
        public function adjuntarExcel1(){

        $objPHPExcel = PHPExcel_IOFactory::load('C:\Users\mpetterson\Desktop\Mica.xlsx');
        $worksheet = $objPHPExcel->getSheetByName('Hoja2');
        echo "<pre>";
        print_r($worksheet);
        echo "</pre>";
        
        
        
        
    }
    
    public function adjuntarExcel2(){
        //$inputFileType = 'Excel5'; 
        //$inputFileName = './sampleData/example1.xls'; 

        $inputFileType = 'Excel2007';   
        $archivo = 'C:\Users\mpetterson\Desktop\Mica.xlsx';
        //$archivo = $this->input->post('archivo');
        $sheetname = 'Hoja2'; 
        
        $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
        $objReader->setLoadSheetsOnly($sheetname); 
        //$objReader->getActiveSheet($sheetname); 
        $objPHPExcel = $objReader->load($archivo); 

        echo "<pre>";
        print_r($objPHPExcel);
        echo "</pre>";
    }


    public function adjuntarExcel(){
        $inputFileName = 'C:\Users\mpetterson\Desktop\Mica.xlsx';

        //  Read your Excel workbook
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();

        //  Loop through each row of the worksheet in turn
        for ($row = 1; $row <= $highestRow; $row++){ 
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                            NULL,
                                            TRUE,
                                            FALSE);    
            echo"<pre>";
        print_r($rowData);
        echo"</pre>";
        }
        
        
    }
    
    
        public function adjuntarExcel2(){

        $objPHPExcel = PHPExcel_IOFactory::load('C:\Users\mpetterson\Desktop\Mica.xlsx');        
        $sheet = $objPHPExcel->getSheetByName('Hoja2');
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 1; $row <= $highestRow; $row++){ 
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                            NULL,
                                            TRUE,
                                            FALSE);    
        echo"<pre>";
        print_r($rowData);
        echo"</pre>";
        }
    }
    
    
    
    public function adjuntarExcel4(){

        $inputFileName = 'C:\Users\mpetterson\Desktop\Mica.xlsx';       
        $inputFileType = 'Excel2007';


        /**  Create a new Reader of the type defined in $inputFileType  **/ 
        $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
        /**  Read the list of worksheet names and select the one that we want to load  **/
        //$worksheetList = $objReader->listWorksheetNames($inputFileName);
        $sheetname = 'Hoja2'; 

        /**  Advise the Reader of which WorkSheets we want to load  **/ 
        $objReader->setLoadSheetsOnly($sheetname); 
        /**  Load $inputFileName to a PHPExcel Object  **/ 
        $objPHPExcel = $objReader->load($inputFileName); 
        
        
        echo"<pre>";
        print_r($objPHPExcel);
        echo"</pre>";

        
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
        $sheet->getActiveSheet()->fromArray($datos, NULL, 'A2');
        $sheet_writer = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $titulo .date('dMy').'.xls"');
        header('Cache-Control: max-age=0');
        $sheet_writer->save('php://output');
    }
    
    public function adjuntarExcel(){
        $objPHPExcel = PHPExcel_IOFactory::load('C:\Users\mpetterson\Desktop\Mica.xlsx');
        //get the worksheet your choice by its name
        $worksheet = $objPHPExcel->getSheetByName('Hoja2');
    }
    
    public function previewMercado(){
        $ordenes_in = implode(',', $this->ordenes);
        $sql = "SELECT  cierre_id,
                        tramo,
                        moneda, 
                        tipopersona,
                        plazo, 
                        numcomitente,
                        cantidad,
                        precio,
                        cuit,
                        comision
                        FROM    lebac
                WHERE   id in ({$ordenes_in})
                ORDER BY cierre_id, tramo, moneda, tipopersona, plazo ";
        $resultado = R::getAll($sql);
        $planillaAnterior = "";
        $plazoAnterior = 0;
        $filaPlanilla = 0;
        $contadorPlanilla = 0;
        
        $planillaExcel = $this->crearLibro();
        
        $this->load->library('excel');
        $this->workbook = PHPExcel_IOFactory::load($planillaExcel);
        $this->sheetIndex = 12;
        
        
        $maxFila = 5;
        foreach ($resultado as $indice=>$fila){
            if ($fila['tramo'] == 'Competitiva'){
                $planillaActual = 'C';
                $maxFila = 5;
            } else {
                $planillaActual = 'N';
                $maxFila = 10;
            }
            if ($fila['moneda'] == '$'){
                $planillaActual .= 'P';
            } else {
                $maxFila = 5;
                $campos = 'plazosdolares plazos, segmentosdolares segmentos';
                $sql = "select {$campos} from cierre where id = {$fila['cierre_id']}";
                $cierre = R::getRow($sql);
                $plazos =  explode(',', $cierre['plazos']);
                $segmentos = explode(',', $cierre['segmentos']);
                foreach ($plazos as $indiceP=>$plazo){
                    if ((int) $plazo == (int) $fila['plazo']){
                        $segmento = $segmentos[$indiceP];
                    }
                }
                $planillaActual .= $segmento;
            }
            
            if ($fila['tipopersona'] == 'JURIDICA') {
                $planillaActual .= 'J';
            } else {
                $planillaActual .= 'F';
            }
            
            if ($planillaActual == $planillaAnterior && $fila['plazo'] == $plazoAnterior){
                ++$filaPlanilla;
                if ($filaPlanilla > $maxFila){
                    ++$contadorPlanilla;
                    $filaPlanilla = 1;
                    $this->crearHoja($planillaActual, $fila['plazo'], $contadorPlanilla);
                }
            } else {
                $planillaAnterior = $planillaActual;
                $plazoAnterior = $fila['plazo'];
                $filaPlanilla = 1;
                $contadorPlanilla = 1;
                $this->crearHoja($planillaActual, $fila['plazo'], $contadorPlanilla);
            }
            $this->escribirFila($planillaActual, $fila, $filaPlanilla);
        }
        for($i=1;$i<=13;$i++){
            $this->workbook->removeSheetByIndex(0);
        }

        
        
        $objWriter = new PHPExcel_Writer_Excel2007($this->workbook);
        $objWriter->save($planillaExcel);
        unset($objWriter);
        
        $archivos = Array();
        $sheetCount = $this->workbook->getSheetCount();
        for($i=0; $i<$sheetCount;$i++) {
            $this->workbook->setActiveSheetIndex($i);
            $planillaNueva = FCPATH . 'generadas/' . $this->nombreArchivo($this->workbook->getActiveSheet()->getTitle()) . '.xlsx';
            copy($planillaExcel, $planillaNueva);
            $wbNuevo = PHPExcel_IOFactory::load($planillaNueva);
            for ($h=0;$h<$i;$h++){
                $wbNuevo->removeSheetByIndex(0);
            }
            for ($h=$i+1;$h<$sheetCount;$h++){
                $wbNuevo->removeSheetByIndex(1);
            }
            
            $objWriter = new PHPExcel_Writer_Excel2007($wbNuevo);
            $objWriter->save($planillaNueva);
            array_push($archivos, base_url() . 'generadas/' . basename($planillaNueva));
            unset($planillaNueva, $wbNuevo, $objWriter);
        }
        
        
        return array('uris'=>$archivos);
    }
    
    private function crearHoja($planilla, $plazo, $contador){
        $nueva = clone $this->workbook->getSheetByName($planilla);
        $nueva->setTitle($planilla . '-' . $plazo . '-' . $contador);
        $this->sheetIndex++;
        $this->workbook->addSheet($nueva,$this->sheetIndex);
        $this->workbook->setActiveSheetIndex($this->sheetIndex);
        
        $fecha = new DateTime();
        $this->workbook->getActiveSheet()->SetCellValue('F4', $fecha->format('d-M-Y'));
        $this->workbook->getActiveSheet()->SetCellValue('B11', $plazo);
    }
    
    $archivos = Array();
    $sheetCount = $this->workbook->getSheetCount();
    for($i=0; $i<$sheetCount;$i++) {
            $this->workbook->setActiveSheetIndex($i);
            $planillaNueva = FCPATH . 'generadas/' . $this->nombreArchivo($this->workbook->getActiveSheet()->getTitle()) . '.xlsx';
            copy($planillaExcel, $planillaNueva);
            $wbNuevo = PHPExcel_IOFactory::load($planillaNueva);
            for ($h=0;$h<$i;$h++){
                $wbNuevo->removeSheetByIndex(0);
            }
            for ($h=$i+1;$h<$sheetCount;$h++){
                $wbNuevo->removeSheetByIndex(1);
            }
            
            $objWriter = new PHPExcel_Writer_Excel2007($wbNuevo);
            $objWriter->save($planillaNueva);
            array_push($archivos, base_url() . 'generadas/' . basename($planillaNueva));
            unset($planillaNueva, $wbNuevo, $objWriter);
        }
    
    
    
    
    
}













/*
function adjuntarExcel2(grid, title, showHidden){
    var titles = $(grid).jqxGrid('columns').records;
    var titulosArr = Array();
    var data = $(grid).jqxGrid('getboundrows');

    var columnTitle = JSON.stringify(titulosArr);
    var datos = JSON.stringify(data);
    
    $.redirect('/util/grid2Excel', {
        columnTitle: columnTitle,
        data: datos, 
        title: title
    });
    //$.post('/util/grid2Excel', {data: data, title: title});
}
*/
///////////////////////
/*
$('#adjuntarButton').bind('click', function () {
                $('#ventanaBono').ajaxloader();
                datos = {
                    archivo: $("#adjuntarButton").val(),
                }
                $.post('/util/adjuntarExcel2', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/bono');
                    } else {
                        new Messi('Hubo un error guardando el bono', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                        $('#ventanaBono').ajaxloader('hide');
                    }
                }, 'json');           
        });  
*/
///////////////////////
/*
        var srcTest =
            {
                datatype: "json",
                datafields: [
                    { name: 'archivo'}
                ],
                url: '/util/adjuntarExcel',
                async: false
            };
        var DATest = new $.jqx.dataAdapter(srcTest);

        $('#adjuntarButton').on('change', function (event){     
            var args = event.args;
            if (args) {
                // index represents the item's index.                      
                var item = args.item;
                var value = item.value;
                srcTest.data = {
                    archivo: $("#adjuntarButton").val() // {vencimiento: "30/10/2017"}
                    //vencimiento: $("#vencimiento").val('val', "2013/3/3"), //{vencimiento: "30/10/2017"}
                };
                DATest.dataBind();
            }
            console.log(srcTest.data);
        });
*/
///////////////////////        
/*
        $('#adjuntarButton').on('change', function (event){   
            var args = event.args;
            if (args) {
                
                // index represents the item's index.                      
                var item = args.item;
                var value = item.value;
                
                datos = {
                    archivo: $("#adjuntarButton").val()
                }
                
                $.post('/util/adjuntarExcel', datos, function(data){
                    if (data.id > 0){
                        $.redirect('/bono');
                    } else {
                        new Messi('Hubo un error guardando el bono', {title: 'Error', 
                            buttons: [{id: 0, label: 'Cerrar', val: 'X'}], modal:true});
                        $('#ventanaBono').ajaxloader('hide');
                    }
                }, 'json');
                
            }
        });
*/
///////////////////////
/*
        $('#adjuntarButton').on('change', function (event){   
            console.log('archivo');
            var args = event.args;
            if (args) {
                // index represents the item's index.                      
                var item = args.item;
                var value = item.value;
                
                $.post('/util/adjuntarExcel', {archivo: value}, function(util){
                    //$('#codigoCaja').val(util.archivo);
                    console.log(util.archivo);
                }, 'json');
                
            }
        });
*/     
///////////////////////   

/*
        $('#adjuntarArchivo').bind('click', function () {
                console.log('archivo');
                datos = {
                    archivo: $("#adjuntarArchivo").val()
                }
                $.post('/util/adjuntarExcel', datos, function(data){
                    console.log(datos);
                }, 'json');           
        });  
*/