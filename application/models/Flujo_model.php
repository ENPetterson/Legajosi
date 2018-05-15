<?php

class Flujo_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $fechapagos;
    public $amortizacion;
    public $interes;
    
    
    public function getFlujos(){
        
        $flujo = $this->bono;   
        $sql = "select * from flujo WHERE bono = (SELECT nombre FROM bono WHERE id = {$this->bono})"; 
        $flujos = R::getAll($sql);  
        return $flujos;
    }
    
    public function getFeriados(){
        $feriados = R::getAll('select * from feriados order by anio');
        return $feriados;
    }
    
    
    public function getCalcularFlujo(){
        
        //Calcular Precio
        $precio = $this->precio;
        $flujo = $precio * -1;
        
        //Traer todos los datos de la tabla Flujo (importados del Excel Bonos)
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
        
        //Tomar datos necesarios de la tabla flujos (Fechas y Flujos)
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
        //echo json_encode(array('flujos'=>$flujos, 'fechasExcel'=>$fechasExcel, 'xirr'=>$xirr));
        $flujos = (array('flujos'=>$flujos, 'fechasExcel'=>$fechasExcel, 'xirr'=>$xirr));
        
        return $flujos;
    }
    
    public function validarfechas($fecha){
        if($fecha != ''){
            $valores = explode('-', $fecha);
            $month = strftime("%m", strtotime($valores[1]));
            if(checkdate($month, $valores[0], $valores[2])){
                return true;
            }
        }
        return false;
    }
    
    public function validarBonos(){
        $sql = "SELECT actualizacionAutomatica FROM bono WHERE hoja = ?";
        $bono = R::getRow($sql, array($this->bono));        

        if($bono['actualizacionAutomatica'] == 'true' ){
            return true;
            
        } else{
            return false;
        }
    }
    

    public function getImportarFlujos(){

        //Taer los nombres de los bonos, 
        $this->load->model('Bono_model');
        $bonos = $this->Bono_model->getBonos();
        
        
//        Cambiar esto en test
//        $file = 'BONOS.xlsm';
//        $inputFileName = '/var/research/' . $file;
        
        $inputFileName = 'C:\BONOS.xlsm';
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);

        foreach ($bonos as $bono){

            $sheetname = $bono['hoja'];

            $this->bono = $sheetname;
            $bonoValido = $this->Flujo_model->validarBonos();
                        
            if ($bonoValido == 1){ // Si el bono tiene tildada la opción de actualizar automáticamente.
                
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

                        if($this->Flujo_model->validarfechas($fechapagos)){
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
                    print_r("No se pudo cargar" . $sheetname);
                }
                                   
            }
        }
    }
    
    
    public function getImportarDatos(){

        //Taer los nombres de los bonos, 
        $this->load->model('Bono_model');
        $bonos = $this->Bono_model->getBonos();
        
        try {
            
            
//        Cambiar esto en test
//        $file = 'BONOS.xlsm';
//        $inputFileName = '/var/rese
            
            $inputFileName = 'C:\BONOS.xlsm';
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        
        foreach ($bonos as $bono){
            
            $sheetname = $bono['nombre'];    
            $this->bono = $sheetname;
            $bonoValido = $this->Flujo_model->validarBonos();
                        
            if ($bonoValido == 1){ // Si el bono tiene tildada la opción de actualizar automáticamente.
            
                $objReader->setLoadSheetsOnly($sheetname); 
                $objPHPExcel = $objReader->load($inputFileName); 
                $sheet = $objPHPExcel->getSheetByName($sheetname);
                if($sheet){
                    $aprobado = 0;                    
                    for ($row = 17; $row < 19; $row++){
                        for($column = 0; $column < 29; $column++){

                            $nombreHoja = str_replace(
                                                    array('á','é','í','ó','ú'),
                                                    array('a','e','i','o','u'),
                                                    $sheet->getCellByColumnAndRow($column,$row)->getValue()
                                                );

                            $nombreHoja = strtolower($nombreHoja);                    
                            $nombreHojas[] = $nombreHoja;                                    
                        }
                    }
                    
                    if ($nombreHojas[3].$nombreHojas[32] == 'fechasflow' && 
                        $nombreHojas[13].$nombreHojas[42] == 'vnactualizado' && 
                        $nombreHojas[14].$nombreHojas[43] == 'vractualizado' && 
                        $nombreHojas[55] == 'k' && 
                        $nombreHojas[56] == 'i' && 
                        $nombreHojas[57] == 'flujo'){
                            $aprobado = 1;
                    }
                    
                    unset($nombreHojas);
                    
                    if($aprobado == 1){ 

                        $fechaActualizacion = new DateTime('NOW');
                        $fechaActualizacion = $fechaActualizacion->format('Y-m-d');                       
                        
                        $this->load->model('Dato_model');
                        $this->Dato_model->bono = $sheetname;
                        $this->Dato_model->fechaActualizacion = $fechaActualizacion;
                        $datos = $this->Dato_model->getDatosFecha();

                        if ($datos){
                            $this->Dato_model->datos = $datos;
                            $eliminarDatos = $this->Dato_model->delDatos();
                        }
                        
                        $highestRow = $sheet->getHighestRow();
                        for ($row = 19; $row <= $highestRow; $row++){

                            $fecha = $sheet->getCellByColumnAndRow(3,$row)->getFormattedValue();
                            //Si la fecha viene en formato 01-01-10 la convierte.
                            $patrón = '/[0-9]{2}-[0-9]{2}-[0-9]{2}/';         
                            if(preg_match($patrón, $fecha, $coincidencias)){
                                $valores = explode('-', $fecha);
                                $newfecha = ($valores[2].'-'.$valores[0].'-'.$valores[1]);
                                $fecha = date('d-M-y', strtotime($newfecha));
                            }

                            if($this->Flujo_model->validarfechas($fecha)){
                                $valorN = $sheet->getCellByColumnAndRow(13,$row)->getFormattedValue();
                                $valorS = $sheet->getCellByColumnAndRow(14,$row)->getFormattedValue();
                                $cuponAmortizacion = $sheet->getCellByColumnAndRow(26,$row)->getOldCalculatedValue();
                                $cuponInteres = $sheet->getCellByColumnAndRow(27,$row)->getOldCalculatedValue();
                                $totalFlujo = $sheet->getCellByColumnAndRow(28,$row)->getOldCalculatedValue();

                                $valorN = str_replace('%', '', $valorN);
                                $valorS = str_replace('%', '', $valorS);
                                $cuponAmortizacion = str_replace('%', '', $cuponAmortizacion);          
                                
                                $datos = R::dispense('dato');

                                $datos->bono = $sheetname;
                                $datos->fecha = $fecha;
                                $datos->valorNominalActualizado = (double)$valorN;
                                $datos->valorResidualActualizado = (double)$valorS;
                                $datos->cuponAmortizacion = (double)$cuponAmortizacion;
                                $datos->cuponInteres = (double)$cuponInteres;
                                $datos->totalFlujo = (double)$totalFlujo;
                                $datos->fechaActualizacion = $fechaActualizacion;
                                
                                R::store($datos);

                                echo"<pre>";
                                print_r($sheetname . ' ' . $fecha . ' ' . $valorN. ' ' . $valorS . ' '. $cuponAmortizacion . ' '. $cuponInteres . ' '. $totalFlujo);
                                echo"</pre>";
                            }else{
                                break;
                            }
                        }
                        
                    } else {
                        echo "<pre>";
                        print_r("el bono tiene mal los títulos: " . $sheetname);
                        echo "<pre>";
                    } 
                        
                } else {
                    echo "<pre>";                    
                    print_r("No se pudo cargar: " . $sheetname);
                    echo "<pre>";
                }  
            }
        }
    }
    
}