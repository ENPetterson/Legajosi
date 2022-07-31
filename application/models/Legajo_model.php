<?php

require_once APPPATH."/third_party/PHPExcel.php";

class Legajo_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $nombre;
    public $apellido;
    public $fechaNacimiento;
    public $tipoDocumento;
    public $cuil;      
    public $nacionalidad;
    public $estadoCivil;
    public $esDiscapacitado;
    public $email;
    public $sexo;
    public $cargo;
    public $fechaIngreso;
    public $fechaEgreso;
    public $fechaAntiguedad;
    public $diasVacaciones;
    public $sueldoBasico;
    public $observaciones;
    
    public function saveLegajo(){
        //R::debug(true);
        //Cuando solucionaste y ya anda bien comentás el r::debug
        $legajo = R::load('legajo', $this->id);
        $legajo->nombre = $this->nombre;
        $legajo->apellido = $this->apellido;
        $legajo->fechaNacimiento = $this->fechaNacimiento;
        $legajo->tipoDocumento = $this->tipoDocumento;
        $legajo->cuil = $this->cuil;
        $legajo->nacionalidad = $this->nacionalidad; 
        $legajo->estadoCivil = $this->estadoCivil; 
        $legajo->esDiscapacitado = $this->esDiscapacitado; 
        $legajo->email = $this->email; 
        $legajo->sexo = $this->sexo; 
        $legajo->cargo = $this->cargo; 
        $legajo->fechaIngreso = $this->fechaIngreso; 
        $legajo->fechaEgreso = $this->fechaEgreso; 
        $legajo->fechaAntiguedad = $this->fechaAntiguedad; 
        $legajo->diasVacaciones = $this->diasVacaciones;
        $legajo->sueldoBasico = $this->sueldoBasico; 
        $legajo->observaciones = $this->observaciones;               
        //$this->id = R::store($solicitud);
        
    
        foreach ($this->experienciaslaborales as $experiencialaboralArray) {

            $experiencialaboral = (object) $experiencialaboralArray;
            $experiencialaboralBean = R::load('experiencialaboral', $experiencialaboral->idExperiencialaboral);
            $experiencialaboralBean->legajo = $legajo;
            $experiencialaboralBean->experiencia = $experiencialaboral->experiencia;
            $experiencialaboralBean->empresa = $experiencialaboral->empresa;
            //$experiencialaboralBean->legajo_id = $experiencialaboral->legajo_id;
            $experiencialaboralBean->fechaInicio = $experiencialaboral->fechaInicio;
            $experiencialaboralBean->fechaSalida = $experiencialaboral->fechaSalida;
            $experiencialaboralBean->montoMensual = $experiencialaboral->montoMensual;
            $experiencialaboralBean->dependencia = $experiencialaboral->dependencia;
            $experiencialaboralBean->funciones = $experiencialaboral->funciones;

            R::store($experiencialaboralBean);
            
        }

        return $legajo->export();
    }
    
    public function getLegajo(){
        $legajo = R::load('legajo', $this->id);
        return $legajo->export();
    }
    
    public function getLegajosUsuario(){
        $legajos = R::getCol('select legajo_id from legajo_usuario where usuario_id = ?', array($this->usuario_id));
        return $legajos;
    }
    
    public function getLegajos(){
        $legajos = R::getAll('select * from legajo order by nombre');
        return $legajos;
    }

     public function getLegajosExperiencialaboral(){
//        $solicitud = $this->solicitud;    
        $legajos = R::getAll('SELECT 
    `p`.`id` AS `id`,
    `p`.`nombre` AS `nombre`,
    `p`.`apellido` AS `apellido`,
    `p`.`fechaNacimiento` AS `fechaNacimiento`,
    `p`.`tipoDocumento` AS `tipoDocumento`,
    `p`.`cuil` AS `cuil`,
    `e`.`nacionalidad` AS `nacionalidad`,
    `e`.`estadoCivil` AS `estadoCivil`,
    `e`.`esDiscapacitado` AS `esDiscapacitado`,
    `e`.`email` AS `email`,
    `e`.`sexo` AS `sexo`,
    `e`.`cargo` AS `cargo`,
    `e`.`fechaIngreso` AS `fechaIngreso`,
    `e`.`fechaEgreso` AS `fechaEgreso`,
    `e`.`fechaAntiguedad` AS `fechaAntiguedad`,
    `e`.`diasVacaciones` AS `diasVacaciones`,
    `e`.`sueldoBasico` AS `sueldoBasico`,
    `e`.`observaciones` AS `observaciones`,

    `s`.`id` AS `idExperiencialaboral`,
    `s`.`experiencia` AS `experiencia`,
    `s`.`empresa` AS `empresa`,
    `s`.`legajo_id` AS `legajo_id`,
    `s`.`fechaInicio` AS `fechaInicio`,
    `s`.`fechaSalida` AS `fechaSalida`,
    `s`.`montoMensual` AS `montoMensual`,
    `s`.`dependencia` AS `dependencia`,
    `s`.`funciones` AS `funciones`,

    FROM 
    `legajo` `e` 

    LEFT JOIN `experiencialaboral` `l` on `l`.`legajo_id` = `e`.`id`

    ORDER BY l.legajo_id DESC


                ');
        
        
        
        return $legajos;
        
    }

    public function getLegajoExperiencialaboralId(){
        $legajoBean = R::load('legajo', $this->id);
        $legajo = $legajoBean->export();
        $experienciaslaborales = array();
        foreach ($legajoBean->ownExperiencialaboral as $experiencialaboralBean){
            $experiencialaboral = $experiencialaboralBean->export();
            array_push($experienciaslaborales, $experiencialaboral);
        }
        $legajo['experienciaslaborales'] = $experienciaslaborales;
        
        return $legajo;
    }
    
    
    public function delLegajo(){
        $legajo = R::load('legajo', $this->id);
        R::trash($legajo);
    }
    
    public function assocUsuario(){
        $legajo = R::load('legajo', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($legajo, $usuario);
    }
    
    public function clearRelMenu(){
        $legajo = R::load('legajo', $this->id);
        R::clearRelations($legajo, 'menu');
    }
    
    public function assocMenu(){
        $legajo = R::load('legajo', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($legajo, $menu);
    }
    
    public function clearRelControlador(){
        $legajo = R::load('legajo', $this->id);
        R::clearRelations($legajo, 'controlador');
    }
    
    public function assocControlador(){
        $legajo = R::load('legajo', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($legajo, $controlador);
    }
    
    public function grabarExcel(){


        //R::debug(true);
                  
        $usuarioParam = $this->session->userdata('usuario');

        //$orden = R::load('legajo', $this->id);

        $this->load->helper('file');
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/tmp/';
       
        try {
            $inputFileName = $uploadDir . $this->archivo; 

            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);   
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);


        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }


        $sheetname = 'Sheet1'; 
        $sheet = $objPHPExcel->getSheetByName($sheetname);

        if($sheet){
            for ($row = 1; $row < 2; $row++){
                for($column = 0; $column < 11; $column++){
                    
                    $nombreHoja = str_replace(
                                            array('á','é','í','ó','ú'),
                                            array('a','e','i','o','u'),
                                            $sheet->getCellByColumnAndRow($column,$row)->getFormattedValue()
                                        );
                    $nombreHoja = strtolower($nombreHoja);                    
                    $nombreHojas[] = $nombreHoja;                                    
                }
            }
               
            if($nombreHojas[0] == 'nombre' && $nombreHojas[1] == 'apellido'){
                $aprobado = 1;
            }
        }
         
        //Esto es para c rear la fecha de hoy y darle formato
        //podés googlear new datetime, o "Coo crear nueva fecha en php" hay mil formasq, esta es una    
        //$fechaActualizacion = new DateTime('NOW');
        //$fechaActualizacion = $fechaActualizacion->format('Y-m-d');

        if($aprobado){

            $highestRow = $sheet->getHighestDataRow();

            $valido = true;
            $error = '';

            R::freeze(true); 
                            
            R::begin();  


            
            for ($row = 2; $row <= $highestRow; $row++){

                //Acá tomás lo del excel getCellByColumnAndRow(Loprimeroeslacolumna,elsegundodatoeslarow)
                //La row sale del for que estás haciendo, entonces vá a pasar por la 1, la 2, la 3 etc.
                //Vos pensá que un for son vueltas,
                //Dá las vueltas que vos le digas
                //$columna0 = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
                $columna1 = $sheet->getCellByColumnAndRow(0,$row)->getCalculatedValue();

                $columna2 = $sheet->getCellByColumnAndRow(1,$row)->getCalculatedValue();                

                $columna3 = $sheet->getCellByColumnAndRow(2,$row)->getCalculatedValue();    

                $columna4 = $sheet->getCellByColumnAndRow(3,$row)->getCalculatedValue();


                $fechaNacimiento = $sheet->getCellByColumnAndRow(4,$row)->getFormattedValue();
                /*$color = strtolower($color);

                $this->load->model('Perfil_model');
                $this->Perfil_model->color = $color;

                $resultadoColor = $this->Perfil_model->getColorPorNombre();
                $resultadoColor = $resultadoColor['id'];*/  


                $tipoDocumento = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue();
                /*$comida = strtolower($comida);

                //$this->load->model('Perfil_model');
                $this->Perfil_model->comida = $comida;

                $resultadoComida = $this->Perfil_model->getComidaPorNombre();
                $resultadoComida = $resultadoComida['id'];*/


                $cuil = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue();
                /*$musica = strtolower($musica);

                //$this->load->model('Perfil_model');
                $this->Perfil_model->musica = $musica;

                $resultadoMusica = $this->Perfil_model->getMusicaPorNombre();
                $resultadoMusica = $resultadoMusica['id'];*/


                /*$pelicula = strtolower($pelicula);

                //$this->load->model('Perfil_model');
                $this->Perfil_model->pelicula = $pelicula;

                $resultadoPelicula = $this->Perfil_model->getPeliculaPorNombre();
                $resultadoPelicula = $resultadoPelicula['id']; */  


                $nacionalidad = $sheet->getCellByColumnAndRow(7,$row)->getFormattedValue();

                /*$esSoltero = strtolower($esSoltero);
                if($esSoltero == 'si'){
                    $esSoltero = 'true';
                }else{
                    $esSoltero = 'false';
                }*/


                $estadoCivil = $sheet->getCellByColumnAndRow(8,$row)->getFormattedValue(); 

                /*$esDeportista = strtolower($esDeportista);
                if($esDeportista == 'si'){
                    $esDeportista = 'true';    
                }else{
                    $esDeportista = 'false'; 
                }*/


                $esDiscapacitado = $sheet->getCellByColumnAndRow(9,$row)->getFormattedValue(); 

                /*$esVegetariano = strtolower($esVegetariano);
                if($esVegetariano == 'si'){
                    $esVegetariano = 'true';    
                }else{
                    $esVegetariano = 'false'; 
                }*/

                $email = $sheet->getCellByColumnAndRow(10,$row)->getFormattedValue();

                $sexo = $sheet->getCellByColumnAndRow(11,$row)->getFormattedValue();

                $cargo = $sheet->getCellByColumnAndRow(12,$row)->getFormattedValue();

                $fechaIngreso = $sheet->getCellByColumnAndRow(13,$row)->getFormattedValue();

                $fechaEgreso = $sheet->getCellByColumnAndRow(14,$row)->getFormattedValue();

                $fechaAntiguedad = $sheet->getCellByColumnAndRow(15,$row)->getFormattedValue();

                $diasVacaciones = $sheet->getCellByColumnAndRow(16,$row)->getFormattedValue();

                $sueldoBasico = $sheet->getCellByColumnAndRow(17,$row)->getFormattedValue();

                $observaciones = $sheet->getCellByColumnAndRow(18,$row)->getFormattedValue();

                $fechaInicio = $sheet->getCellByColumnAndRow(19,$row)->getFormattedValue();

                $fechaSalida = $sheet->getCellByColumnAndRow(20,$row)->getFormattedValue();

                $montoMensual = $sheet->getCellByColumnAndRow(21,$row)->getFormattedValue();

                $dependencia = $sheet->getCellByColumnAndRow(22,$row)->getFormattedValue();

                $funciones = $sheet->getCellByColumnAndRow(23,$row)->getFormattedValue();

                /*$columna5 = $sheet->getCellByColumnAndRow(2,$row)->getCalculatedValue();    

                $columna6 = $sheet->getCellByColumnAndRow(2,$row)->getCalculatedValue();    

                $columna7 = $sheet->getCellByColumnAndRow(2,$row)->getCalculatedValue();    

                $columna8 = $sheet->getCellByColumnAndRow(2,$row)->getCalculatedValue();*/                                                    
                                    
                
                //En este orden queda más prolijo, levantás el dato,
                //Comprobás si es un número
                //Abris el dispense,
                //Le indicas el dato
                /*if(!(is_numeric($columna1))){
                    $error.="estado inválido en fila {$row} <br>";
                    $valido = false;
                }

                if(is_null($resultadoColor)){
                    $error.="La funcion consultó con mysql y no encontró ese color en fila {$row} <br>";
                    $valido = false;
                }

                if(is_null($resultadoComida)){
                    $error.="La funcion consultó con mysql y no encontró esa comida en fila {$row} <br>";
                    $valido = false;
                }

                if(is_null($resultadoMusica)){
                    $error.="La funcion consultó con mysql y no encontró esa musica en fila {$row} <br>";
                    $valido = false;
                }

                if(is_null($resultadoPelicula)){
                    $error.="La funcion consultó con mysql y no encontró esa pelicula en fila {$row} <br>";
                    $valido = false;
                }*/

                //Acá válido te dá false, si es false no se guarda la solicitud, solo el perfil y dá error
                //if(!is_bool($esSoltero)){
                //    $error.="es soltero inválido en fila {$row} <br>";
                //    $valido = false;
                //}

                //if(!is_bool($esDeportista)){
                //    $error.="es deportista inválido en fila {$row} <br>";
                //    $valido = false;
                //}

                //if(!is_bool($esVegetariano)){
                //    $error.="es vegetariano inválido en fila {$row} <br>";
                //    $valido = false;
                //}                                              

                /*if(!is_null($columna2)){
                    $error.="observacion inválida en fila {$row} <br>";
                    $valido = false;
                }*/

                //El dispense bien, porque la tabla que querés usar es solicitud
                $orden = R::dispense('legajo');

                //Y acá bajo el array orden, que ya indica que es para solicitud, le mandás el dato estado_id
                //O sea, indicas ->estado_id que es como se llama el campo en la tabla
                //Y le ponés igual a tanto, que es lo que querés guardar vos
                //Podrías poner así también $orden->estado_id = 2;
                //Ahí lo estarías forzando, "hardcodeando"
                //$orden->estado_id = $columna0; 
                //$orden->estado_id = $columna2;  
                $orden->nombre = $columna1;
                $orden->apellido = $columna2;                  
                $orden->fechaNacimiento = $fechaNacimiento;
                $orden->tipoDocumento = $tipoDocumento; 
                $orden->cuil = $cuil;  
                $orden->nacionalidad = $nacionalidad;  
                $orden->estadoCivil = $estadoCivil;  
                $orden->esDiscapacitado = $esDiscapacitado;  
                $orden->email = $email;  
                $orden->sexo = $sexo;  
                $orden->cargo = $cargo;  
                $orden->fechaIngreso = $fechaIngreso;  
                $orden->fechaEgreso = $fechaEgreso;  
                $orden->fechaAntiguedad = $fechaAntiguedad;  
                $orden->diasVacaciones = $diasVacaciones;  
                $orden->sueldoBasico = $sueldoBasico;  
                $orden->observaciones = $observaciones;                  

                //Valido arriba está en true, pasaría a ser false sólo si no fuera un número
                if ($valido){  
                    $this->id = R::store($orden);    
                }

                $experiencialaboral = R::dispense('experiencialaboral');
                $experiencialaboral->experiencia = $columna3;
                $experiencialaboral->empresa = $columna4;
                $experiencialaboral->fechaInicio = $fechaInicio;
                $experiencialaboral->fechaSalida = $fechaSalida;
                $experiencialaboral->montoMensual = $montoMensual;
                $experiencialaboral->dependencia = $dependencia; 
                $experiencialaboral->funciones = $funciones;             
                $experiencialaboral->legajo_id = $this->id;                  

                $this->id = R::store($experiencialaboral);   

            
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
        }
    } 
}