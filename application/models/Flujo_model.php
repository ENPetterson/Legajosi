<?php

class Flujo_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $fechapagos;
    public $amortizacion;
    public $interes;
    
    
    public function getFechaActualizacion(){
        $fechaActualizacion = R::getAll('SELECT DISTINCT fechaActualizacion from flujo where bono = ? ', array($this->bono));
//        print_r($fechaActualizacion); die;
        
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
                id,
                bono,
                fechapagos,
                amortizacion,
                vr,
                interes
                FROM flujo
                WHERE bono = ?
                AND fechaActualizacion = ? 
                ORDER BY id"; 
        
        $resultado = R::getAll($sql, array($this->bono, $this->fecha));
        return $resultado;
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
        
//        echo "<pre>";
//        print_r($datos);
//        echo "</pre>";
//        die;
        
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
        $bono = R::getRow($sql, array($this->bono));        

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

        $sql = "SELECT id FROM flujo WHERE bono = ? AND fechaActualizacion = ?";
        $bono = R::getCol($sql, array($this->bono, $this->fechaActualizacion));           
        
        if (!(empty($bono))){
            
            echo "<pre>";
            print_r("Se encontraron datos con fecha del día de hoy del bono.");
            
            $bono = implode(",", $bono);
            
            $sql = "DELETE FROM flujo WHERE id IN ({$bono})";
            $result = R::exec($sql); 
            
            return true;
        }else{
            echo "<pre>";
            print_r("No se encontraron datos del día de la fecha del bono.");
            
            return false;
        }
    }    
    
    
    public function borrarDatosBonos(){

        $sql = "SELECT id FROM dato WHERE bono = ? AND fechaActualizacion = ?";
        $bono = R::getCol($sql, array($this->bono, $this->fechaActualizacion));           
        
        if (!(empty($bono))){
            
            echo "<pre>";
            print_r("Se encontraron datos con fecha del día de hoy del bono.");
            
            $bono = implode(",", $bono);
            
            $sql = "DELETE FROM dato WHERE id IN ({$bono})";
            $result = R::exec($sql); 
            
            return true;
        }else{
            echo "<pre>";
            print_r("No se encontraron datos del día de la fecha del bono.");
            
            return false;
        }
    }    
    
    
    
    
    
    


//    public function getImportarFlujos(){
//
//        //Taer los nombres de los bonos, 
//        $this->load->model('Bono_model');
//        $bonos = $this->Bono_model->getBonos();
//        
//        try {
////        Cambiar esto en test
//        $file = 'BONOS.xlsm';
//        $inputFileName = '/var/research/' . $file;
//
////        Cambiar esto en local        
////        $inputFileName = 'C:\BONOS.xlsm';
//        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
//
//        $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
//        $worksheetList = $objReader->listWorksheetNames($inputFileName);
//        
//        } catch(Exception $e) {
//            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
//        }
//
////        foreach ($bonos as $bono){
//
//            $sheetname = $this->bono;
//            
//            $fechaActualizacion = new DateTime('NOW');
//            $fechaActualizacion = $fechaActualizacion->format('Y-m-d');  
//            
//            $this->bono = $sheetname;
//            $this->fechaActualizacion = $fechaActualizacion;
//            $bonoFechaValida = $this->Flujo_model->validarBonosFecha();
//                  
//            if ($bonoFechaValida == false){ // Si el bono tiene tildada la opción de actualizar automáticamente.
//                                
//                if(in_array($sheetname, $worksheetList)){
//                    
//                    print_r("Hoja existe: " . $sheetname);
//
//                    $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
//                    $objReader->setReadDataOnly(true);
//                    $objReader->setLoadSheetsOnly($sheetname);
//                    $objPHPExcel = $objReader->load($inputFileName);
//                    $sheet = $objPHPExcel->getSheetByName($sheetname);
//                    
//                    $highestRow = $sheet->getHighestRow();
//                    
//                    for ($row = 19; $row <= $highestRow; $row++){
//                        $fechapagos = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
//                        if($fechapagos == null){
//                            $newHighestRow = $row - 1;
//                            break;
//                        } 
//                    }
//
////                    echo "<pre>";
////                    print_r($highestRow);
////                    echo "</pre>";
////                    
////                    echo "<pre>";
////                    print_r($newHighestRow);
////                    echo "<pre>";
//                    
//                    for ($row = 19; $row <= $newHighestRow; $row++){                        
//                            $fechapagos = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
//                            $fechapagos = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($fechapagos));
//                        
//                            $amortizacion = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue();
//                            $vr = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue();
//                            $interes = $sheet->getCellByColumnAndRow(7,$row)->getOldCalculatedValue();
//
//                            $amortizacion = str_replace('%', '', $amortizacion);
//                            $vr = str_replace('%', '', $vr);
//                            $interes = str_replace('%', '', $interes);          
//
//                            $flujo = R::dispense('flujo');
//
//                            $flujo->bono = $sheetname;
//                            $flujo->fechapagos = $fechapagos;
//                            $flujo->amortizacion = (double)$amortizacion;
//                            $flujo->vr = (double)$vr;
//                            $flujo->interes = (double)$interes;
//                            $flujo->fechaActualizacion = $fechaActualizacion;
//                            R::store($flujo);
//
//                            echo"<pre>";
//                            print_r($sheetname . ' ' . $fechapagos . ' ' . $amortizacion. ' ' . $vr . ' '. $interes);
//                            echo"</pre>";                            
//                    }
//                }else{
//                    echo "<pre>";
//                    print_r("NO existe la hoja " . $sheetname . " en el archivo excel");
//                    echo "<pre>";
//                }
//            }else{
//                echo "<pre>";
//                print_r("Este Bono ya fué importado en el día de hoy: " . $sheetname);
//                echo "<pre>";
//            }            
////        }
//    }
    
    
    
    //Importar los bonos que vienen de la planilla Bonos.
    public function getImportarFlujosAllBonos(){

        //Taer los nombres de los bonos, 
        $this->load->model('Bono_model');
        $bonos = $this->Bono_model->getBonosBonos();

        echo "<pre>";
        print_r("Listado de bonos dados de alta, que existen en la tabla: ");
        
        if($bonos)  {
            foreach ($bonos as $bono){
                print_r($bono['nombre'] . ', ');
            }
        }
        echo "</pre>";
        
        
        
               
        //Conectar con el excel
        try {
        //Cambiar esto en test
        $file = 'BONOS.xlsm';
        $inputFileName = '/var/research/' . $file;
        //Cambiar esto en local        
        //$inputFileName = 'C:\BONOS.xlsm';
        
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
        $worksheetList = $objReader->listWorksheetNames($inputFileName);
        
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        
        $fechaActualizacion = new DateTime('NOW');
        $fechaActualizacion = $fechaActualizacion->format('Y-m-d');     

        foreach ($bonos as $bono){
            $sheetname = $bono['hoja'];
            $this->bono = $sheetname;
            $bonoValido = $this->Flujo_model->validarBonos();
            
            
            print_r($bonoValido);
            
            if ($bonoValido == 1){ // Si el bono tiene tildada la opción de actualizar automáticamente.
                if(in_array($sheetname, $worksheetList)){
                    echo "<pre>";
                    print_r("Bono " . $sheetname . " existe en el excel.");
                    //Borrar
                    $this->fechaActualizacion = $fechaActualizacion;
                    $this->bono = $sheetname;
                    $bonoBorrado = $this->Flujo_model->borrarFlujosBonos();
                    if($bonoBorrado == true){
                        echo "<pre>";
                        print_r("Se borró la info del bono " . $sheetname . " con fecha del día de hoy.");
                    }else{
                        echo "<pre>";
                        print_r("No se borraron datos del bono: " . $sheetname . ". No se encontraron datos con fecha del día de hoy.");
                    }
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
                    $objReader->setReadDataOnly(true);
                    $objReader->setLoadSheetsOnly($sheetname);
                    $objPHPExcel = $objReader->load($inputFileName);
                    $sheet = $objPHPExcel->getSheetByName($sheetname);
                    
//                    print_r($sheet);
//                    echo "<pre>";
                   
                    $highestRow = $sheet->getHighestRow();
                    
                    
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
                            
                            $amortizacion = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue();
                            $vr = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue();
                            $interes = $sheet->getCellByColumnAndRow(7,$row)->getOldCalculatedValue();

                            $amortizacion = (int)$amortizacion;
                            $vr = (int)$vr;
                            
//                            $amortizacion = $amortizacion * 100;
//                            $vr = $vr * 100;
//                            $interes = str_replace('%', '', $interes);          

                            $flujo = R::dispense('flujo');

                            $flujo->bono = $sheetname;
                            $flujo->fechapagos = $fechapagos;
                            //Así
//                            $flujo->amortizacion = (double)$amortizacion;
//                            $flujo->vr = number_format($vr, 2, '.', '');
//                            $flujo->interes = number_format($interes, 6, '.', '');
                            //O Así
                            $flujo->amortizacion = $amortizacion;
                            $flujo->vr = $vr;
                            $flujo->interes = $interes;
                            
                            $flujo->fechaActualizacion = $fechaActualizacion;
                            R::store($flujo);
                            echo"<pre>";
                            print_r("Bono: " . $sheetname . ' Fecha de Pagos: ' . $flujo->fechapagos . ' Amortización: ' . $flujo->amortizacion. ' VR: ' . $flujo->vr . ' Interés: '. $flujo->interes);
                            echo"</pre>";                            
                    }
                }else{
                    echo "<pre>";
                    print_r("No existe el bono " . $sheetname . " en el excel.");
                    echo "<pre>";
                }
            }else{
                echo "<pre>";
                print_r("El bono " . $sheetname . " no tiene tildada la opción de actualización automática.");
                echo "<pre>";
            }    
        } 
    }
    
    
    //Importar los bonos que vienen de la planilla Provinciales.
    public function getImportarFlujosAllProvinciales(){

        
        //Taer los nombres de los bonos, 
        $this->load->model('Bono_model');
        $bonos = $this->Bono_model->getBonosProvinciales();
        
        
        echo "<pre>";
        print_r("Listado de bonos dados de alta, que existen en la tabla: ");
        
        foreach ($bonos as $bono){
            print_r($bono['nombre'] . ', ');
        }
                
        try {
//        Cambiar esto en test
        $file = 'PROVINCIALES DIARIO.xls';
        $inputFileName = '/var/research/' . $file;

//        Cambiar esto en local        
//        $inputFileName = 'C:\BONOS.xlsm';
        
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
        $worksheetList = $objReader->listWorksheetNames($inputFileName);
        
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        
        $fechaActualizacion = new DateTime('NOW');
        $fechaActualizacion = $fechaActualizacion->format('Y-m-d');     
        
        foreach ($bonos as $bono){
            $sheetname = $bono['hoja'];
            $this->bono = $sheetname;
            $bonoValido = $this->Flujo_model->validarBonos();                      
            if ($bonoValido == 1){ // Si el bono tiene tildada la opción de actualizar automáticamente.
                if(in_array($sheetname, $worksheetList)){
                    echo "<pre>";
                    print_r("Bono " . $sheetname . " existe en el excel.");
                    //Borrar
                    $this->fechaActualizacion = $fechaActualizacion;
                    $this->bono = $sheetname;
                    $bonoBorrado = $this->Flujo_model->borrarFlujosBonos();
                    if($bonoBorrado == true){
                        echo "<pre>";
                        print_r("Se borró la info del bono " . $sheetname . " con fecha del día de hoy.");
                    }else{
                        echo "<pre>";
                        print_r("No se borraron datos del bono: " . $sheetname . ". No se encontraron datos con fecha del día de hoy.");
                    }
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
                    $objReader->setReadDataOnly(true);
                    $objReader->setLoadSheetsOnly($sheetname);
                    $objPHPExcel = $objReader->load($inputFileName);
                    $sheet = $objPHPExcel->getSheetByName($sheetname);
                    
//                    print_r($sheet);
//                    echo "<pre>";
                   
                    $highestRow = $sheet->getHighestRow();
                    
                    
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
                            
                            $amortizacion = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue();
                            $vr = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue();
                            $interes = $sheet->getCellByColumnAndRow(7,$row)->getOldCalculatedValue();

                            $amortizacion = (int)$amortizacion;
                            $vr = (int)$vr;
                            
//                            $amortizacion = $amortizacion * 100;
//                            $vr = $vr * 100;
//                            $interes = str_replace('%', '', $interes);          

                            $flujo = R::dispense('flujo');

                            $flujo->bono = $sheetname;
                            $flujo->fechapagos = $fechapagos;
                            //Así
//                            $flujo->amortizacion = (double)$amortizacion;
//                            $flujo->vr = number_format($vr, 2, '.', '');
//                            $flujo->interes = number_format($interes, 6, '.', '');
                            //O Así
                            $flujo->amortizacion = $amortizacion;
                            $flujo->vr = $vr;
                            $flujo->interes = $interes;
                            
                            $flujo->fechaActualizacion = $fechaActualizacion;
                            R::store($flujo);
                            echo"<pre>";
                            print_r("Bono: " . $sheetname . ' Fecha de Pagos: ' . $flujo->fechapagos . ' Amortización: ' . $flujo->amortizacion. ' VR: ' . $flujo->vr . ' Interés: '. $flujo->interes);
                            echo"</pre>";                            
                    }
                }else{
                    echo "<pre>";
                    print_r("No existe el bono " . $sheetname . " en el excel.");
                    echo "<pre>";
                }
            }else{
                echo "<pre>";
                print_r("El bono " . $sheetname . " no tiene tildada la opción de actualización automática.");
                echo "<pre>";
            }       
        }
    }
    
    
    
    public function getImportarDatosAllBonos(){

        //Taer los nombres de los bonos, sólo los que tengan asignado el libro excel Bonos
        $this->load->model('Bono_model');
        $bonos = $this->Bono_model->getBonosBonos();
        
        echo "<pre>";
        print_r("Listado de bonos dados de alta, que existen en la tabla: ");
        echo "<pre>";
        foreach ($bonos as $bono){
            print_r($bono['nombre'] . ', ');
        }
        echo "</pre>";
        //Conectar con el excel
        try {
            //Cambiar esto en test
            $file = 'BONOS.xlsm';
            $inputFileName = '/var/research/' . $file;
            //Cambiar esto en local        
            //$inputFileName = 'C:\BONOS.xlsm';       
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
            $worksheetList = $objReader->listWorksheetNames($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        
        $fechaActualizacion = new DateTime('NOW');
        $fechaActualizacion = $fechaActualizacion->format('Y-m-d');     

        foreach ($bonos as $bono){//Para cada Bono

            $sheetname = $bono['hoja'];
//            $sheetname = 'AA22';

            $this->bono = $sheetname;
            $bonoValido = $this->Flujo_model->validarBonos();
                        
            if ($bonoValido == 1){ // Si el bono tiene tildada la opción de actualizar automáticamente.
                
                if(in_array($sheetname, $worksheetList)){ // Si el bono aparece en el listado de hojas del Excel
                        
                    echo "<pre>";
                    print_r("Bono " . $sheetname . " existe en el excel.");
                    
                    
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
                    
                    PHPExcel_Calculation::getInstance($objPHPExcel)->cyclicFormulaCount = 1;  
                    
                    $objReader->setReadDataOnly(true);
                    $objReader->setLoadSheetsOnly($sheetname);  
//                    $objReader->setLoadSheetsOnly([$sheetname, "A2E2-S", "carga"]);
//                    $objReader->setActiveSheetIndexByName($sheetname); // Esto no anda   
                    
                                      
                    
                    
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
                    
//                    if ($nombreHojas[3].$nombreHojas[32] == 'fechasflow' && 
//                        $nombreHojas[13].$nombreHojas[42] == 'vnactualizado' && 
//                        $nombreHojas[14].$nombreHojas[43] == 'vractualizado' && 
//                        $nombreHojas[26] == 'k' && 
//                        $nombreHojas[27] == 'i' && 
//                        $nombreHojas[28] == 'flujo')
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
                    
                    if($aprobado == 1){ 
                    ////////////////////////////////////////////////////////////
                    
                        echo "<pre>";
                        print_r("Los títulos son correctos");

                        //Borrar
                        $this->fechaActualizacion = $fechaActualizacion;
                        $this->bono = $sheetname;
                        $bonoBorrado = $this->Flujo_model->borrarDatosBonos();

                        if($bonoBorrado == true){
                            echo "<pre>";
                            print_r("Se borró la info del bono " . $sheetname . " con fecha del día de hoy.");
                        }else{
                            echo "<pre>";
                            print_r("No se borraron datos del bono: " . $sheetname . ". No se encontraron datos con fecha del día de hoy.");
                        }

                        
                        
                        
//                        echo "<pre>";
//                        var_dump($sheet->getCellByColumnAndRow(0,14)->getOldCalculatedValue());
//                        echo "</pre>";
//
//                        echo "<pre>";
//                        var_dump($sheet->getCellByColumnAndRow(6,10)->getOldCalculatedValue());
//                        echo "</pre>";   
//
//                        echo "<pre>";
//                        var_dump($sheet->getCellByColumnAndRow(4,10)->getOldCalculatedValue());
//                        echo "</pre>";                                
//
//                        echo "<pre>";
//                        var_dump($sheet->getCellByColumnAndRow(5,15)->getOldCalculatedValue());
//                        echo "</pre>";     
                        
                        
                        
                        
                        
                        
                        $highestRow = $sheet->getHighestRow();

                        for ($row = 19; $row <= $highestRow; $row++){
                            $fecha = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
//                            $fecha = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($fecha));
                            if($fecha == null){
                                $newHighestRow = $row - 1;
                                break;
                            } 
                        }
                        
                        for ($row = 19; $row <= $newHighestRow; $row++){     
                            $fecha = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
                            $fecha = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($fecha));
                                                                                  
                            //Si la fecha viene en formato 01-01-10 la convierte.
                            $patrón = '/^([VE]-)?[0-9]{2}-[0-9]{2}-[0-9]{2}/';         
                            if(preg_match($patrón, $fecha, $coincidencias)){
                                                               
                                $valores = explode('-', $fecha);
                                $newfecha = ($valores[2].'-'.$valores[0].'-'.$valores[1]);
                                $fecha = date('d-M-y', strtotime($newfecha));
                            }
                            
                            if($this->Flujo_model->validarfechas($fecha)){
                                   
                                $vr = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue();
                                $amortizacion = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue();
                                
                                $valorN = $sheet->getCellByColumnAndRow(13,$row)->getFormattedValue();
                                $valorS = $sheet->getCellByColumnAndRow(14,$row)->getFormattedValue();
                                $cuponAmortizacion = $sheet->getCellByColumnAndRow(26,$row)->getOldCalculatedValue();
                                $cuponInteres = $sheet->getCellByColumnAndRow(27,$row)->getOldCalculatedValue();
                                $totalFlujo = $sheet->getCellByColumnAndRow(28,$row)->getOldCalculatedValue();

                                $totalFlujo = round($totalFlujo, 15); 
                                
//                                
//                                echo "<pre>";
//                                var_dump($sheet->getCellByColumnAndRow(0,14)->getValue());
//                                echo "</pre>";
                                
//                                echo "<pre>";
//                                var_dump($sheet->getCellByColumnAndRow(0,14)->getCalculatedValue());
//                                echo "</pre>";
//                                
//                                echo "<pre>";
//                                var_dump($sheet->getCellByColumnAndRow(0,14)->getFormattedValue());
//                                echo "</pre>";
                                                      
//                                echo "<pre>";
//                                var_dump($sheet->getCellByColumnAndRow(0,14)->getOldCalculatedValue());
//                                echo "</pre>";

                                
//                                
//                                
//                                die;
//                                echo "<pre>";
//                                print_r($sheet->getCellByColumnAndRow(28,$row)->getValue());
//                                echo "</pre>";
                                
//                                echo "<pre>";
//                                var_dump($sheet->getCellByColumnAndRow(6,10)->getValue());
//                                echo "</pre>";
                                
//                                echo "<pre>";
//                                var_dump($sheet->getCellByColumnAndRow(6,10)->getCalculatedValue());
//                                echo "</pre>";
                                
//                                echo "<pre>";
//                                var_dump($sheet->getCellByColumnAndRow(6,10)->getFormattedValue());
//                                echo "</pre>";
                                                      
//                                echo "<pre>";
//                                var_dump($sheet->getCellByColumnAndRow(6,10)->getOldCalculatedValue());
//                                echo "</pre>";
                                
//                                PHPExcel_Calculation::getInstance($objPHPExcel)->cyclicFormulaCount = 1;
                                
//                                echo "<pre>";
//                                print_r($sheet->getCellByColumnAndRow(28,$row)->getFormattedValue());
//                                echo "</pre>";
                                
//                                echo "<pre>";
//                                var_dump($sheet->getCellByColumnAndRow(28,$row)->getValue());
//                                echo "</pre>";
                                
                                
//                                echo "<pre>";
//                                var_dump($sheet->getCellByColumnAndRow(28,$row)->getCalculatedValue());
//                                echo "</pre>";
//                                
//                                echo "<pre>";
//                                var_dump($sheet->getCellByColumnAndRow(28,$row)->getFormattedValue());
//                                echo "</pre>";
                                
//                                echo "<pre>";
//                                var_dump($sheet->getCellByColumnAndRow(28,$row)->getOldCalculatedValue());
//                                echo "</pre>";
//                                
                                
                                
                                
//                                $valorN = str_replace('%', '', $valorN) * 100;
//                                $valorS = str_replace('%', '', $valorS) * 100;
//                                $cuponAmortizacion = str_replace('%', '', $cuponAmortizacion);          

                                $datos = R::dispense('dato');

                                $datos->bono = $sheetname;
                                $datos->fecha = $fecha;
                                
                                $datos->vr = (double)$vr;
                                $datos->amortizacion = (double)$amortizacion;
                                
                                $datos->VNActualizado = (double)$valorN;
                                $datos->VRActualizado = (double)$valorS;
                                $datos->cuponAmortizacion = (double)$cuponAmortizacion;
                                $datos->cuponInteres = (double)$cuponInteres;
                                $datos->totalFlujo = (double)$totalFlujo;
                                $datos->fechaActualizacion = $fechaActualizacion;

                                R::store($datos);

                                echo"<pre>";
                                print_r('Bono: ' . $sheetname . ' Fecha: ' . $fecha . 'vr: ' . $vr . 'amortizacion: ' . $amortizacion . 'ValorN: ' . $valorN. ' ValorS: ' . $valorS . ' Cupon Amortización: '. $cuponAmortizacion . ' Cupon Interés: '. $cuponInteres . ' Total Flujo: '. $totalFlujo);
                                echo"</pre>";
                            }else{
                                break;
                            }
                        }
                    } else {
                        echo "<pre>";
                        print_r("La hoja " . $sheetname . "tiene mal los títulos." );
                        echo "<pre>";
                    }      
                }else{
                    echo "<pre>";
                    print_r("No existe el bono " . $sheetname . " en el excel.");
                    echo "<pre>";
                }
            }else{
                echo "<pre>";
                print_r("El bono " . $sheetname . " no tiene tildada la opción de actualización automática.");
                echo "<pre>";
            }          
        }
        
        ////////////////////////////////////////////////////////////////////////
//        foreach ($bonos as $bono){
//
////            $sheetname = $bono['hoja'];
////
////            $this->bono = $sheetname;
////            $bonoValido = $this->Flujo_model->validarBonos();
//                        
////            if ($bonoValido == 1){ // Si el bono tiene tildada la opción de actualizar automáticamente.
//            
//                $objReader->setLoadSheetsOnly($sheetname); 
//                $objPHPExcel = $objReader->load($inputFileName); 
//                $sheet = $objPHPExcel->getSheetByName($sheetname);
//                if($sheet){
//                    $aprobado = 0;                    
//                    for ($row = 17; $row < 19; $row++){
//                        for($column = 0; $column < 29; $column++){
//
//                            $nombreHoja = str_replace(
//                                                    array('á','é','í','ó','ú'),
//                                                    array('a','e','i','o','u'),
//                                                    $sheet->getCellByColumnAndRow($column,$row)->getValue()
//                                                );
//
//                            $nombreHoja = strtolower($nombreHoja);                    
//                            $nombreHojas[] = $nombreHoja;                                    
//                        }
//                    }
//                    
//                    if ($nombreHojas[3].$nombreHojas[32] == 'fechasflow' && 
//                        $nombreHojas[13].$nombreHojas[42] == 'vnactualizado' && 
//                        $nombreHojas[14].$nombreHojas[43] == 'vractualizado' && 
//                        $nombreHojas[55] == 'k' && 
//                        $nombreHojas[56] == 'i' && 
//                        $nombreHojas[57] == 'flujo'){
//                            $aprobado = 1;
//                    }
//                    
//                    unset($nombreHojas);
//                    
//                    if($aprobado == 1){ 
//
//                        $fechaActualizacion = new DateTime('NOW');
//                        $fechaActualizacion = $fechaActualizacion->format('Y-m-d');                       
//                        
//                        $this->load->model('Dato_model');
//                        $this->Dato_model->bono = $sheetname;
//                        $this->Dato_model->fechaActualizacion = $fechaActualizacion;
//                        $datos = $this->Dato_model->getDatosFecha();
//
//                        if ($datos){
//                            $this->Dato_model->datos = $datos;
//                            $eliminarDatos = $this->Dato_model->delDatos();
//                        }
//                        
//                        $highestRow = $sheet->getHighestRow();
//                        for ($row = 19; $row <= $highestRow; $row++){
//
//                            $fecha = $sheet->getCellByColumnAndRow(3,$row)->getFormattedValue();
//                            //Si la fecha viene en formato 01-01-10 la convierte.
//                            $patrón = '/[0-9]{2}-[0-9]{2}-[0-9]{2}/';         
//                            if(preg_match($patrón, $fecha, $coincidencias)){
//                                $valores = explode('-', $fecha);
//                                $newfecha = ($valores[2].'-'.$valores[0].'-'.$valores[1]);
//                                $fecha = date('d-M-y', strtotime($newfecha));
//                            }
//
//                            if($this->Flujo_model->validarfechas($fecha)){
//                                $valorN = $sheet->getCellByColumnAndRow(13,$row)->getFormattedValue();
//                                $valorS = $sheet->getCellByColumnAndRow(14,$row)->getFormattedValue();
//                                $cuponAmortizacion = $sheet->getCellByColumnAndRow(26,$row)->getOldCalculatedValue();
//                                $cuponInteres = $sheet->getCellByColumnAndRow(27,$row)->getOldCalculatedValue();
//                                $totalFlujo = $sheet->getCellByColumnAndRow(28,$row)->getOldCalculatedValue();
//
//                                $valorN = str_replace('%', '', $valorN);
//                                $valorS = str_replace('%', '', $valorS);
//                                $cuponAmortizacion = str_replace('%', '', $cuponAmortizacion);          
//                                
//                                $datos = R::dispense('dato');
//
//                                $datos->bono = $sheetname;
//                                $datos->fecha = $fecha;
//                                $datos->valorNominalActualizado = (double)$valorN;
//                                $datos->valorResidualActualizado = (double)$valorS;
//                                $datos->cuponAmortizacion = (double)$cuponAmortizacion;
//                                $datos->cuponInteres = (double)$cuponInteres;
//                                $datos->totalFlujo = (double)$totalFlujo;
//                                $datos->fechaActualizacion = $fechaActualizacion;
//                                
//                                R::store($datos);
//
//                                echo"<pre>";
//                                print_r($sheetname . ' ' . $fecha . ' ' . $valorN. ' ' . $valorS . ' '. $cuponAmortizacion . ' '. $cuponInteres . ' '. $totalFlujo);
//                                echo"</pre>";
//                            }else{
//                                break;
//                            }
//                        }
//                        
//                    } else {
//                        echo "<pre>";
//                        print_r("el bono tiene mal los títulos: " . $sheetname);
//                        echo "<pre>";
//                    } 
//                        
//                } else {
//                    echo "<pre>";                    
//                    print_r("No se pudo cargar: " . $sheetname);
//                    echo "<pre>";
//                }  
////            }
//        }
        ////////////////////////////////////////////////////////////////////////

    }    
    
//////////////
//////////////
//////////////
    
    
    
       public function getImportarDatosAllProvinciales(){

        //Taer los nombres de los bonos, sólo los que tengan asignado el libro excel Bonos
        $this->load->model('Bono_model');
        $bonos = $this->Bono_model->getBonosProvinciales();
        
        echo "<pre>";
        print_r("Listado de bonos dados de alta, que existen en la tabla: ");
        echo "<pre>";
        foreach ($bonos as $bono){
            print_r($bono['nombre'] . ', ');
        }
        echo "</pre>";
        //Conectar con el excel
        try {
            //Cambiar esto en test
            $file = 'PROVINCIALES DIARIO.xls';
            $inputFileName = '/var/research/' . $file;
            //Cambiar esto en local        
            //$inputFileName = 'C:\BONOS.xlsm';       
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
            $worksheetList = $objReader->listWorksheetNames($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
        
        $fechaActualizacion = new DateTime('NOW');
        $fechaActualizacion = $fechaActualizacion->format('Y-m-d');     

        foreach ($bonos as $bono){//Para cada Bono

            $sheetname = $bono['hoja'];
//            $sheetname = 'AA22';

            $this->bono = $sheetname;
            $bonoValido = $this->Flujo_model->validarBonos();
                        
            if ($bonoValido == 1){ // Si el bono tiene tildada la opción de actualizar automáticamente.
                
                if(in_array($sheetname, $worksheetList)){ // Si el bono aparece en el listado de hojas del Excel
                        
                    echo "<pre>";
                    print_r("Bono " . $sheetname . " existe en el excel.");
                    
                    
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
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
                    
//                    if ($nombreHojas[3].$nombreHojas[32] == 'fechasflow' && 
//                        $nombreHojas[13].$nombreHojas[42] == 'vnactualizado' && 
//                        $nombreHojas[14].$nombreHojas[43] == 'vractualizado' && 
//                        $nombreHojas[26] == 'k' && 
//                        $nombreHojas[27] == 'i' && 
//                        $nombreHojas[28] == 'flujo')
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
                    
                    if($aprobado == 1){ 
                    ////////////////////////////////////////////////////////////
                    
                        echo "<pre>";
                        print_r("Los títulos son correctos");

                        //Borrar
                        $this->fechaActualizacion = $fechaActualizacion;
                        $this->bono = $sheetname;
                        $bonoBorrado = $this->Flujo_model->borrarDatosBonos();

                        if($bonoBorrado == true){
                            echo "<pre>";
                            print_r("Se borró la info del bono " . $sheetname . " con fecha del día de hoy.");
                        }else{
                            echo "<pre>";
                            print_r("No se borraron datos del bono: " . $sheetname . ". No se encontraron datos con fecha del día de hoy.");
                        }

                        $highestRow = $sheet->getHighestRow();

                        for ($row = 19; $row <= $highestRow; $row++){
                            $fecha = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
//                            $fecha = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($fecha));
                            if($fecha == null){
                                $newHighestRow = $row - 1;
                                break;
                            } 
                        }
                        
                        for ($row = 19; $row <= $newHighestRow; $row++){     
                            $fecha = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
                            $fecha = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($fecha));
                                                                                  
                            //Si la fecha viene en formato 01-01-10 la convierte.
                            $patrón = '/^([VE]-)?[0-9]{2}-[0-9]{2}-[0-9]{2}/';         
                            if(preg_match($patrón, $fecha, $coincidencias)){
                                                               
                                $valores = explode('-', $fecha);
                                $newfecha = ($valores[2].'-'.$valores[0].'-'.$valores[1]);
                                $fecha = date('d-M-y', strtotime($newfecha));
                            }
                            
                            if($this->Flujo_model->validarfechas($fecha)){
                                   
                                
                                $vr = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue();
                                $amortizacion = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue();
                                
                                $valorN = $sheet->getCellByColumnAndRow(13,$row)->getFormattedValue();
                                $valorS = $sheet->getCellByColumnAndRow(14,$row)->getFormattedValue();
                                $cuponAmortizacion = $sheet->getCellByColumnAndRow(26,$row)->getOldCalculatedValue();
                                $cuponInteres = $sheet->getCellByColumnAndRow(27,$row)->getOldCalculatedValue();
                                $totalFlujo = $sheet->getCellByColumnAndRow(28,$row)->getOldCalculatedValue();

                                $totalFlujo = round($totalFlujo, 15);
                                
//                                $valorN = str_replace('%', '', $valorN);
//                                $valorS = str_replace('%', '', $valorS);
//                                $cuponAmortizacion = str_replace('%', '', $cuponAmortizacion);          

                                $datos = R::dispense('dato');

                                $datos->bono = $sheetname;
                                $datos->fecha = $fecha;
                                
                                $datos->vr = (double)$vr;
                                $datos->amortizacion = (double)$amortizacion;
                                
                                $datos->valorNominalActualizado = (double)$valorN;
                                $datos->valorResidualActualizado = (double)$valorS;
                                $datos->cuponAmortizacion = (double)$cuponAmortizacion;
                                $datos->cuponInteres = (double)$cuponInteres;
                                $datos->totalFlujo = (double)$totalFlujo;
                                $datos->fechaActualizacion = $fechaActualizacion;

                                R::store($datos);

                                echo"<pre>";
                                print_r('Bono: ' . $sheetname . ' Fecha: ' . $fecha . ' vr: ' . $vr . ' Amortizacion: ' . $amortizacion . ' ValorN: ' . $valorN. ' ValorS: ' . $valorS . ' Cupon Amortización: '. $cuponAmortizacion . ' Cupon Interés: '. $cuponInteres . ' Total Flujo: '. $totalFlujo);
                                echo"</pre>";
                            }else{
                                break;
                            }
                        }
                    } else {
                        echo "<pre>";
                        print_r("La hoja " . $sheetname . "tiene mal los títulos." );
                        echo "<pre>";
                    }      
                }else{
                    echo "<pre>";
                    print_r("No existe el bono " . $sheetname . " en el excel.");
                    echo "<pre>";
                }
            }else{
                echo "<pre>";
                print_r("El bono " . $sheetname . " no tiene tildada la opción de actualización automática.");
                echo "<pre>";
            }           
        }
    }     
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function getImportarEstructurasBonos(){

        //Taer los nombres de los bonos, sólo los que tengan asignado el libro excel Bonos
        $this->load->model('estructuraBono_model');
        $estructuraBonos = $this->estructuraBono_model->getEstructuraBonos();
        
        echo "<pre>";
        print_r("Listado de bonos dados de alta, que existen en la tabla: ");
        echo "<pre>";
        
        if($estructuraBonos)  {
            foreach ($estructuraBonos as $estructuraBono){
                print_r($estructuraBono['especieByma'] . ', ');
            }
        }
        echo "</pre>";

        //Conectar con el excel
        try {
            //Cambiar esto en test
            $file = 'Estructuras de Bonos.xlsx';
            $inputFileName = '/var/research/' . $file;
            //Cambiar esto en local        
            //$inputFileName = 'C:\BONOS.xlsm';       
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
            $worksheetList = $objReader->listWorksheetNames($inputFileName);
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }

        $fechaActualizacion = new DateTime('NOW');
        $fechaActualizacion = $fechaActualizacion->format('Y-m-d');     

            $sheetname = $worksheetList[0];
                                        
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
                    
                    PHPExcel_Calculation::getInstance($objPHPExcel)->cyclicFormulaCount = 1;  
                    
                    $objReader->setReadDataOnly(true);
                    $objReader->setLoadSheetsOnly($sheetname);  
                    $objPHPExcel = $objReader->load($inputFileName);
                    $sheet = $objPHPExcel->getSheetByName($sheetname);

                    $aprobado = 0;                    
                    for ($row = 2; $row < 3; $row++){
                        for($column = 0; $column < 50; $column++){
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
                            $nombreHojas[49] == 'precio en pesos ($)'
                            )    
                        {
                            $aprobado = 1;
                    }
                    
                    unset($nombreHojas);
                    
                    if($aprobado == 1){ 
                    ////////////////////////////////////////////////////////////
                    //Borrar
                    //$this->fechaActualizacion = $fechaActualizacion;
                    //    $this->bono = $sheetname;
                    //    $bonoBorrado = $this->Flujo_model->borrarDatosBonos();
                    //
                    //     if($bonoBorrado == true){
                    //         echo "<pre>";
                    //         print_r("Se borró la info del bono " . $sheetname . " con fecha del día de hoy.");
                    //     }else{
                    //         echo "<pre>";
                    //         print_r("No se borraron datos del bono: " . $sheetname . ". No se encontraron datos con fecha del día de hoy.");
                    //}
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
                        
                        
                        echo "<pre>";
                        print_r($highestRow);
                        echo "</pre>";
                        
                        
                        
                        $valido = true;
                        $error = '';

                        R::freeze(true);
                        R::begin();
                        
                        
                        for ($row = 3; $row <= $newHighestRow; $row++){

                            $especieByma = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
                            $tipoInstrumentoImpuesto = $sheet->getCellByColumnAndRow(1,$row)->getFormattedValue();
                            $tipoAjuste = $sheet->getCellByColumnAndRow(2,$row)->getFormattedValue();
                            $tipoInstrumento = $sheet->getCellByColumnAndRow(3,$row)->getFormattedValue();
                            $nombreConocido = $sheet->getCellByColumnAndRow(4,$row)->getOldCalculatedValue();

                            $tipoEmisor = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue();
                            $emisor = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue();
                            $monedacobro = $sheet->getCellByColumnAndRow(7,$row)->getFormattedValue();
                            $monedaEmision = $sheet->getCellByColumnAndRow(8,$row)->getFormattedValue();

                            
                            
                            
//                            $cerInicial = $sheet->getCellByColumnAndRow(9,$row)->getFormattedValue();
//                            if($cerInicial == ''){
//                                $cerInicial = null;
//                            }
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

                            $fechaEmision = $sheet->getCellByColumnAndRow(14,$row)->getFormattedValue();
                            $fechaEmision = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($fechaEmision)); 
                            if(($fechaEmision == '') or ($fechaEmision == '#REF!') or ($fechaEmision == '#N/A')){
                                $fechaEmision = null;
                            }
                            
                            $fechaVencimiento = $sheet->getCellByColumnAndRow(15,$row)->getOldCalculatedValue();
                            $fechaVencimiento = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($fechaVencimiento)); 
                            if(($fechaVencimiento == '') or ($fechaVencimiento == '#REF!') or ($fechaVencimiento == '#N/A')){
                                $fechaVencimiento = null;
                            }
                            
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
                            
                            ///////
                            
                            $frecuenciaCobro = $sheet->getCellByColumnAndRow(26,$row)->getFormattedValue();
                            if(($frecuenciaCobro == '') or ($frecuenciaCobro == '#REF!') or ($frecuenciaCobro == '#N/A')){
                                $frecuenciaCobro = null;
                            }
                            
                            $fechasCobroCupon = $sheet->getCellByColumnAndRow(27,$row)->getFormattedValue();
                            if(($fechasCobroCupon == '') or ($fechasCobroCupon == '#REF!') or ($fechasCobroCupon == '#N/A')){
                                $fechasCobroCupon = null;
                            }
                            
                            $formulaCalculoInteres = $sheet->getCellByColumnAndRow(28,$row)->getFormattedValue();
                            if(($formulaCalculoInteres == '') or ($formulaCalculoInteres == '#REF!') or ($formulaCalculoInteres == '#N/A')){
                                $formulaCalculoInteres = null;
                            }
                            
                            $diasPreviosRecord = $sheet->getCellByColumnAndRow(29,$row)->getFormattedValue();
                            if(($diasPreviosRecord == '') or ($diasPreviosRecord == '#REF!') or ($diasPreviosRecord == '#N/A')){
                                $diasPreviosRecord = null;
                            }
                            
                            $proximoCobroInteres = $sheet->getCellByColumnAndRow(30,$row)->getOldCalculatedValue();
                            if(($proximoCobroInteres == '') or ($proximoCobroInteres == '#REF!') or ($proximoCobroInteres == '#N/A')){
                                $proximoCobroInteres = null;
                            }
                            $proximoCobroInteres = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($proximoCobroInteres)); 

                            
                            
                            $proximoCobroCapital = $sheet->getCellByColumnAndRow(31,$row)->getOldCalculatedValue();
                            if(($proximoCobroCapital == '') or ($proximoCobroCapital == '#REF!') or ($proximoCobroCapital == '#N/A')){
                                $proximoCobroCapital = null;
                            }
                            $proximoCobroCapital = gmdate("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($proximoCobroCapital)); 

                            //Revisar este caso de duration
                            $duration = $sheet->getCellByColumnAndRow(32,$row)->getOldCalculatedValue();
                            if(($duration == '') or ($duration == '#REF!') or ($duration == '#N/A')){
                                $duration = null;                                
                            }
                            
                            
                            
                            
                            $precioMonedaOrigen = $sheet->getCellByColumnAndRow(33,$row)->getOldCalculatedValue();
                            if(($precioMonedaOrigen == '') or ($precioMonedaOrigen == '#REF!') or ($precioMonedaOrigen == '#N/A')){
                                $precioMonedaOrigen = null;
                            }

                            $lastYtm = $sheet->getCellByColumnAndRow(34,$row)->getOldCalculatedValue();
                            if(($lastYtm == '') or ($lastYtm == '#REF!') or ($lastYtm == '#N/A')  or ($lastYtm == '#VALUE!')  ){
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
                            if(($mDuration == '') or ($mDuration == '#REF!') or ($mDuration == '#N/A') or ($mDuration == '#DIV/0!')){
                                $mDuration = null;
                            }
                                                        
                            //Revisar
                            $convexity = $sheet->getCellByColumnAndRow(41,$row)->getOldCalculatedValue();
                            if(($convexity == '') or ($convexity == '#REF!') or ($convexity == '#N/A') or ($convexity == '#VALUE!')){
                                $convexity = null;
                            }

//
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
                            
                            
                            ///
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
                            
                            
                            $estructuraBonos->especieByma = $especieByma;
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
                            $estructuraBonos->fechaActualizacion = $fechaActualizacion;



                            print_r('-----------------------------------------------------------------------');
                            echo "<pre>";
                            print_r('especieByma: ' . $especieByma);
                            echo "<pre>";
                       

                                R::store($estructuraBonos);

//                                echo"<pre>";
//                                print_r('Bono: ' . $especieByma . ' Fecha: ' . $tipoInstrumentoImpuesto . 'vr: ' . $tipoAjuste . 'amortizacion: ' . $tipoInstrumento . 'ValorN: ' . $nombreConocido. ' ValorS: ' . $valorS);
//                                print_r('Bono: ' . $especieByma);
//                                echo"</pre>";
//                            }else{
//                                break;
//                            }
                        }
                        
                        if ($valido){
                            R::commit();
                            $resultado = array('resultado'=>'OK');
                        } else {
                            R::rollback();
                            $resultado = array('resultado'=>'Error', 'mensaje'=>$error);
                        }

                        R::freeze(false); 

                        return $resultado;
                        
                        
                        
                    } else {
                        
                        $error = 'Títulos inválidos.';
                        $resultado = array('resultado'=>'Error', 'mensaje'=>$error);
                        return $resultado;
                        
//                        echo "<pre>";
//                        print_r("La hoja " . $sheetname . "tiene mal los títulos." );
//                        echo "<pre>";
                    }      
//                }else{
//                    echo "<pre>";
//                    print_r("No existe el bono " . $sheetname . " en el excel.");
//                    echo "<pre>";
//                }
//            }else{
//                echo "<pre>";
//                print_r("El bono " . $sheetname . " no tiene tildada la opción de actualización automática.");
//                echo "<pre>";
//            }          
//        }
        

    }        
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
//    public function getImportarFlujosAll(){
//
//        //Taer los nombres de los bonos, 
//        $this->load->model('Bono_model');
//        $bonos = $this->Bono_model->getBonos();
//        
//        try {
////        Cambiar esto en test
//        $file = 'BONOS.xlsm';
//        $inputFileName = '/var/research/' . $file;
//
////        Cambiar esto en local        
////        $inputFileName = 'C:\BONOS.xlsm';
//        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
//
//        $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
//        $worksheetList = $objReader->listWorksheetNames($inputFileName);
//        
//        } catch(Exception $e) {
//            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
//        }
//        
//
//        foreach ($bonos as $bono){
//
//            $sheetname = $bono['hoja'];
//
//            $this->bono = $sheetname;
//            $bonoValido = $this->Flujo_model->validarBonos();
//                        
//            if ($bonoValido == 1){ // Si el bono tiene tildada la opción de actualizar automáticamente.
//                
//                if(in_array($sheetname, $worksheetList)){
//                    
//                    print_r("Hoja existe: " . $sheetname);
//
//                    $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
//                    $objReader->setReadDataOnly(true);
//                    $objReader->setLoadSheetsOnly($sheetname);
//                    $objPHPExcel = $objReader->load($inputFileName);
//                    $sheet = $objPHPExcel->getSheetByName($sheetname);
//                    
//                    $highestRow = $sheet->getHighestRow();
//                    
//                    for ($row = 19; $row <= $highestRow; $row++){
//                        $fechapagos = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
//                        if($fechapagos == null){
//                            $newHighestRow = $row - 1;
//                            break;
//                        } 
//                    }
//                                        
//                    echo "<pre>";
//                    print_r($highestRow);
//                    echo "</pre>";
//                    
//                    echo "<pre>";
//                    print_r($newHighestRow);
//                    echo "<pre>";
//                    
////                    for ($row = 19; $row <= $newHighestRow; $row++){                        
////                            $fechapagos = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
////                            $fechapagos = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($fechapagos));
////                        
////                            $amortizacion = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue();
////                            $vr = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue();
////                            $interes = $sheet->getCellByColumnAndRow(7,$row)->getOldCalculatedValue();
////
////                            $amortizacion = str_replace('%', '', $amortizacion);
////                            $vr = str_replace('%', '', $vr);
////                            $interes = str_replace('%', '', $interes);          
////
////                            $flujo = R::dispense('flujo');
////
////                            $flujo->bono = $sheetname;
////                            $flujo->fechapagos = $fechapagos;
////                            $flujo->amortizacion = (double)$amortizacion;
////                            $flujo->vr = (double)$vr;
////                            $flujo->interes = (double)$interes;
////                            R::store($flujo);
////
////                            echo"<pre>";
////                            print_r($sheetname . ' ' . $fechapagos . ' ' . $amortizacion. ' ' . $vr . ' '. $interes);
////                            echo"</pre>";                            
////                    }
//                }else{
//                    echo "<pre>";
//                    print_r("NO existe" . $sheetname);
//                    echo "<pre>";
//                }
//            }            
//        }
//    }
    
    

//    public function getImportarFlujosss(){
//
//        //Taer los nombres de los bonos, 
//        $this->load->model('Bono_model');
//        $bonos = $this->Bono_model->getBonos();
//        
//        try {
////        Cambiar esto en test
//        $file = 'BONOS.xlsm';
//        $inputFileName = '/var/research/' . $file;
//
////        Cambiar esto en local        
////        $inputFileName = 'C:\BONOS.xlsm';
//        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
//
//        $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
//        $worksheetList = $objReader->listWorksheetNames($inputFileName);
//        
////        echo "<pre>";
////        print_r($worksheetList);
////        echo "</pre>";
//        } catch(Exception $e) {
//            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
//        }
//        
//
//        foreach ($bonos as $bono){
//
//            $sheetname = $bono['hoja'];
//
//            $this->bono = $sheetname;
//            $bonoValido = $this->Flujo_model->validarBonos();
//                        
//            if ($bonoValido == 1){ // Si el bono tiene tildada la opción de actualizar automáticamente.
//                
//                if(in_array($sheetname, $worksheetList)){
//                    
//                    print_r("Hoja existe: " . $sheetname);
//
//                    $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
//                    $objReader->setReadDataOnly(true);
//                    $objReader->setLoadSheetsOnly($sheetname);
//                    $objPHPExcel = $objReader->load($inputFileName);
////                    $sheet = $objPHPExcel->getActiveSheet();
//                    $sheet = $objPHPExcel->getSheetByName($sheetname);
//                    
//                    
////                $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
////                $objReader->setLoadSheetsOnly($sheetname); 
////                $objPHPExcel = $objReader->load($inputFileName); 
////                $sheet = $objPHPExcel->getSheetByName($sheetname);
//                    
//                    
//                    $highestRow = $sheet->getHighestRow();
//                    
//                    for ($row = 19; $row <= $highestRow; $row++){
//                        $fechapagos = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
//
////                        print_r($fechapagos);
////                        $lala = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($fechapagos));
////                        $fechapagos = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($fechapagos));
//                        
////                        print_r($fechapagos);
////                                               
////                        print_r($lala);
////
//
//                        
//
//                        if($fechapagos == null){
//                            $newHighestRow = $row - 1;
//                            break;
//                        } 
////                        if($fechapagos != null) {
////                            $newHighestRow = $row;
////                        }
//                    }
//                    
//                    
//                    echo "<pre>";
//                    print_r($highestRow);
//                    echo "</pre>";
//                    
//                    echo "<pre>";
//                    print_r($newHighestRow);
//                    echo "<pre>";
//                    
//                    for ($row = 19; $row <= $newHighestRow; $row++){
////                        $fechapagos = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
////                        //Si la fecha viene en formato 01-01-10 la convierte.
////                        $patrón = '/[0-9]{2}-[0-9]{2}-[0-9]{2}/';         
////                        if(preg_match($patrón, $fechapagos, $coincidencias)){
////                            $valores = explode('-', $fechapagos);
////                            $newfechapagos = ($valores[2].'-'.$valores[0].'-'.$valores[1]);
////                            $fechapagos = date('d-M-y', strtotime($newfechapagos));
////                        }
////
////                        if($this->Flujo_model->validarfechas($fechapagos)){
//                        
//                            $fechapagos = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
//                            $fechapagos = date('Y-m-d',PHPExcel_Shared_Date::ExcelToPHP($fechapagos));
//                        
//                            $amortizacion = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue();
//                            $vr = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue();
//
//                            //$interes = $sheet->getCellByColumnAndRow(7,$row)->getCalculatedValue();
//                            //$interes = $sheet->getCellByColumnAndRow(7,$row)->getValue();                    
//                            //$interes = $sheet->getCellByColumnAndRow(7,$row)->getFormattedValue();
//                            $interes = $sheet->getCellByColumnAndRow(7,$row)->getOldCalculatedValue();
//
//                            $amortizacion = str_replace('%', '', $amortizacion);
//                            $vr = str_replace('%', '', $vr);
//                            $interes = str_replace('%', '', $interes);          
//
//                            $flujo = R::dispense('flujo');
//
//                            $flujo->bono = $sheetname;
//                            $flujo->fechapagos = $fechapagos;
//                            $flujo->amortizacion = (double)$amortizacion;
//                            $flujo->vr = (double)$vr;
//                            $flujo->interes = (double)$interes;
//                            R::store($flujo);
//
//                            echo"<pre>";
//                            print_r($sheetname . ' ' . $fechapagos . ' ' . $amortizacion. ' ' . $vr . ' '. $interes);
//                            echo"</pre>";
////                        }else{
////                            break;
////                        }
//                            
//                    }
//
//                    
//                }else{
//                    echo "<pre>";
//                    print_r("NO existe" . $sheetname);
//                    echo "<pre>";
//                }
//            }            
//        }
//    }
    
   
    
//        public function getImportarFlujoss(){
//
//        //Taer los nombres de los bonos, 
//        $this->load->model('Bono_model');
//        $bonos = $this->Bono_model->getBonos();
//        
//        
////        Cambiar esto en test
//        $file = 'BONOS.xlsm';
//        $inputFileName = '/var/research/' . $file;
//        
////        $inputFileName = 'C:\BONOS.xlsm';
//        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
//
//        foreach ($bonos as $bono){
//
//            $sheetname = $bono['hoja'];
//
//            $this->bono = $sheetname;
//            $bonoValido = $this->Flujo_model->validarBonos();
//                        
//            if ($bonoValido == 1){ // Si el bono tiene tildada la opción de actualizar automáticamente.
//                
//                $objReader = PHPExcel_IOFactory::createReader($inputFileType); 
//                $objReader->setLoadSheetsOnly($sheetname); 
//                $objPHPExcel = $objReader->load($inputFileName); 
//                $sheet = $objPHPExcel->getSheetByName($sheetname);
//                if($sheet){
//                    $highestRow = $sheet->getHighestRow();
//                    for ($row = 19; $row <= $highestRow; $row++){
//                        $fechapagos = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
//                        
//                        //Si la fecha viene en formato 01-01-10 la convierte.
//                        $patrón = '/[0-9]{2}-[0-9]{2}-[0-9]{2}/';         
//                        if(preg_match($patrón, $fechapagos, $coincidencias)){
//                            $valores = explode('-', $fechapagos);
//                            $newfechapagos = ($valores[2].'-'.$valores[0].'-'.$valores[1]);
//                            $fechapagos = date('d-M-y', strtotime($newfechapagos));
//                        }
//
//                        if($this->Flujo_model->validarfechas($fechapagos)){
//                            $amortizacion = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue();
//                            $vr = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue();
//
//                            //$interes = $sheet->getCellByColumnAndRow(7,$row)->getCalculatedValue();
//                            //$interes = $sheet->getCellByColumnAndRow(7,$row)->getValue();                    
//                            //$interes = $sheet->getCellByColumnAndRow(7,$row)->getFormattedValue();
//                            $interes = $sheet->getCellByColumnAndRow(7,$row)->getOldCalculatedValue();
//
//                            $amortizacion = str_replace('%', '', $amortizacion);
//                            $vr = str_replace('%', '', $vr);
//                            $interes = str_replace('%', '', $interes);          
//
//                            $flujo = R::dispense('flujo');
//
//                            $flujo->bono = $sheetname;
//                            $flujo->fechapagos = $fechapagos;
//                            $flujo->amortizacion = (double)$amortizacion;
//                            $flujo->vr = (double)$vr;
//                            $flujo->interes = (double)$interes;
//                            R::store($flujo);
//
//                            echo"<pre>";
//                            print_r($sheetname . ' ' . $fechapagos . ' ' . $amortizacion. ' ' . $vr . ' '. $interes);
//                            echo"</pre>";
//                        }else{
//                            break;
//                        }
//                    }
//                } else {
//                    print_r("No se pudo cargar" . $sheetname);
//                }
//                                   
//            }
//            
//        }
//    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    
}