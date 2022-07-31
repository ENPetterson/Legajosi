<?php

class Flujo_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $bono;
    public $flujo;
    public $fechapagos;
    public $amortizacion;
    public $interes;
    
    public $usuario_id;
    public $menu_id;
    public $controlador_id;
   
    
    public function saveFlujo(){
        
        $flujo = R::load('flujo', $this->id);

        $bono = R::load('bono', $this->bono);
        
        $flujo->bono = $bono;
//        $flujo->fecha = $this->fecha;
        
        $flujo->fechapagos = $this->fechapagos;
        $flujo->vr = $this->vr;
        $flujo->amortizacion = $this->amortizacion;
        $flujo->interes = $this->interes;
        
        $flujo->VNActualizado = $this->VNActualizado;
        $flujo->VRActualizado = $this->VRActualizado;
        $flujo->cuponAmortizacion = $this->cuponAmortizacion;        
        $flujo->cuponInteres = $this->cuponInteres;
        $flujo->totalFlujo = $this->totalFlujo;
        $flujo->fechaActualizacion = $this->fechaActualizacion;
        
        
        $this->id = R::store($flujo);
        return $this->id;
    }
    
    
    
    public function getFechaActualizacion(){
        $fechaActualizacion = R::getAll('SELECT DISTINCT fechaActualizacion from flujo where bono_id = ? ', array($this->bono));        
        return $fechaActualizacion;
    }
    
    
    
    public function getFlujos(){

        $flujo = $this->bono;   
        $sql = "SELECT * FROM flujo WHERE bono = (SELECT nombre FROM bono WHERE id = {$this->bono}) AND fechaActualizacion = (SELECT MAX(fechaActualizacion) from flujo where bono = (SELECT nombre FROM bono WHERE id = {$this->bono}) )";         
        $flujos = R::getAll($sql);  
        return $flujos;
    }
    
    public function getFeriados(){
        $feriados = R::getAll('select * from feriados order by anio');
        return $feriados;
    }
    
    
    public function grillaFlujo(){
        $sql = "SELECT 
                f.id,
                b.nombre as bono,
                f.fechapagos,
                f.vr,
                f.amortizacion,
                f.interes,
                f.VNActualizado,
                f.VRActualizado,
                f.cuponAmortizacion,
                f.cuponInteres,
                f.totalFlujo
                FROM flujo f
                LEFT JOIN bono b
                ON b.id = f.bono_id
                WHERE f.bono_id = ?
                AND f.fechaActualizacion = ?
                ORDER BY id"; 
          
        $resultado = R::getAll($sql, array($this->bono, $this->fecha));
        return $resultado;
    }

        public function grilla(){
        $instrumentos = R::getAll('SELECT 
                i.id, 
                i.nombre,
                t.nombre as tipoinstrumento_id
                FROM instrumento i
                LEFT JOIN tipoinstrumento t
                ON tipoinstrumento_id = t.id');

        return $instrumentos; 
    }  
    

    
    public function getCalcularFlujo(){
        
        //Calcular Precio
        $precio = $this->precio;
        $flujo = $precio * -1;
        
        //Traer todos los flujos de la tabla Flujo (importados del Excel Bonos)
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
        
        //Tomar flujos necesarios de la tabla flujos (Fechas y Flujos)
        $fechaHoy = date('d-M-y');
        
        
        foreach($datos as $dato){
            if(strtotime($dato['fechapagos']) > strtotime($fechaHoy)){
                $fechasExcel[] = $dato['fechapagos']; //Fecha como tendría que mostrarse en el excel.
                $fechas[] = strtotime($dato['fechapagos']);
                $flujos[] = ($dato['interes'] * 100) + ($dato['amortizacion'] * 100);
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
            if(checkdate($month, $valores[2], $valores[0])){
                return true;
            }else{
                print_r("Fecha No válida");
            }
        }
        return false;
    }
    
    public function validarBonos(){
        $sql = "SELECT actualizacionAutomatica FROM bono WHERE hoja = ?";
        $bono = R::getRow($sql, array($this->hoja));        

        if($bono['actualizacionAutomatica'] == 'true' ){
            return true;
        } else{
            return false;
        }
    }
    
    public function validarBonosFecha(){

        $sql = "SELECT bono FROM flujo WHERE bono = ? and fechaActualizacion = ? ";
        $bono = R::getRow($sql, array($this->bono, $this->fechaActualizacion));        
        
        if($bono['bono'] != '' ){
            return true;
        } else{
            return false;
        }
    }
    
   public function borrarFlujosBonos(){

        $sql = "SELECT id FROM flujo WHERE bono_id = ? AND fechaActualizacion = ?";
        $bono = R::getCol($sql, array($this->bono_id, $this->fechaActualizacion));           
        
        if (!(empty($bono))){
            
            //Log
//            echo "<pre>";
//            print_r("Se encontraron flujos con fecha del día de hoy del bono.");
            
            $bono = implode(",", $bono);
            
            $sql = "DELETE FROM flujo WHERE id IN ({$bono})";
            $result = R::exec($sql); 
            
            return true;
        }else{
            //Log
//            echo "<pre>";
//            print_r("No se encontraron flujos del día de la fecha del bono.");
            
            return false;
        }
    }      
    
    
    public function borrarDatosEstructuraBonos(){

//        echo "</pre>";
//        print_r($this->bono);
//        echo "</pre>";
//        print_r($this->fechaActualizacion);
//        echo "</pre>";
        
        $sql = "SELECT id FROM estructurabono WHERE bono_id = ? AND fechaActualizacion = ?";
        $bono = R::getCol($sql, array($this->bono, $this->fechaActualizacion));           
        
//        print_r($bono);
//        echo "</pre>";
        
        
        if (!(empty($bono))){
            
//            echo "<pre>";
//            print_r("Se encontraron flujos con fecha del día de hoy del bono.");
//            echo "</pre>";
            $bono = implode(",", $bono);
            
            $sql = "DELETE FROM estructurabono WHERE id IN ({$bono})";
            $result = R::exec($sql); 
            
            return true;
        }else{
//            echo "<pre>";
//            print_r("No se encontraron flujos del día de la fecha del bono.");
//            echo "</pre>";
            return false;
        }
    }  
    
    
    
    
    public function borrarDatosMercado(){

        
        $sql = "SELECT id FROM datosmercado WHERE fechaActualizacion = ?";
        $datosMercado = R::getCol($sql, array($this->fechaActualizacion));           
        
        
        if (!(empty($datosMercado))){
            
//            echo "<pre>";
//            print_r("Se encontraron datos con fecha del día de hoy.");
//            echo "</pre>";
            $datosMercado = implode(",", $datosMercado);
            
            $sql = "DELETE FROM datosmercado WHERE id IN ({$datosMercado})";
            $result = R::exec($sql); 
            
            return true;
        }else{
//            echo "<pre>";
//            print_r("No se encontraron datos del día de la fecha.");
//            echo "</pre>";
            return false;
        }
    }  
    
    
    public function borrarLatam(){

        
        $sql = "SELECT id FROM latam WHERE fechaActualizacion = ?";
        $latam = R::getCol($sql, array($this->fechaActualizacion));           
        
        
        if (!(empty($latam))){
            
//            echo "<pre>";
//            print_r("Se encontraron datos con fecha del día de hoy.");
//            echo "</pre>";
            $latam = implode(",", $latam);
            
            $sql = "DELETE FROM latam WHERE id IN ({$latam})";
            $result = R::exec($sql); 
            
            return true;
        }else{
//            echo "<pre>";
//            print_r("No se encontraron datos del día de la fecha.");
//            echo "</pre>";
            return false;
        }
    }  
    
    
    public function borrarTreasuries(){

        
        $sql = "SELECT id FROM treasuries WHERE fechaActualizacion = ?";
        $treasuries = R::getCol($sql, array($this->fechaActualizacion));           
        
        
        if (!(empty($treasuries))){
            
//            echo "<pre>";
//            print_r("Se encontraron datos con fecha del día de hoy.");
//            echo "</pre>";
            $treasuries = implode(",", $treasuries);
            
            $sql = "DELETE FROM treasuries WHERE id IN ({$treasuries})";
            $result = R::exec($sql); 
            
            return true;
        }else{
//            echo "<pre>";
//            print_r("No se encontraron datos del día de la fecha.");
//            echo "</pre>";
            return false;
        }
    }  
    
    
    
    
    
    
    
    //Importar los bonos que vienen de la planilla Bonos.
    public function getImportarFlujosAllBonos(){

        $planilla = $this->planilla;        

        //Taer los nombres de los bonos, sólo los que tengan asignado el libro excel Bonos 
        $this->load->model('Bono_model');
        
        //Traigo todos los bonos que vengan de la planilla bonos o de la planilla provinciales según sea el caso.
        if($planilla == 'bonos'){
            $bonos = $this->Bono_model->getBonosBonos();
        }else{
            $bonos = $this->Bono_model->getBonosProvinciales();
        }

        //Esto vá en el Log
//        echo "<pre>";
//        print_r("Listado de bonos dados de alta, que existen en la tabla: ");
//        echo "</pre>";
//        if($bonos){
//            foreach ($bonos as $bono){
//                echo "<pre>";
//                print_r($bono['nombre'] . ', ');
//            }
//        }
//        echo "</pre>";
        
        
        
        //Conectar con el excel
        try {
            if($planilla == 'bonos'){
                $file = 'BONOS.xlsm';
                $mensajeResultado = "Importación de Flujos Bonos.";
                $msgLog.="Importando flujos de planilla bonos." . chr(13).chr(10);                                
            }else{
                $file = 'PROVINCIALES DIARIO.xlsm';
                $mensajeResultado = "Importación de Flujos Provinciales.";
                $msgLog.="Importando flujos de planilla provinciales." . chr(13).chr(10);                
            }        
        
            //Cambiar esto en Test:
            $inputFileName = '/var/research/' . $file;
            //Cambiar esto en local:
            //$inputFileName = 'C:\';

            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $lala = PHPExcel_IOFactory::createReader($inputFileType); 
            $worksheetList = $lala->listWorksheetNames($inputFileName);
        
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        
        $fechaActualizacion = new DateTime('NOW');
        $fechaActualizacion = $fechaActualizacion->format('Y-m-d');             
        
        foreach ($bonos as $bono){ //Para cada Bono
            
            $sheetname = $bono['hoja'];
            $bonoNombre = $bono['nombre'];
            
            $this->hoja = $sheetname;
            $bonoValido = $this->Flujo_model->validarBonos();
            
            if ($bonoValido == 1){ // Si el bono tiene tildada la opción de actualizar automáticamente.
                if(in_array($sheetname, $worksheetList)){ // Si el bono aparece en el listado de hojas del Excel

                    //Log
//                    print_r("Bono " . $sheetname . " existe en el excel.");
                    $msgLog.="Bono " . $bonoNombre . " existe en el excel." . chr(13).chr(10);                


                    $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
//                    PHPExcel_Calculation::getInstance($objPHPExcel)->cyclicFormulaCount = 1;   
                    $objReader->setReadDataOnly(true);
                    $objReader->setLoadSheetsOnly($sheetname);
                    $objPHPExcel = $objReader->load($inputFileName);
                    $sheet = $objPHPExcel->getSheetByName($sheetname);
                    
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

                    if  (
                        $nombreHojas[42] == 'valor nominal actualizado' &&
                        $nombreHojas[43] == 'valor residual actualizado' && 
                        $nombreHojas[55] == 'cupon amortizacion cada 1 vn' &&
                        $nombreHojas[56] == 'cupon interes cada 1 vn' && 
                        $nombreHojas[57] == 'total cupones'
                            )    
                        {
                            $aprobado = 1;
                    }
                    
                    unset($nombreHojas);
                    
                    if($planilla == 'provinciales'){
                        $aprobado = 1;
                    }
                    
                    
                    if($aprobado == 1){ 
                        //Log
                        $msgLog.="Los títulos son correctos" . chr(13).chr(10);                
                        
//////////////////////Borrar ///////////////////////////////////////////////////
                  
                        
                    $bonosImportados++;    
                    
                    //Log
//                    echo "<pre>";
//                    print_r("El sheetname es: ");
//                    print_r($sheetname);
                    $msgLog.="El sheetname es: " . $sheetname . chr(13).chr(10);                

//                    print_r(" número: ". $bonosImportados);
//                    echo "<pre>"; 
                    
                    
                    $this->load->model('Bono_model');
                    $this->Bono_model->bono = $bonoNombre;
                    $id = $this->Bono_model->getBonoId();
                    $id = $id['id'];
                    
                    //Log
//                    echo "<pre>";
//                    print_r("El id es: ");
//                    print_r($id);
//                    echo "<pre>";  
                    $msgLog.="El id es: " . $id . chr(13).chr(10);                

                    
                    $this->fechaActualizacion = $fechaActualizacion;
                    $this->bono_id = $id;
                    $bonoBorrado = $this->Flujo_model->borrarFlujosBonos();
                    
                    //Log
                    if($bonoBorrado == true){
                        $msgLog.="Se borró la info del bono " . $bonoNombre . " con fecha del día de hoy." . chr(13).chr(10);                
                    }else{
                        $msgLog.="No se borraron flujos del bono: " . $bonoNombre . ". No se encontraron flujos con fecha del día de hoy." . chr(13).chr(10);                
                    }
                    
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        $highestRow = $sheet->getHighestRow();
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //Aca Fecha es fecha pagos, revisar eso
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        for ($row = 19; $row <= $highestRow; $row++){
                            $fechapagos = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
                            if($fechapagos == null){
                                $newHighestRow = $row - 1;
                                break;
                            } 
                        }
                        for ($row = 19; $row <= $newHighestRow; $row++){        


                            $fechapagos = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
                            $fechapagos = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($fechapagos));                          

                            //Esto no sé si es necesario, lo hacía con fecha
                            //Si la fecha viene en formato 01-01-10 la convierte.
                            $patrón = '/^([VE]-)?[0-9]{2}-[0-9]{2}-[0-9]{2}/';         
                            if(preg_match($patrón, $fecha, $coincidencias)){
                                $valores = explode('-', $fecha);
                                $newfecha = ($valores[2].'-'.$valores[0].'-'.$valores[1]);
                                $fechapagos = date('d-M-y', strtotime($newfecha));
                            }

                            //Esta validación de fechas la agregué, verificar si está bien.
                            if($this->Flujo_model->validarfechas($fechapagos)){
                                
                                
                                
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                
                                $vr = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue(); //$vr = (int)$vr;
                                $amortizacion = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue(); //$amortizacion = (int)$amortizacion;
                                $interes = $sheet->getCellByColumnAndRow(7,$row)->getOldCalculatedValue(); 
                                
                                $valorN = $sheet->getCellByColumnAndRow(13,$row)->getFormattedValue();
                                $valorS = $sheet->getCellByColumnAndRow(14,$row)->getFormattedValue();
                                $cuponAmortizacion = $sheet->getCellByColumnAndRow(26,$row)->getOldCalculatedValue();
                                $cuponInteres = $sheet->getCellByColumnAndRow(27,$row)->getOldCalculatedValue();
                                $totalFlujo = $sheet->getCellByColumnAndRow(28,$row)->getOldCalculatedValue();
                                
                                //Log
//                                echo"<pre>";
//                                print_r('Bono: ' . $bonoNombre . 'Bono Hoja: ' . $sheetname . 'Flujo: ' . $totalFlujo). chr(13).chr(10);
//                                $msgLog.="Total Flujo: " . $totalFlujo . chr(13).chr(10);                

                                
                                $totalFlujo = round($totalFlujo, 15); 

                                $flujo = R::dispense('flujo');

                                
                                
                                $flujo->bono_id = $id;
                                $flujo->fechapagos = $fechapagos;
                                $flujo->vr = (double)$vr;                       //$flujo->vr = number_format($vr, 2, '.', '');
                                $flujo->amortizacion = (double)$amortizacion;   //$flujo->amortizacion = (double)$amortizacion;
                                $flujo->interes = $interes;                     //$flujo->interes = number_format($interes, 6, '.', '');
                                $flujo->VNActualizado = (double)$valorN;
                                $flujo->VRActualizado = (double)$valorS;
                                $flujo->cuponAmortizacion = (double)$cuponAmortizacion;
                                $flujo->cuponInteres = (double)$cuponInteres;
                                $flujo->totalFlujo = (double)$totalFlujo;
                                $flujo->fechaActualizacion = $fechaActualizacion;
                                                                
                                R::store($flujo);
                                
                                
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                
//                                echo"<pre>";
//                                print_r("Bono: " . $sheetname . ' Fecha de Pagos: ' . $flujo->fechapagos . ' VR: ' . $flujo->vr . ' Amortización: ' . $flujo->amortizacion . ' Interés: '. $flujo->interes . 'ValorN: ' . $valorN. ' ValorS: ' . $valorS . ' Cupon Amortización: '. $cuponAmortizacion . ' Cupon Interés: '. $cuponInteres . ' Total Flujo: '. $totalFlujo);
//                                echo"</pre>";  
                            }else{
                                $mensajeError.= "Problema con las fechas";

                                break;
                            }
                        }
                        
                        
                        
                    } else {
                        
                        $mensajeError.= ("Títulos incorrectos en " . $sheetname);
                        
                    }
                    
                    
                    
                }else{//If in array sheet
//                    
                    
                    $mensajeResultado.= "No existe el bono " . $sheetname . " en el excel.";
                    
//                    $mensajeResultado.= "Bonos importados: " . $bonosImportados;
                    
                }
            }else{
                
                $mensajeError.= "El bono " . $sheetname . " no tiene tildada la opción de actualización automática.";
                
            }    
            
            
//            unset($objReader);
//            unset($objPHPExcel);
//            unset($sheet);
            
            
        } 
        
        $mensajeResultado.= "Bonos importados: " . $bonosImportados;
        $msgLog.= $mensajeResultado . chr(13).chr(10);                

        
        
        $resultado = array('resultado'=>'Ok', 'mensaje'=>$mensajeResultado, 'log'=>$msgLog);
        return $resultado;
        
        
    }
  
    
    
    public function getImportarEstructurasBonos(){

        //Taer los nombres de los bonos, sólo los que tengan asignado el libro excel Bonos
//        $this->load->model('estructuraBono_model');
//        $estructuraBonos = $this->estructuraBono_model->getEstructuraBonos();
        
//        echo "<pre>";
//        print_r("Listado de bonos dados de alta, que existen en la tabla: ");
//        echo "<pre>";
        
//        if($estructuraBonos)  {
//            foreach ($estructuraBonos as $estructuraBono){
//                echo "<pre>";
//                print_r($estructuraBono['bono'] . ', ');
//            }
//        }
//        echo "</pre>";

        //Conectar con el excel
        try {
            //Cambiar esto en test
//            $file = 'Estructuras de Bonos.xlsx';
            
            $file = 'Estructuras de Bonos para WS.xlsx';
            
            $inputFileName = '/var/research/SOL/Web/' . $file;
            //Cambiar esto en local        
            //$inputFileName = 'C:\BONOS.xlsm';       
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
            $worksheetList = $objReader->listWorksheetNames($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        $mensajeResultado = ("Importación de Estructura de bonos. ");

        $fechaActualizacion = new DateTime('NOW');
        $fechaActualizacion = $fechaActualizacion->format('Y-m-d');     

        $msgLog.="Importando Estructura de bonos." . chr(13).chr(10);

        
        
            $sheetname = $worksheetList[0];
                                        
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
                    
                    PHPExcel_Calculation::getInstance($objPHPExcel)->cyclicFormulaCount = 1;  
                    
                    $objReader->setReadDataOnly(true);
                    $objReader->setLoadSheetsOnly($sheetname);  
                    $objPHPExcel = $objReader->load($inputFileName);
                    $sheet = $objPHPExcel->getSheetByName($sheetname);

                    $aprobado = 0;                    
                    for ($row = 2; $row < 3; $row++){
                        for($column = 0; $column < 58; $column++){
                            $nombreHoja = str_replace(
                                                    array('á','é','í','ó','ú'),
                                                    array('a','e','i','o','u'),
                                                    $sheet->getCellByColumnAndRow($column,$row)->getValue()
                                                );
                            $nombreHoja = strtolower($nombreHoja);                    
                            $nombreHojas[] = $nombreHoja;       
                        }
                    }

                        if  (
                            $nombreHojas[0] == 'especie byma' &&
                            $nombreHojas[1] == 'tipo de instrumento para el calculo del impuesto' &&
                            $nombreHojas[2] == 'tipo de ajuste' &&
                            $nombreHojas[3] == 'tipo de instrumento' &&
                            $nombreHojas[4] == 'nombre conocido' &&
                            $nombreHojas[5] == 'tipo de emisor' &&
                            $nombreHojas[6] == 'emisor' &&
                            $nombreHojas[7] == 'moneda de cobro' &&
                            $nombreHojas[8] == 'moneda de emision o actualizacion' &&
                            $nombreHojas[9] == 'cer inicial' &&
                            $nombreHojas[10] == 'dias previos al pago del cupon para tomar el cer' &&
                            $nombreHojas[11] == 'especie caja' &&
                            $nombreHojas[12] == 'isin' &&
                            $nombreHojas[13] == 'nombre' &&
                            $nombreHojas[14] == 'fecha de emision' &&
                            $nombreHojas[15] == 'fecha de vencimiento' &&
                            $nombreHojas[16] == 'oustanding (mln)' &&
                            $nombreHojas[17] == 'ley' &&
                            $nombreHojas[18] == 'amortizacion' &&
                            $nombreHojas[19] == 'tipo de tasa' &&
                            $nombreHojas[20] == 'tipo de tasa variable' &&
                            $nombreHojas[21] == 'spread s/tasa variable contractural en p.b.' &&
                            $nombreHojas[22] == 'tasa minima (tna)' &&
                            $nombreHojas[23] == 'tasa maxima (tna)' &&
                            $nombreHojas[24] == 'cupon anual' &&
                            $nombreHojas[25] == 'cantidad de cupones por año' &&
                            $nombreHojas[26] == 'frecuencia de cobro de intereses' &&
//                            $nombreHojas[27] == 'fechas de corbro del cupon de interes' &&
                            $nombreHojas[28] == 'formula de calculo de intereses' &&
                            $nombreHojas[29] == 'dias previos para el record date' &&
                            $nombreHojas[30] == 'fecha proximo cobro de interes' &&
                            $nombreHojas[31] == 'proximo cobro de capital' &&
                            $nombreHojas[32] == 'duration' &&
                            $nombreHojas[33] == 'precio en moneda de origen c/1 vn' &&
                            $nombreHojas[34] == 'last ytm' &&
                            $nombreHojas[35] == 'paridad' &&
                            $nombreHojas[36] == 'current yield' &&
                            $nombreHojas[37] == 'intereses corridos' &&
                            $nombreHojas[38] == 'valor residual' &&
                            $nombreHojas[39] == 'valor tecnico' &&
                            $nombreHojas[40] == 'm. duration' &&
                            $nombreHojas[41] == 'convexity' &&
//                            $nombreHojas[42] == 'denominacion minima en vn' &&
                            $nombreHojas[43] == 'spread s/tasa variable actual en p.b.' &&
                            $nombreHojas[44] == 'Última tna' &&
                            $nombreHojas[45] == 'dias habiles antes del inicio del cupon' &&
                            $nombreHojas[46] == 'dias habiles antes del final del cupon' &&
                            $nombreHojas[47] == 'capitalizacion de intereses' &&
                                
                            $nombreHojas[49] == 'precio en pesos ($)' &&    
                                
                            $nombreHojas[50] == 'especies relacionadas' &&
                            $nombreHojas[51] == 'curva a la que pertenece' &&
                            $nombreHojas[52] == 'variable para curva' &&
                            $nombreHojas[53] == 'tna ulitma licitacion para el plazo' &&
                            $nombreHojas[54] == 'dias al vencimiento' &&
                            $nombreHojas[55] == 'var. desde licitacion -pb-' &&
                            $nombreHojas[56] == '1d-ch %' &&
                            $nombreHojas[57] == '1w-ch %'
 
                            
                            )    
                        {
                            $aprobado = 1;
                    }
                    
                    unset($nombreHojas);
                    
                    if($aprobado == 1){ 
                    ////////////////////////////////////////////////////////////

                        $highestRow = $sheet->getHighestRow();
                        
                        for ($row = 3; $row <= $highestRow; $row++){
                            $campo = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
//                            $fecha = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($fecha));
                            if($campo == null){
                                $newHighestRow = $row - 1;
                                break;
                            }
                        }
                        
                        
                        
                        $valido = true;
                        $error = '';

                        R::freeze(true);
                        R::begin();
                        
                        $bonoYaBorrado = array();
                        
                        for ($row = 3; $row <= $newHighestRow; $row++){
                            
//                            echo "<pre>";
//                            print_r("Son " . $newHighestRow . " filas");
//                            echo "</pre>";
                            

                            $especie = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
                            
                            //Log
                            //Muestro el nombre de la especie
                            //
                            //
                            $msgLog.="Importando bono: " . $especie . chr(13).chr(10);

                            
                            //Busco el id.
                            $this->load->model('Bono_model');
                            $this->Bono_model->bono = $especie;
                            $id = $this->Bono_model->getBonoId();
                            
                            //Si tiene un ID porque existe en la tabla:
                            if($id['id'] > 0){
                                
                                $bono = $id['id'];
                                
                                //Log
//                                echo "<pre>";
//                                print_r("");
                                $msgLog.= $especie . " es un bono existente. No se dará de alta." . chr(13).chr(10);

                                //
                                if(!(in_array($bono, $bonoYaBorrado, true))){

                                    
                                    //Log
//                                    echo "<pre>"; 
//                                    print_r("El bono no se repite en el excel: ");
//                                    print_r($bono);
//                                    echo "<pre>";  
                                    
                                    $msgLog.= $especie . " no se repite en el excel." . chr(13).chr(10);

                                    
                                    //// Borrar ////////////////////////////////////                                
                                    //Si tiene ya datos cargados ese día, esa misma especie.
                                    $this->fechaActualizacion = $fechaActualizacion;
                                    $this->bono = $bono;
                                    

                                    
                                    $bonoBorrado = $this->Flujo_model->borrarDatosEstructuraBonos();

                                    //Los bonos ya borrados van en un array
                                    array_push($bonoYaBorrado, $bono); 
                                    
                                    
                                   
                                    if($bonoBorrado == true){
                                        $msgLog.= "Se encontró y borró info del bono " . $especie . " con fecha del día de hoy." . chr(13).chr(10);
                                    }else{
                                        $msgLog.="No se borró info del bono: " . $especie . ". No se encontraron datos con fecha del día de hoy para esa especie.";
                                    }
                                     
                                    
                                    ////////////////////////////////////////////////
                                    ////////////////////////////////////////////////
                                }else{                                                                        
                                    $mensajeResultado.= "El bono: " . $especie . " se repite en el excel. No se borrarán los datos de este bono. Verificar por qué aparece dos veces.\n";
                                    $msgLog.= $mensajeResultado . chr(13).chr(10);

                                }                                
                            }else{
                                $mensajeResultado.= "Se dará de alta la especie: " . $especie . " en la tabla Bonos, porque no existe un bono con ese nombre.";
                                $msgLog.= $mensajeResultado . chr(13).chr(10);

                                $alta = R::dispense('bono');
                                $alta->nombre = $especie;
                                
                                $alta->emisor_id = 1;
                                $alta->tipobono_id = 1;
                                
                                R::store($alta);
                                
                                $this->load->model('Bono_model');
                                $this->Bono_model->bono = $especie;
                                $id = $this->Bono_model->getBonoId();
                                $bono = $id['id'];
                            }
                            
                            
                            $tipoInstrumentoImpuesto = $sheet->getCellByColumnAndRow(1,$row)->getFormattedValue();
                            $tipoAjuste = $sheet->getCellByColumnAndRow(2,$row)->getFormattedValue();
                            $tipoInstrumento = $sheet->getCellByColumnAndRow(3,$row)->getFormattedValue();
                            $nombreConocido = $sheet->getCellByColumnAndRow(4,$row)->getOldCalculatedValue();
                            $tipoEmisor = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue();
                            $emisor = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue();
                            $monedacobro = $sheet->getCellByColumnAndRow(7,$row)->getFormattedValue();
                            $monedaEmision = $sheet->getCellByColumnAndRow(8,$row)->getFormattedValue();
                            $cerInicial = $sheet->getCellByColumnAndRow(9,$row)->getFormattedValue();
                            if(($cerInicial == '') or ($cerInicial == '#REF!') or ($cerInicial == '#N/A')){
                                $cerInicial = null;
                            }
                            $diasPreviosCer = $sheet->getCellByColumnAndRow(10,$row)->getFormattedValue();
                            if(($diasPreviosCer == '') or ($diasPreviosCer == '#REF!') or ($diasPreviosCer == '#N/A')){
                                $diasPreviosCer = null;
                            }
                            $especieCaja = $sheet->getCellByColumnAndRow(11,$row)->getOldCalculatedValue();
                            if(($especieCaja == '') or ($especieCaja == '#REF!') or ($especieCaja == '#N/A')){
//                                $especieCaja = $sheet->getCellByColumnAndRow(11,$row)->getFormattedValue();
                                $especieCaja = null;
                            }
                            $isin = $sheet->getCellByColumnAndRow(12,$row)->getOldCalculatedValue();
                            if(($isin == '') or ($isin == '#REF!') or ($isin == '#N/A')){
                                $isin = null;
                            }
                            $nombre = $sheet->getCellByColumnAndRow(13,$row)->getOldCalculatedValue();
                            if(($nombre == '') or ($nombre == '#REF!') or ($nombre == '#N/A')){
                                $nombre = null;
                            }
                            
                            //Se cambió a getOldCalculatedValue porque getFormatted value dá eror 24/10/2014
//                            $fechaEmision = $sheet->getCellByColumnAndRow(14,$row)->getOldCalculatedValue();
//                            $fechaEmision = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($fechaEmision)); 
//                            if(($fechaEmision == '') or ($fechaEmision == '#REF!') or ($fechaEmision == '#N/A')){
//                                $fechaEmision = null;
//                            }
                            
                            // Se cambia esto //////////////////////////////////
                            
                            $fechaEmision = $sheet->getCellByColumnAndRow(14,$row)->getOldCalculatedValue();
                            
                            if($fechaEmision == '') {
                                $fechaEmision = $sheet->getCellByColumnAndRow(14,$row)->getValue();
                            }
                            if(($fechaEmision == '#REF!') or ($fechaEmision == '#N/A')){
                                $fechaEmision = null;
                            }
                            if ($fechaEmision != null){
                                $fechaEmision = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($fechaEmision)); 
                            }

                            ////////////////////////////////////////////////////
                            
                            // Se cambia esto //////////////////////////////////
                            $fechaVencimiento = $sheet->getCellByColumnAndRow(15,$row)->getOldCalculatedValue();
                            
                            if($fechaVencimiento == '') {
                                $fechaVencimiento = $sheet->getCellByColumnAndRow(15,$row)->getValue();
                            }
                                                   
                            if(($fechaVencimiento == '#REF!') or ($fechaVencimiento == '#N/A')){
                                $fechaVencimiento = null;
                            }
                            
                            if ($fechaVencimiento != null){
                                $fechaVencimiento = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($fechaVencimiento)); 
                            }
                            
                            ////////////////////////////////////////////////////

                            $oustanding = $sheet->getCellByColumnAndRow(16,$row)->getOldCalculatedValue();
                            if(($oustanding == '') or ($oustanding == '#REF!') or ($oustanding == '#N/A')){
                                $oustanding = null;
                            }
                            $ley = $sheet->getCellByColumnAndRow(17,$row)->getFormattedValue();
                            if(($ley == '') or ($ley == '#REF!') or ($ley == '#N/A')){
                                $ley = null;
                            }                            
                            $amortizacion = $sheet->getCellByColumnAndRow(18,$row)->getFormattedValue();
                            if(($amortizacion == '') or ($amortizacion == '#REF!') or ($amortizacion == '#N/A')){
                                $amortizacion = null;
                            }
                            $tipoTasa = $sheet->getCellByColumnAndRow(19,$row)->getFormattedValue();
                            if(($tipoTasa == '') or ($tipoTasa == '#REF!') or ($tipoTasa == '#N/A')){
                                $tipoTasa = null;
                            }
                            $tipoTasaVariable = $sheet->getCellByColumnAndRow(20,$row)->getFormattedValue();
                            if(($tipoTasaVariable == '') or ($tipoTasaVariable == '#REF!') or ($tipoTasaVariable == '#N/A')){
                                $tipoTasaVariable = null;
                            }
                            $spread = $sheet->getCellByColumnAndRow(21,$row)->getFormattedValue();
                            if(($spread == '') or ($spread == '#REF!') or ($spread == '#N/A')){
                                $spread = null;
                            }
                            $tasaMinima = $sheet->getCellByColumnAndRow(22,$row)->getFormattedValue();
                            if(($tasaMinima == '') or ($tasaMinima == '#REF!') or ($tasaMinima == '#N/A')){
                                $tasaMinima = null;
                            }
                            $tasaMaxima = $sheet->getCellByColumnAndRow(23,$row)->getFormattedValue();
                            if(($tasaMaxima == '') or ($tasaMaxima == '#REF!') or ($tasaMaxima == '#N/A')){
                                $tasaMaxima = null;
                            }
                            $cuponAnual = $sheet->getCellByColumnAndRow(24,$row)->getOldCalculatedValue();
                            if(($cuponAnual == '') or ($cuponAnual == '#REF!') or ($cuponAnual == '#N/A')){
                                $cuponAnual = null;
                            }
                            $cantidadCuponesAnio = $sheet->getCellByColumnAndRow(25,$row)->getOldCalculatedValue();
                            if(($cantidadCuponesAnio == '') or ($cantidadCuponesAnio == '#REF!') or ($cantidadCuponesAnio == '#N/A')){
                                $cantidadCuponesAnio = null;
                            }
                            $frecuenciaCobro = $sheet->getCellByColumnAndRow(26,$row)->getFormattedValue();
                            
                            if(($frecuenciaCobro == '') or ($frecuenciaCobro == '#REF!') or ($frecuenciaCobro == '#N/A')){
                                $frecuenciaCobro = null;
                            }

                            // Se cambia esto //////////////////////////////////
                            $fechasCobroCupon = $sheet->getCellByColumnAndRow(27,$row)->getOldCalculatedValue();
                            
                            if($fechasCobroCupon == '') {
                                $fechasCobroCupon = $sheet->getCellByColumnAndRow(27,$row)->getValue();
                            }
                                                   
                            if(($fechasCobroCupon == '#REF!') or ($fechasCobroCupon == '#N/A')){
                                $fechasCobroCupon = null;
                            }
                            
                            if ($fechasCobroCupon != null){
                                $fechasCobroCupon = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($fechasCobroCupon)); 
                            }
                            
                            ////////////////////////////////////////////////////
                            
                            $formulaCalculoInteres = $sheet->getCellByColumnAndRow(28,$row)->getFormattedValue();
                            if(($formulaCalculoInteres == '') or ($formulaCalculoInteres == '#REF!') or ($formulaCalculoInteres == '#N/A')){
                                $formulaCalculoInteres = null;
                            }
                            $diasPreviosRecord = $sheet->getCellByColumnAndRow(29,$row)->getFormattedValue();
                            if(($diasPreviosRecord == '') or ($diasPreviosRecord == '#REF!') or ($diasPreviosRecord == '#N/A')){
                                $diasPreviosRecord = null;
                            }
                            
                            /////
//                            $proximoCobroInteres = $sheet->getCellByColumnAndRow(30,$row)->getOldCalculatedValue();
//                            if(($proximoCobroInteres == '') or ($proximoCobroInteres == '#REF!') or ($proximoCobroInteres == '#N/A')){
//                                $proximoCobroInteres = null;
//                            }
//                            $proximoCobroInteres = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($proximoCobroInteres)); 
                            
                            // Se cambió esto //////////////////////////////////
                            
                            $proximoCobroInteres = $sheet->getCellByColumnAndRow(30,$row)->getOldCalculatedValue();
                            
                            if($proximoCobroInteres == '') {
                                $proximoCobroInteres = $sheet->getCellByColumnAndRow(30,$row)->getValue();
                            }
                                                   
                            if(($proximoCobroInteres == '#REF!') or ($fechaEmision == '#N/A')){
                                $proximoCobroInteres = null;
                            }
                            
                            if ($proximoCobroInteres != null){
                                $proximoCobroInteres = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($proximoCobroInteres)); 
                            }
                            ////////////////////////////////////////////////////
                                                        
                            // Se cambió esto //////////////////////////////////
                            
//                            $proximoCobroCapital = $sheet->getCellByColumnAndRow(31,$row)->getOldCalculatedValue();
//                            if(($proximoCobroCapital == '') or ($proximoCobroCapital == '#REF!') or ($proximoCobroCapital == '#N/A')){
//                                $proximoCobroCapital = null;
//                            }
//                            $proximoCobroCapital = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($proximoCobroCapital)); 
                            
                            $proximoCobroCapital = $sheet->getCellByColumnAndRow(31,$row)->getOldCalculatedValue();
                            
                            if($proximoCobroCapital == '') {
                                $proximoCobroCapital = $sheet->getCellByColumnAndRow(31,$row)->getValue();
                            }
                                                   
                            if(($proximoCobroCapital == '#REF!') or ($fechaEmision == '#N/A')){
                                $proximoCobroCapital = null;
                            }
                            
                            if ($proximoCobroCapital != null){
                                $proximoCobroCapital = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($proximoCobroCapital)); 
                            }
                            
                            ////////////////////////////////////////////////////

                            //Revisar este caso de duration
                            $duration = $sheet->getCellByColumnAndRow(32,$row)->getOldCalculatedValue();
                            if(($duration == '') or ($duration == '#REF!') or ($duration == '#N/A') or ($duration == '#DIV/0!')){
                                $duration = null;                                
                            }
                            $precioMonedaOrigen = $sheet->getCellByColumnAndRow(33,$row)->getOldCalculatedValue();
                            if(($precioMonedaOrigen == '') or ($precioMonedaOrigen == '#REF!') or ($precioMonedaOrigen == '#N/A')){
                                $precioMonedaOrigen = null;
                            }
                            $lastYtm = $sheet->getCellByColumnAndRow(34,$row)->getOldCalculatedValue();
                            if(($lastYtm == '') or ($lastYtm == '#REF!') or ($lastYtm == '#N/A')  or ($lastYtm == '#VALUE!') or ($lastYtm == '#¡NUM!') or ($lastYtm == '#NUM!') ){
                                $lastYtm = null;
                            }
                            $paridad = $sheet->getCellByColumnAndRow(35,$row)->getOldCalculatedValue();
                            if(($paridad == '') or ($paridad == '#REF!') or ($paridad == '#N/A')){
                                $paridad = null;
                            }
                            $currentYield = $sheet->getCellByColumnAndRow(36,$row)->getOldCalculatedValue();
                            if(($currentYield == '') or ($currentYield == '#REF!') or ($currentYield == '#N/A')){
                                $currentYield = null;
                            }
                            $interesesCorridos = $sheet->getCellByColumnAndRow(37,$row)->getOldCalculatedValue();
                            if(($interesesCorridos == '') or ($interesesCorridos == '#REF!') or ($interesesCorridos == '#N/A')){
                                $interesesCorridos = null;
                            }
                            $valorResidual = $sheet->getCellByColumnAndRow(38,$row)->getOldCalculatedValue();
                            if(($valorResidual == '') or ($valorResidual == '#REF!') or ($valorResidual == '#N/A')){
                                $valorResidual = null;
                            }
                            $valorTecnico = $sheet->getCellByColumnAndRow(39,$row)->getOldCalculatedValue();
                            if(($valorTecnico == '') or ($valorTecnico == '#REF!') or ($valorTecnico == '#N/A')){
                                $valorTecnico = null;
                            }
                            //Revisar
                            $mDuration = $sheet->getCellByColumnAndRow(40,$row)->getOldCalculatedValue();
                            if(($mDuration == '') or ($mDuration == '#REF!') or ($mDuration == '#N/A') or ($mDuration == '#DIV/0!') or ($mDuration == '#¡DIV/0!')){
                                $mDuration = null;
                            }
                            //Revisar
                            $convexity = $sheet->getCellByColumnAndRow(41,$row)->getOldCalculatedValue();
                            if(($convexity == '') or ($convexity == '#REF!') or ($convexity == '#N/A') or ($convexity == '#VALUE!') or ($convexity == '#¡NUM!') or ($convexity == '#NUM!') ){
                                $convexity = null;
                            }
                            $denominacionMinima = $sheet->getCellByColumnAndRow(42,$row)->getOldCalculatedValue();
                            if(($denominacionMinima == '') or ($denominacionMinima == '#REF!') or ($denominacionMinima == '#N/A')){
                                $denominacionMinima = null;
                            }
                            $spreadSinTasa = $sheet->getCellByColumnAndRow(43,$row)->getOldCalculatedValue();
                            if(($spreadSinTasa == '') or ($spreadSinTasa == '#REF!') or ($spreadSinTasa == '#N/A')){
                                $spreadSinTasa = null;
                            }
                            $ultimaTna = $sheet->getCellByColumnAndRow(44,$row)->getOldCalculatedValue();
                            if(($ultimaTna == '') or ($ultimaTna == '#REF!') or ($ultimaTna == '#N/A')){
                                $ultimaTna = null;
                            }
                            $diasInicioCupon = $sheet->getCellByColumnAndRow(45,$row)->getCalculatedValue();
                            if(($diasInicioCupon == '') or ($diasInicioCupon == '#REF!') or ($diasInicioCupon == '#N/A')){
                                $diasInicioCupon = null;
                            }
                            $diasFinalCupon = $sheet->getCellByColumnAndRow(46,$row)->getCalculatedValue();
                            if(($diasFinalCupon == '') or ($diasFinalCupon == '#REF!') or ($diasFinalCupon == '#N/A')){
                                $diasFinalCupon = null;
                            }
                            $capitalizacionInteres = $sheet->getCellByColumnAndRow(47,$row)->getCalculatedValue();
                            if(($capitalizacionInteres == '') or ($capitalizacionInteres == '#REF!') or ($capitalizacionInteres == '#N/A')){
                                $capitalizacionInteres = null;
                            }
                            $precioPesos = $sheet->getCellByColumnAndRow(49,$row)->getOldCalculatedValue();
                            if(($precioPesos == '') or ($precioPesos == '#REF!') or ($precioPesos == '#N/A')){
                                $precioPesos = null;
                            }
                            $especiesRelacionadas = $sheet->getCellByColumnAndRow(50,$row)->getFormattedValue();
                            $curva = $sheet->getCellByColumnAndRow(51,$row)->getFormattedValue();
                            $variableCurva = $sheet->getCellByColumnAndRow(52,$row)->getFormattedValue();
                            $tnaUltimaLicitacion = $sheet->getCellByColumnAndRow(53,$row)->getFormattedValue();
                            if(($tnaUltimaLicitacion == '') or ($tnaUltimaLicitacion == '#REF!') or ($tnaUltimaLicitacion == '#N/A')){
                                $tnaUltimaLicitacion = null;
                            }
                            $diasVencimiento = $sheet->getCellByColumnAndRow(54,$row)->getFormattedValue();
                            if(($diasVencimiento == '') or ($diasVencimiento == '#REF!') or ($diasVencimiento == '#N/A')){
                                $diasVencimiento = null;
                            }
                            $variableLicitacionPb = $sheet->getCellByColumnAndRow(55,$row)->getFormattedValue();
                            if(($variableLicitacionPb == '') or ($variableLicitacionPb == '#REF!') or ($variableLicitacionPb == '#N/A')){
                                $variableLicitacionPb = null;
                            }
                            $cuponPbiD = $sheet->getCellByColumnAndRow(56,$row)->getOldCalculatedValue();
                            if(($cuponPbiD == '') or ($cuponPbiD == '#REF!') or ($cuponPbiD == '#N/A')){
                                $cuponPbiD = null;
                            }
                            $cuponPbiW = $sheet->getCellByColumnAndRow(57,$row)->getOldCalculatedValue();
                            if(($cuponPbiW == '') or ($cuponPbiW == '#REF!') or ($cuponPbiW == '#N/A')){
                                $cuponPbiW = null;
                            }

                            $estructuraBonos = R::dispense('estructurabono');
                            
                            if(!is_numeric($cerInicial) && !is_null($cerInicial)){
                                $error.="CER inicial inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($cerInicial == '#DIV/0!'){
                                $cerInicial = null;
                                $error.="Error en excel, imposible dividir por cero, columna CER inicial, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($diasPreviosCer) && !is_null($diasPreviosCer)){
                                $error.="Días previos al pago del cupón para tomar el CER inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($diasPreviosCer == '#DIV/0!'){
                                $diasPreviosCer = null;
                                $error.="Error en excel, imposible dividir por cero, columna Días previos al pago del cupón para tomar el CER, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($especieCaja) && !is_null($especieCaja)){
                                $error.="Especie Caja inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($especieCaja == '#DIV/0!'){
                                $especieCaja = null;
                                $error.="Error en excel, imposible dividir por cero, columna Especie Caja, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($oustanding) && !is_null($oustanding)){
                                $error.="Oustanding inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($oustanding == '#DIV/0!'){
                                $oustanding = null;
                                $error.="Error en excel, imposible dividir por cero, columna Oustanding, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($spread) && !is_null($spread)){
                                $error.="Spread inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($spread == '#DIV/0!'){
                                $spread = null;
                                $error.="Error en excel, imposible dividir por cero, columna Spread, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($tasaMinima) && !is_null($tasaMinima)){
                                $error.="Tasa mínima inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($tasaMinima == '#DIV/0!'){
                                $tasaMinima = null;
                                $error.="Error en excel, imposible dividir por cero, columna Tasa mínima, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($tasaMaxima) && !is_null($tasaMaxima)){
                                $error.="Tasa máxima inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($tasaMaxima == '#DIV/0!'){
                                $tasaMaxima = null;
                                $error.="Error en excel, imposible dividir por cero, columna Tasa máxima, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($cuponAnual) && !is_null($cuponAnual)){
                                $error.="Cupón anual inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($cuponAnual == '#DIV/0!'){
                                $cuponAnual = null;
                                $error.="Error en excel, imposible dividir por cero, columna Cupón anual, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($cantidadCuponesAnio) && !is_null($cantidadCuponesAnio)){
                                $error.="Cantidad de cupones por año inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($cantidadCuponesAnio == '#DIV/0!'){
                                $cantidadCuponesAnio = null;
                                $error.="Error en excel, imposible dividir por cero, columna Cantidad de cupones por año, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($duration) && !is_null($duration)){
                                $error.="Duration inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($duration == '#DIV/0!'){
                                $duration = null;
                                $error.="Error en excel, imposible dividir por cero, columna Duration, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($precioMonedaOrigen) && !is_null($precioMonedaOrigen)){
                                $error.="Precio en moneda de origen inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($precioMonedaOrigen == '#DIV/0!'){
                                $precioMonedaOrigen = null;
                                $error.="Error en excel, imposible dividir por cero, columna Precio en moneda de origen, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($lastYtm) && !is_null($lastYtm)){
                                $error.="Last YTM inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($lastYtm == '#DIV/0!'){
                                $lastYtm = null;
                                $error.="Error en excel, imposible dividir por cero, columna Last YTM, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($paridad) && !is_null($paridad)){
                                $error.="Paridad  inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($paridad == '#DIV/0!'){
                                $paridad = null;
                                $error.="Error en excel, imposible dividir por cero, columna Paridad, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($currentYield) && !is_null($currentYield)){
                                $error.="Current Yield inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($currentYield == '#DIV/0!'){
                                $currentYield = null;
                                $error.="Error en excel, imposible dividir por cero, columna Current Yield, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($interesesCorridos) && !is_null($interesesCorridos)){
                                $error.="Intereses corridos inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($interesesCorridos == '#DIV/0!'){
                                $interesesCorridos = null;
                                $error.="Error en excel, imposible dividir por cero, columna Intereses corridos, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($valorResidual) && !is_null($valorResidual)){
                                $error.="Valor Residual inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($valorResidual == '#DIV/0!'){
                                $valorResidual = null;
                                $error.="Error en excel, imposible dividir por cero, columna Valor Residual, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($valorTecnico) && !is_null($valorTecnico)){
                                $error.="Valor Técnico inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($valorTecnico == '#DIV/0!'){
                                $valorTecnico = null;
                                $error.="Error en excel, imposible dividir por cero, columna Valor Técnico, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($mDuration) && !is_null($mDuration)){
                                $error.="M. Duration inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($mDuration == '#DIV/0!'){
                                $mDuration = null;
                                $error.="Error en excel, imposible dividir por cero, columna M. Duration, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($convexity) && !is_null($convexity)){
                                $error.="Convexity  inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($convexity == '#DIV/0!'){
                                $convexity = null;
                                $error.="Error en excel, imposible dividir por cero, columna Convexity, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($denominacionMinima) && !is_null($denominacionMinima)){
                                $error.="Denominación Mínima en VN inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($denominacionMinima == '#DIV/0!'){
                                $denominacionMinima = null;
                                $error.="Error en excel, imposible dividir por cero, columna Denominación Mínima en VN, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($spreadSinTasa) && !is_null($spreadSinTasa)){
                                $error.="Spread s/tasa variable actual inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($spreadSinTasa == '#DIV/0!'){
                                $spreadSinTasa = null;
                                $error.="Error en excel, imposible dividir por cero, columna Spread s/tasa variable actual, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($ultimaTna) && !is_null($ultimaTna)){
                                $error.="Última TNA inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($ultimaTna == '#DIV/0!'){
                                $ultimaTna = null;
                                $error.="Error en excel, imposible dividir por cero, columna Última TNA, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($precioPesos) && !is_null($precioPesos)){
                                $error.="Precio en Pesos inv&aacutelido en {$row} <br>";
                                $valido = false;
                            }
                            if($precioPesos == '#DIV/0!'){
                                $precioPesos = null;
                                $error.="Error en excel, imposible dividir por cero, columna Precio en Pesos, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($tnaUltimaLicitacion) && !is_null($tnaUltimaLicitacion)){
                                $error.="tna Ultima Licitacion inválido en {$row} <br>";
                                $valido = false;
                            }
                            if($tnaUltimaLicitacion == '#DIV/0!'){
                                $tnaUltimaLicitacion = null;
                                $error.="Error en excel, imposible dividir por cero, columna Tna Ultima Licitacion, fila {$row} <br>";
                                $valido = false;
                            }
                            if(!is_numeric($diasVencimiento) && !is_null($diasVencimiento)){
                                $error.="Dias Vencimiento inválido en {$row} <br>";
                                $valido = false;
                            }
                            if($diasVencimiento == '#DIV/0!'){
                                $diasVencimiento = null;
                                $error.="Error en excel, imposible dividir por cero, columna Dias Vencimiento, fila {$row} <br>";
                                $valido = false;
                            } 
                            if(!is_numeric($variableLicitacionPb) && !is_null($variableLicitacionPb)){
                                $error.="Variable Licitacion Pb inválido en {$row} <br>";
                                $valido = false;
                            }
                            if($variableLicitacionPb == '#DIV/0!'){
                                $variableLicitacionPb = null;
                                $error.="Error en excel, imposible dividir por cero, columna Variable Licitacion Pb, fila {$row} <br>";
                                $valido = false;
                            } 
                            if(!is_numeric($cuponPbiD) && !is_null($cuponPbiD)){
                                $error.="Cupon Pbi D inválido en {$row} <br>";
                                $valido = false;
                            }
                            if($cuponPbiD == '#DIV/0!'){
                                $cuponPbiD = null;
                                $error.="Error en excel, imposible dividir por cero, columna Cupon Pbi D, fila {$row} <br>";
                                $valido = false;
                            } 
                            if(!is_numeric($cuponPbiW) && !is_null($cuponPbiW)){
                                $error.="Cupon Pbi W inválido en {$row} <br>";
                                $valido = false;
                            }
                            if($cuponPbiW == '#DIV/0!'){
                                $variableLicitacionPb = null;
                                $error.="Error en excel, imposible dividir por cero, columna Cupon Pbi W, fila {$row} <br>";
                                $valido = false;
                            } 

                            $estructuraBonos->bono_id = $bono;
                            $estructuraBonos->tipoInstrumentoImpuesto = $tipoInstrumentoImpuesto;
                            $estructuraBonos->tipoAjuste = $tipoAjuste;
                            $estructuraBonos->tipoInstrumento = $tipoInstrumento;
                            $estructuraBonos->nombreConocido = $nombreConocido;
                            $estructuraBonos->tipoEmisor = $tipoEmisor;
                            $estructuraBonos->emisor = $emisor;
                            $estructuraBonos->monedacobro = $monedacobro;
                            $estructuraBonos->monedaEmision = $monedaEmision;
                            $estructuraBonos->cerInicial = $cerInicial;
                            $estructuraBonos->diasPreviosCer = $diasPreviosCer;
                            $estructuraBonos->especieCaja = $especieCaja;
                            $estructuraBonos->isin = $isin;
                            $estructuraBonos->nombre = $nombre;
                            $estructuraBonos->fechaEmision = $fechaEmision;
                            $estructuraBonos->fechaVencimiento = $fechaVencimiento;
                            $estructuraBonos->oustanding = $oustanding;
                            $estructuraBonos->ley = $ley;
                            $estructuraBonos->amortizacion = $amortizacion;
                            $estructuraBonos->tipoTasa = $tipoTasa;
                            $estructuraBonos->tipoTasaVariable = $tipoTasaVariable;
                            $estructuraBonos->spread = $spread;
                            $estructuraBonos->tasaMinima = $tasaMinima;
                            $estructuraBonos->tasaMaxima = $tasaMaxima;
                            $estructuraBonos->cuponAnual = $cuponAnual;
                            $estructuraBonos->cantidadCuponesAnio = $cantidadCuponesAnio;
                            $estructuraBonos->frecuenciaCobro = $frecuenciaCobro;
                            $estructuraBonos->fechasCobroCupon = $fechasCobroCupon;
                            $estructuraBonos->formulaCalculoInteres = $formulaCalculoInteres;
                            $estructuraBonos->diasPreviosRecord = $diasPreviosRecord;
                            $estructuraBonos->proximoCobroInteres = $proximoCobroInteres;
                            $estructuraBonos->proximoCobroCapital = $proximoCobroCapital;
                            $estructuraBonos->duration = $duration;
                            $estructuraBonos->precioMonedaOrigen = $precioMonedaOrigen;
                            $estructuraBonos->lastYtm = $lastYtm;
                            $estructuraBonos->paridad = $paridad;
                            $estructuraBonos->currentYield = $currentYield;
                            $estructuraBonos->interesesCorridos = $interesesCorridos;
                            $estructuraBonos->valorResidual = $valorResidual;
                            $estructuraBonos->valorTecnico = $valorTecnico;
                            $estructuraBonos->mDuration = $mDuration;
                            $estructuraBonos->convexity = $convexity;
                            $estructuraBonos->denominacionMinima = $denominacionMinima;
                            $estructuraBonos->spreadSinTasa = $spreadSinTasa;
                            $estructuraBonos->ultimaTna = $ultimaTna;
                            $estructuraBonos->diasInicioCupon = $diasInicioCupon;
                            $estructuraBonos->diasFinalCupon = $diasFinalCupon;
                            $estructuraBonos->capitalizacionInteres = $capitalizacionInteres;
                            $estructuraBonos->precioPesos = $precioPesos;
                            $estructuraBonos->especiesRelacionadas = $especiesRelacionadas;
                            $estructuraBonos->curva = $curva;
                            $estructuraBonos->variableCurva = $variableCurva;
                            $estructuraBonos->tnaUltimaLicitacion = $tnaUltimaLicitacion;
                            $estructuraBonos->diasVencimiento = $diasVencimiento;
                            $estructuraBonos->variableLicitacionPb = $variableLicitacionPb;
                            $estructuraBonos->cuponPbiD = $cuponPbiD;
                            $estructuraBonos->cuponPbiW = $cuponPbiW;
                            $estructuraBonos->fechaActualizacion = $fechaActualizacion;
                            
                            $bonosImportados++; 

                            R::store($estructuraBonos);
                            
                            //Log
//                            echo "<pre>";
//                            print_r('Se importaron los datos de la Especie: ' . $especie);
//                            echo "<pre>";
                            
                            
                            
                        }
                        
                        if ($valido){
                            
                            $mensajeResultado.= $bonosImportados . " Bonos importados. ";
                            $msgLog.= $bonosImportados . " Bonos importados. " . chr(13).chr(10);
                            
                            R::commit();
                            $resultado = array('resultado'=>'Ok', 'mensaje'=>$mensajeResultado, 'log'=>$msgLog);
                        } else {
                            R::rollback();
                            $resultado = array('resultado'=>'Error', 'mensaje'=>$error, 'log'=>$msgLog . $error );
                        }

                        R::freeze(false); 

                        return $resultado;
                        
                        
                        
                    } else {

                        $error = 'Títulos inválidos.';
                        $resultado = array('resultado'=>'Error', 'mensaje'=>$error, 'log'=>$msgLog . $error);
                        return $resultado;
                    }      
        
                    $mensajeResultado.= $bonosImportados;

    }        
    
    
    
    

////////////////////////////////////////////////////////////////////////////////    

    public function getImportarDatosMercado(){

        //Conectar con el excel
        try {
            //Test
            $file = 'Estructuras de Bonos para WS.xlsx';
            $inputFileName = '/var/research/SOL/Web/' . $file;
            //local        
            //$inputFileName = 'C:\BONOS.xlsm';       
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
            $worksheetList = $objReader->listWorksheetNames($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        $fechaActualizacion = new DateTime('NOW');
        $fechaActualizacion = $fechaActualizacion->format('Y-m-d');     

        $sheetname = 'Datos de Mercado';
        
        $msgLog.="Importando " . $sheetname . chr(13).chr(10);
                                        
        $objReader = PHPExcel_IOFactory::createReader($inputFileType); 

        PHPExcel_Calculation::getInstance($objPHPExcel)->cyclicFormulaCount = 1;  

        $objReader->setReadDataOnly(true);
        $objReader->setLoadSheetsOnly($sheetname);  
        $objPHPExcel = $objReader->load($inputFileName);
        $sheet = $objPHPExcel->getSheetByName($sheetname);

        $aprobado = 0;                    
        for ($row = 1; $row < 2; $row++){
           for($column = 0; $column < 3; $column++){
                $nombreHoja = str_replace(
                                        array('á','é','í','ó','ú'),
                                        array('a','e','i','o','u'),
                                        $sheet->getCellByColumnAndRow($column,$row)->getValue()
                                    );
                $nombreHoja = strtolower($nombreHoja);                    
                $nombreHojas[] = $nombreHoja;       
            }
        }

        if  ($nombreHojas[0] == 'inputs'){
            $aprobado = 1;
        }
                    
        unset($nombreHojas);
                    
        if($aprobado == 1){ 
        ////////////////////////////////////////////////////////////            
            $mensajeResultado = ("Datos de Mercado importados. ");
            
            $highestRow = $sheet->getHighestRow();

//            for ($row = 1; $row <= $highestRow; $row++){
//                $campo = $sheet->getCellByColumnAndRow(1,$row)->getOldCalculatedValue();
//
//                if($campo == null){
//                    $newHighestRow = $row - 1;
//                    break;
//                }
//            }

            $mensajeResultado.= ($highestRow-1) . " datos.\n";
            
            $valido = true;
            $error = '';

            R::freeze(true);
            R::begin();

            $bonoYaBorrado = array();

//            print_r('newHighestRow');
//            print_r($newHighestRow);

            
            //Meter por acá la parte en la que borra toda la info del día de la fecha.                            

            //// Borrar ////////////////////////////////////                                
            //Si tiene ya datos cargados ese día, esa misma especie.
            $this->fechaActualizacion = $fechaActualizacion;
            $datosBorrados = $this->Flujo_model->borrarDatosMercado();


            
            if($datosBorrados){
                $msgLog.="Se encontraron y borraron datos del día de hoy." . chr(13).chr(10);
            }else{
                $msgLog.="No se borraron datos del día de hoy." . chr(13).chr(10);
            }

            
            for ($row = 2; $row <= $highestRow; $row++){



                
//              $test = $sheet->getCellByColumnAndRow(0,$row)->getOldCalculatedValue();
                $nombre = $sheet->getCellByColumnAndRow(0,$row)->getCalculatedValue();
//              $test = $sheet->getCellByColumnAndRow(0,$row)->getOldCalculatedValue();
//              $test = $sheet->getCellByColumnAndRow(0,$row)->getOldCalculatedValue();

//                echo "<pre>";
//                $msgLog.= $nombre . chr(13).chr(10);
//                echo "<pre>";

                $input = $sheet->getCellByColumnAndRow(2,$row)->getOldCalculatedValue();

//                echo "<pre>";
//                $msgLog.= $input . chr(13).chr(10);
//                echo "<pre>";

//                            if(($nombre == '') or ($nombre == '#REF!') or ($nombre == '#N/A')){
//                                $nombre = null;
//                            }

//                $this->load->model('DatosMercado_model');
                
                $datosMercado = R::dispense('datosmercado');
                                   
                $datosMercado->nombre = $nombre;
                $datosMercado->input = $input;
                $datosMercado->fechaActualizacion = $fechaActualizacion;

                R::store($datosMercado);

            }

            
//            $fechaNombre = new DateTime('NOW');
//            $fechaNombre = $fechaNombre->format('Ymd');  
//            $log_name = "test".$fechaNombre.".txt";
//            $page_name = "Calculadora";
//            $app_id = uniqid();//give each process a unique ID for differentiation

            /*
            ////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////
            //////////////////////////////////////////////////////////////////////
            //Log
//            $log = new Log('test','calculadora');
//            $log->log_msg('fizzy soda : 45 bubbles remaining per cubic centimeter');
            
//            if(file_exists('/var/www/calculadora/generador/'.$log_name)){                 
//                $log_name = 'a_default_log.txt'; 
//            }
              
            $log = fopen('/var/www/calculadora/generador/'.$log_name,'a');
            $log_line = join(array( date(DATE_RFC822), chr(13).chr(10), $page_name, chr(13).chr(10), $app_id, chr(13).chr(10), $msgLog ) );
            fwrite($log, $log_line."\n");
//            $this->log_msg("Closing log");
//            fclose($this->log);
            ////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////////////////////
            */
            
            
            
            if ($valido){

                $msgLog.= "Se importaron " . ($highestRow-1). " filas." . chr(13).chr(10);
                
                R::commit();
                $resultado = array('resultado'=>'Ok', 'mensaje'=>$mensajeResultado, 'log'=>$msgLog);
                
            } else {
                R::rollback();
                $resultado = array('resultado'=>'Error', 'mensaje'=>$error);
            }

            R::freeze(false); 

            return $resultado;


        } else {
//            print_r("Títulos incorrectos en Datos Mercado");
            $error = 'Títulos inválidos.';
            $resultado = array('resultado'=>'Error', 'mensaje'=>$error);
            return $resultado;
        } 

    }
////////////////////////////////////////////////////////////////////////////////
        
        
////////////////////////////////////////////////////////////////////////////////    

    public function getImportarLatam(){

        //Conectar con el excel
        try {
            //Test
            $file = 'Estructuras de Bonos para WS.xlsx';
            $inputFileName = '/var/research/SOL/Web/' . $file;
            //local        
            //$inputFileName = 'C:\BONOS.xlsm';       
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
            $worksheetList = $objReader->listWorksheetNames($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        $fechaActualizacion = new DateTime('NOW');
        $fechaActualizacion = $fechaActualizacion->format('Y-m-d');     

        $sheetname = 'LATAM';
        $msgLog.="Importando " . $sheetname . chr(13).chr(10);

                                        
        $objReader = PHPExcel_IOFactory::createReader($inputFileType); 

        PHPExcel_Calculation::getInstance($objPHPExcel)->cyclicFormulaCount = 1;  

        $objReader->setReadDataOnly(true);
        $objReader->setLoadSheetsOnly($sheetname);  
        $objPHPExcel = $objReader->load($inputFileName);
        $sheet = $objPHPExcel->getSheetByName($sheetname);

        $aprobado = 0;                    
        for ($row = 1; $row < 2; $row++){
           for($column = 0; $column < 15; $column++){
                $nombreHoja = str_replace(
                                        array('á','é','í','ó','ú'),
                                        array('a','e','i','o','u'),
                                        $sheet->getCellByColumnAndRow($column,$row)->getCalculatedValue()
                                    );
                $nombreHoja = strtolower($nombreHoja);                    
                $nombreHojas[] = $nombreHoja;       
            }
        }
        
        if  (
                $nombreHojas[0] == 'instr. (usd)' &&
                $nombreHojas[1] == 'coupon' &&
                $nombreHojas[2] == 'price' &&
                $nombreHojas[3] == 'cur. yield' &&
                $nombreHojas[4] == 'ask ytm' &&
                $nombreHojas[5] == 'dur.' &&
                $nombreHojas[6] == '1d ch (bp)' 
                )    
            {
                $aprobado = 1;
        }
                    
        unset($nombreHojas);
                    
        if($aprobado == 1){ 
        ////////////////////////////////////////////////////////////

                        
            
            $mensajeResultado = ("Datos Latam Importados. ");

            $highestRow = $sheet->getHighestRow();

            $mensajeResultado.= $highestRow . " datos.\n";


            $valido = true;
            $error = '';

            R::freeze(true);
            R::begin();

//            $bonoYaBorrado = array();

            //// Borrar ////////////////////////////////////                                
            //Si tiene ya datos cargados ese día, esa misma especie.
            $this->fechaActualizacion = $fechaActualizacion;
            $datosBorrados = $this->Flujo_model->borrarLatam();
            
            if($datosBorrados){
                $msgLog.= "Se encontraron y borraron datos del día de hoy." . chr(13).chr(10);
            }else{
                $msgLog.="No se borraron datos del día de hoy." . chr(13).chr(10);
            }

            
            for ($row = 3; $row <= $highestRow; $row++){

                $instrumento = $sheet->getCellByColumnAndRow(0,$row)->getCalculatedValue();

//                echo "<pre>";
//                print_r($instrumento);
//                echo "<pre>";

                $coupon = $sheet->getCellByColumnAndRow(1,$row)->getOldCalculatedValue();

//                echo "<pre>";
//                print_r($coupon);
//                echo "<pre>";
                
                $price = $sheet->getCellByColumnAndRow(2,$row)->getOldCalculatedValue();

//                echo "<pre>";
//                print_r($price);
//                echo "<pre>";
                
                $yield = $sheet->getCellByColumnAndRow(3,$row)->getOldCalculatedValue();

//                echo "<pre>";
//                print_r($yield);
//                echo "<pre>";
                
                $ytm = $sheet->getCellByColumnAndRow(4,$row)->getOldCalculatedValue();

//                echo "<pre>";
//                print_r($ytm);
//                echo "<pre>";
                
                $duration = $sheet->getCellByColumnAndRow(5,$row)->getOldCalculatedValue();

//                echo "<pre>";
//                print_r($duration);
//                echo "<pre>";
//                
                $bp = $sheet->getCellByColumnAndRow(6,$row)->getOldCalculatedValue();
//
//                echo "<pre>";
//                print_r($bp);
//                echo "<pre>";
                
                
                $latam = R::dispense('latam');
                                   
                $latam->instrumento = $instrumento;
                $latam->coupon = $coupon;
                $latam->price = $price;
                $latam->yield = $yield;
                $latam->ytm = $ytm;
                $latam->duration = $duration;
                $latam->bp = $bp;
                
                $latam->fechaActualizacion = $fechaActualizacion;

                R::store($latam);

            }

            if ($valido){

                $msgLog.= "Se importaron " . ($highestRow-1). " filas." . chr(13).chr(10);

                
                R::commit();
                $resultado = array('resultado'=>'Ok', 'mensaje'=>$mensajeResultado, 'log'=>$msgLog);
                
            } else {
                R::rollback();
                $resultado = array('resultado'=>'Error', 'mensaje'=>$error);
            }

            R::freeze(false); 

            return $resultado;


        } else {
            $msgLog.= "Títulos incorrectos en latam" . chr(13).chr(10);
            $error = 'Títulos inválidos.';
            $resultado = array('resultado'=>'Error', 'mensaje'=>$error, 'log'=>$msgLog);
            return $resultado;
        }
        
    }
        
////////////////////////////////////////////////////////////////////////////////
        
////////////////////////////////////////////////////////////////////////////////    

    public function getImportarTreasuries(){

        //Conectar con el excel
        try {
            //Test
            $file = 'Estructuras de Bonos para WS.xlsx';
            $inputFileName = '/var/research/SOL/Web/' . $file;
            //local        
            //$inputFileName = 'C:\BONOS.xlsm';       
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
            $worksheetList = $objReader->listWorksheetNames($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        $fechaActualizacion = new DateTime('NOW');
        $fechaActualizacion = $fechaActualizacion->format('Y-m-d');     

        $sheetname = 'US Treasuries';
        $msgLog.="Importando " . $sheetname . chr(13).chr(10);

                                        
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
//        PHPExcel_Calculation::getInstance($objPHPExcel)->cyclicFormulaCount = 1;  
        $objReader->setReadDataOnly(true);
        $objReader->setLoadSheetsOnly($sheetname);  
        $objPHPExcel = $objReader->load($inputFileName);
        $sheet = $objPHPExcel->getSheetByName($sheetname);


        
        
        $aprobado = 0;                    
        for ($row = 1; $row < 3; $row++){
           for($column = 0; $column < 8; $column++){
                $nombreHoja = str_replace(
                                        array('á','é','í','ó','ú'),
                                        array('a','e','i','o','u'),
                                        $sheet->getCellByColumnAndRow($column,$row)->getCalculatedValue()
                                    );
                $nombreHoja = strtolower($nombreHoja);                    
                $nombreHojas[] = $nombreHoja;       
            }
        }
        
        if  (
                $nombreHojas[0] == 'benchmark' &&
                $nombreHojas[4] == 'para memo diario' &&
                $nombreHojas[8] == 'us treas' &&
                $nombreHojas[10] == 'ytm' &&
                $nombreHojas[11] == '1d ch. (bp)' &&
                $nombreHojas[12] == 'semana' &&
                $nombreHojas[13] == 'mes' &&
                $nombreHojas[14] == 'año'
                )    
            {
                $aprobado = 1;
        }
                
        
        
        
        unset($nombreHojas);
                    
        if($aprobado == 1){ 

            $mensajeResultado = ("Treasuries Importados. ");

            $highestRow = $sheet->getHighestRow();
            
            $mensajeResultado.= $highestRow . " datos.\n";

            $valido = true;
            $error = '';

            R::freeze(true);
            R::begin();

//            $bonoYaBorrado = array();

            //// Borrar ////////////////////////////////////                                
            //Si tiene ya datos cargados ese día, esa misma especie.
            $this->fechaActualizacion = $fechaActualizacion;
            $datosBorrados = $this->Flujo_model->borrarTreasuries();
            
            if($datosBorrados){
                $msgLog.= "Se encontraron y borraron datos del día de hoy." . chr(13).chr(10);
            }else{
                $msgLog.="No se borraron datos del día de hoy." . chr(13).chr(10);
            }

            
            for ($row = 3; $row <= $highestRow; $row++){

                
               
                $usTreas = $sheet->getCellByColumnAndRow(0,$row)->getCalculatedValue();

//                echo "usTreas";
//                echo "<pre>";
//                print_r($usTreas);
//                echo "<pre>";

                $ytm = $sheet->getCellByColumnAndRow(2,$row)->getOldCalculatedValue();

//                echo "ytm";
//                echo "<pre>";
//                print_r($ytm);
//                echo "<pre>";
                
                
                $bp = $sheet->getCellByColumnAndRow(3,$row)->getOldCalculatedValue();
//                
//                echo "bp: ";
//                print_r($bp);
//                echo "<pre>";
                
                $semana = $sheet->getCellByColumnAndRow(4,$row)->getOldCalculatedValue();

//                echo "semana: ";
//                echo "<pre>";
//                print_r($semana);
//                echo "<pre>";
                
                $mes = $sheet->getCellByColumnAndRow(5,$row)->getOldCalculatedValue();

//                echo "mes";
//                echo "<pre>";
//                print_r($mes);
//                echo "<pre>";
                
                $anio = $sheet->getCellByColumnAndRow(6,$row)->getOldCalculatedValue();

//                echo "anio";
//                echo "<pre>";
//                print_r($anio);
//                echo "<pre>";
                
                
                
                
                $treasuries = R::dispense('treasuries');
                                   
                $treasuries->usTreas = $usTreas;
                $treasuries->ytm = $ytm;
                $treasuries->bp = $bp;
                $treasuries->semana = $semana;
                $treasuries->mes = $mes;
                $treasuries->anio = $anio;
                
                $treasuries->fechaActualizacion = $fechaActualizacion;

                R::store($treasuries);

            }

            if ($valido){
                
                
                $msgLog.= "Se importaron " . ($highestRow-1). " filas." . chr(13).chr(10);

                R::commit();
                $resultado = array('resultado'=>'Ok', 'mensaje'=>$mensajeResultado, 'log'=>$msgLog);
                
            } else {
                R::rollback();
                $resultado = array('resultado'=>'Error', 'mensaje'=>$error);
            }

            R::freeze(false); 

            return $resultado;


        } else {
//            print_r("Títulos incorrectos en Treasuries");
            $error = 'Títulos inválidos.';
            $resultado = array('resultado'=>'Error', 'mensaje'=>$error);
            return $resultado;
        }
        
    }
        
////////////////////////////////////////////////////////////////////////////////
             
        
        
        
        
        
        
        
        
    

    
}