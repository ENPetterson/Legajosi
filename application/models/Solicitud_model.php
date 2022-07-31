<?php

require_once APPPATH."/third_party/PHPExcel.php";

class solicitud_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $fechaPresentacion;
    public $fechaEstado;
    public $estado_id;
    public $observaciones;      
    public $fechaActualizacion;
    
    public function saveSolicitud(){
        $solicitud = R::load('solicitud', $this->id);
        $solicitud->fechaPresentacion = $this->fechaPresentacion;
        $solicitud->fechaEstado = $this->fechaEstado;
        $solicitud->estado_id = $this->estado_id;
        $solicitud->observaciones = $this->observaciones;
        $solicitud->fechaActualizacion = $this->fechaActualizacion;             
        //$this->id = R::store($solicitud);
        
    
        foreach ($this->perfiles as $perfilArray) {

            $perfil = (object) $perfilArray;
            $perfilBean = R::load('perfil', $perfil->idPerfil);
            $perfilBean->solicitud = $solicitud;
            $perfilBean->nombre = $perfil->nombre;
            $perfilBean->apellido = $perfil->apellido;
            $perfilBean->esSoltero = $perfil->esSoltero;
            $perfilBean->color = $perfil->color;
            $perfilBean->comida = $perfil->comida;
            $perfilBean->musica = $perfil->musica;
            $perfilBean->pelicula = $perfil->pelicula;
            $perfilBean->esDeportista = $perfil->esDeportista;
            $perfilBean->esVegetariano = $perfil->esVegetariano;
            $perfilBean->relacionDependencia = $perfil->relacionDependencia;
            $perfilBean->empleador = $perfil->empleador;  
            $perfilBean->asisteUniversidad = $perfil->asisteUniversidad;  
            $perfilBean->universitario = $perfil->universitario;
            $perfilBean->cantidadAnios = $perfil->cantidadAnios;                                                     

            R::store($perfilBean);
            
        }

        return $solicitud->export();
    }
    
    public function getSolicitud(){
        $solicitud = R::load('solicitud', $this->id);
        return $solicitud->export();
    }
    
    public function getSolicitudesUsuario(){
        $solicitudes = R::getCol('select solicitud_id from solicitud_usuario where usuario_id = ?', array($this->usuario_id));
        return $solicitudes;
    }
    
    public function getSolicitudes(){
        $solicitudes = R::getAll('select * from solicitud order by fechaPresentacion');
        return $solicitudes;
    }

     public function getSolicitudesPerfil(){
//        $solicitud = $this->solicitud;    
        $solicituds = R::getAll('SELECT 
    `p`.`id` AS `id`,
    `p`.`fechaPresentacion` AS `fechaPresentacion`,
    `p`.`fechaEstado` AS `fechaEstado`,
    `p`.`fechaActualizacion` AS `fechaActualizacion`,
    `p`.`observaciones` AS `observaciones`,
    `p`.`estado_id` AS `estado_id`,
    `e`.`descripcion` AS `estado`,

    `s`.`id` AS `idPerfil`,
    `s`.`solicitud_id` AS `solicitud_id`,
    `s`.`nombre` AS `nombre`,
    `s`.`apellido` AS `apellido`,
    `s`.`esSoltero` AS `esSoltero`,
    `s`.`color` AS `color`,
    `s`.`comida` AS `comida`,
    `s`.`musica` AS `musica`,
    `s`.`pelicula` AS `pelicula`,
    `s`.`esDeportista` AS `esDeportista`,
    `s`.`esVegetariano` AS `esVegetariano`

    FROM 
    `solicitud` `p` 

    LEFT JOIN `perfil` `s` on `s`.`solicitud_id` = `p`.`id`
    LEFT JOIN `estado` `e` on `p`.`estado_id` = `e`.`id`

    ORDER BY s.solicitud_id DESC


                ');
        
        
        
        return $solicituds;
        
    }

    public function getSolicitudPerfilId(){
        $solicitudBean = R::load('solicitud', $this->id);
        $solicitud = $solicitudBean->export();
        $perfiles = array();
        foreach ($solicitudBean->ownPerfil as $perfilBean){
            $perfil = $perfilBean->export();
            array_push($perfiles, $perfil);
        }
        $solicitud['perfiles'] = $perfiles;
        
        return $solicitud;
    }
    
    
    public function delSolicitud(){
        $solicitud = R::load('solicitud', $this->id);
        R::trash($solicitud);
    }
    
    public function assocUsuario(){
        $solicitud = R::load('solicitud', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($solicitud, $usuario);
    }
    
    public function clearRelMenu(){
        $solicitud = R::load('solicitud', $this->id);
        R::clearRelations($solicitud, 'menu');
    }
    
    public function assocMenu(){
        $solicitud = R::load('solicitud', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($solicitud, $menu);
    }
    
    public function clearRelControlador(){
        $solicitud = R::load('solicitud', $this->id);
        R::clearRelations($solicitud, 'controlador');
    }
    
    public function assocControlador(){
        $solicitud = R::load('solicitud', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($solicitud, $controlador);
    }
    
    public function grabarExcel(){
                  
        $usuarioParam = $this->session->userdata('usuario');

        $orden = R::load('canje', $this->id);

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
               
            if($nombreHojas[0] == 'estado_id' && $nombreHojas[1] == 'observaciones'){
                $aprobado = 1;
            }
        }
         
        //Esto es para c rear la fecha de hoy y darle formato
        //podés googlear new datetime, o "Coo crear nueva fecha en php" hay mil formasq, esta es una    
        $fechaActualizacion = new DateTime('NOW');
        $fechaActualizacion = $fechaActualizacion->format('Y-m-d');

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


                $color = $sheet->getCellByColumnAndRow(4,$row)->getFormattedValue();
                $color = strtolower($color);

                $this->load->model('Perfil_model');
                $this->Perfil_model->color = $color;

                $resultadoColor = $this->Perfil_model->getColorPorNombre();
                $resultadoColor = $resultadoColor['id'];    


                $comida = $sheet->getCellByColumnAndRow(5,$row)->getFormattedValue();
                $comida = strtolower($comida);

                //$this->load->model('Perfil_model');
                $this->Perfil_model->comida = $comida;

                $resultadoComida = $this->Perfil_model->getComidaPorNombre();
                $resultadoComida = $resultadoComida['id'];


                $musica = $sheet->getCellByColumnAndRow(6,$row)->getFormattedValue();
                $musica = strtolower($musica);

                //$this->load->model('Perfil_model');
                $this->Perfil_model->musica = $musica;

                $resultadoMusica = $this->Perfil_model->getMusicaPorNombre();
                $resultadoMusica = $resultadoMusica['id'];


                $pelicula = $sheet->getCellByColumnAndRow(7,$row)->getFormattedValue();
                $pelicula = strtolower($pelicula);

                //$this->load->model('Perfil_model');
                $this->Perfil_model->pelicula = $pelicula;

                $resultadoPelicula = $this->Perfil_model->getPeliculaPorNombre();
                $resultadoPelicula = $resultadoPelicula['id'];   


                $esSoltero = $sheet->getCellByColumnAndRow(8,$row)->getFormattedValue();

                $esSoltero = strtolower($esSoltero);
                if($esSoltero == 'si'){
                    $esSoltero = 'true';
                }else{
                    $esSoltero = 'false';
                }


                $esDeportista = $sheet->getCellByColumnAndRow(9,$row)->getFormattedValue(); 

                $esDeportista = strtolower($esDeportista);
                if($esDeportista == 'si'){
                    $esDeportista = 'true';    
                }else{
                    $esDeportista = 'false'; 
                }


                $esVegetariano = $sheet->getCellByColumnAndRow(10,$row)->getFormattedValue(); 

                $esVegetariano = strtolower($esVegetariano);
                if($esVegetariano == 'si'){
                    $esVegetariano = 'true';    
                }else{
                    $esVegetariano = 'false'; 
                }

                $fechaPresentacion = $sheet->getCellByColumnAndRow(11,$row)->getFormattedValue();

                $fechaEstado = $sheet->getCellByColumnAndRow(12,$row)->getFormattedValue();

                /*$columna5 = $sheet->getCellByColumnAndRow(2,$row)->getCalculatedValue();    

                $columna6 = $sheet->getCellByColumnAndRow(2,$row)->getCalculatedValue();    

                $columna7 = $sheet->getCellByColumnAndRow(2,$row)->getCalculatedValue();    

                $columna8 = $sheet->getCellByColumnAndRow(2,$row)->getCalculatedValue(); */                                                    
                                    
                
                //En este orden queda más prolijo, levantás el dato,
                //Comprobás si es un número
                //Abris el dispense,
                //Le indicas el dato
                if(!(is_numeric($columna1))){
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
                }

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
                $orden = R::dispense('solicitud');

                //Y acá bajo el array orden, que ya indica que es para solicitud, le mandás el dato estado_id
                //O sea, indicas ->estado_id que es como se llama el campo en la tabla
                //Y le ponés igual a tanto, que es lo que querés guardar vos
                //Podrías poner así también $orden->estado_id = 2;
                //Ahí lo estarías forzando, "hardcodeando"
                //$orden->estado_id = $columna0; 
                //$orden->estado_id = $columna2;  
                $orden->estado_id = $columna1;
                $orden->observaciones = $columna2;                  
                $orden->fechaActualizacion = $fechaActualizacion;
                $orden->fechaPresentacion = $fechaPresentacion; 
                $orden->fechaEstado = $fechaEstado;  
                //$orden->fechaPresentacion = $fechaActualizacion;                  

                //Valido arriba está en true, pasaría a ser false sólo si no fuera un número
                if ($valido){  
                    $this->id = R::store($orden);    
                }

                $perfil = R::dispense('perfil');
                $perfil->nombre = $columna3;
                $perfil->apellido = $columna4;
                $perfil->color = $resultadoColor;
                $perfil->comida = $resultadoComida;
                $perfil->musica = $resultadoMusica;
                $perfil->pelicula = $resultadoPelicula; 
                $perfil->esSoltero = $esSoltero;
                $perfil->esDeportista = $esDeportista;  
                $perfil->esVegetariano = $esVegetariano;              
                $perfil->solicitud_id = $this->id;                  

                $this->id = R::store($perfil);    


            
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