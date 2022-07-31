<?php

require_once APPPATH."/third_party/PHPExcel.php";

class Experiencialaboral_model extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    public $id;
    public $experiencia;
    public $empresa;
    public $legajo_id;
    public $fechaInicio;
    public $fechaSalida;
    public $montoMensual;
    public $dependencia;  
    public $funciones;             
    public $usuario_id;
    public $menu_id;
    public $controlador_id;


    public function saveExperiencialaboral(){
        $experiencialaboral = R::load('experiencialaboral', $this->id);
        $experiencialaboral->experiencia = $this->experiencia;
        $experiencialaboral->empresa = $this->empresa;
        $experiencialaboral->legajo_id = 1;
        $experiencialaboral->fechaInicio = $this->fechaInicio;
        $experiencialaboral->fechaSalida = $this->fechaSalida;
        $experiencialaboral->montoMensual = $this->montoMensual;
        $experiencialaboral->dependencia = $this->dependencia;
        $experiencialaboral->funciones = $this->funciones;
                                                           
        $this->id = R::store($experiencialaboral);
        return $this->id;
    }

    public function getExperiencialaboral(){
        $experiencialaboral = R::load('experiencialaboral', $this->id);
        return $experiencialaboral->export();
    }
    
    public function getExperienciaslaboralesUsuario(){
        $experienciaslaborales = R::getCol('select experiencialaboral_id from experiencialaboral_usuario where usuario_id = ?', array($this->usuario_id));
        return $experienciaslaborales;
    }

    public function getExperienciaslaborales(){
        $experienciaslaborales = R::getAll('select * from experiencialaboral order by empresa');
        return $experienciaslaborales;
    }

    public function delExperiencialaboral(){
        $experiencialaboral = R::load('experiencialaboral', $this->id);
        R::trash($experiencialaboral);
    }
    
    public function assocUsuario(){
        $experiencialaboral = R::load('experiencialaboral', $this->id);
        $usuario = R::load('usuario', $this->usuario_id);
        R::associate($experiencialaboral, $usuario);
    }
    
    public function clearRelMenu(){
        $experiencialaboral = R::load('experiencialaboral', $this->id);
        R::clearRelations($experiencialaboral, 'menu');
    }
    
    public function assocMenu(){
        $experiencialaboral = R::load('experiencialaboral', $this->id);
        $menu = R::load('menu', $this->menu_id);
        R::associate($experiencialaboral, $menu);
    }
    
    public function clearRelControlador(){
        $experiencialaboral = R::load('experiencialaboral', $this->id);
        R::clearRelations($experiencialaboral, 'controlador');
    }
    
    public function assocControlador(){
        $experiencialaboral = R::load('experiencialaboral', $this->id);
        $controlador = R::load('controlador', $this->controlador_id);
        R::associate($experiencialaboral, $controlador);
    }


    //Las tablas van en minúsculas, todo en minusculas
    //a lo sumo podés usar algo que se llama camelcase tenes que googlearlo
    //Es por ejemplo "colorDescripcion", todo lo que sea nombres de variables o campos en mysql se usa todo minusculas o camelcase
    //La funcion esta la llamo abajo, te dice que selecciona todo de color, donde la descripcion sea, por ejemplo "rojo"
    //Entonces ahi tomas el id, que es lo que necesitas guardar en realidad, porque no  podes guardar letras en ese campo que es un id y apunta a la tabla color
    //En return color te devuelve el resultado

    /*public function getColorPorNombre(){
        $sql = "select id, descripcion from color WHERE descripcion = ?";
        $color = R::getRow($sql, array($this->color)); 

        return $color;
    }

    public function getComidaPorNombre(){
        $sql = "select id, descripcion from comida WHERE descripcion = ?";
        $comida = R::getRow($sql, array($this->comida)); 

        return $comida;
    }
    
    public function getMusicaPorNombre(){
        $sql = "select id, descripcion from musica WHERE descripcion = ?";
        $musica = R::getRow($sql, array($this->musica)); 

        return $musica;
    }

    public function getPeliculaPorNombre(){
        $sql = "select id, descripcion from pelicula WHERE descripcion = ?";
        $pelicula = R::getRow($sql, array($this->pelicula)); 

        return $pelicula;
    }*/

    public function grabarExcel(){
                  
        $usuarioParam = $this->session->userdata('usuario');

        $orden = R::load('canje', $this->id);
        //$cierre = R::load('cierrecanje', $this->cierre);
        //$usuario = R::load('usuario', $usuarioParam['id']);
        //$estadoorden = R::load('estadoorden', 1);        
        //$this->moneda = '$';

        //$plazos = $this->Canje_model->getPlazos();
               
        $this->load->helper('file');
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/tmp/';
       
        try {
            $inputFileName = $uploadDir . $this->archivo;

            //print_r("Acá Llega");

            //echo "<pre>";

            //print_r( $inputFileName );

            //echo "<pre>";
            //Cuando dejás un print en el controller en lugar de dovolverse resultado:ok se devualve lo que imprimiste + resultado:ok, no lo reconoce y entonces se queda dando vueltas porque piensa que no está ok

            // Bueno me tengo que ir a trabajar, hasta acá llega.
            // Lo que tendrías que hacer ahora es seguir imprimiendo hacia abajo
            // Para ver qué hace cada variable, y entender las cosas,
            // Cualquier cosa preguntame            

            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);   
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);


        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }


        //$sheetname = 'Hoja1'; //Se llama Sheet1 la mierda esta de primera hoja
        $sheetname = 'Sheet1'; //ctrl shift D duplica
        
        $sheet = $objPHPExcel->getSheetByName($sheetname);

        //Vas de  poco para ver si funcionan o no las cosas, imprimís acá.
        //print_r( $sheet ); // ahí imprime bien todo, es un quilombo, si querés lo mirás después
        //die;

        //Esta parte hace los for para ver qué dice la primera celda y la segunda celda,
        //en este caso dicen nombre y apellido
        if($sheet){
            for ($row = 1; $row < 2; $row++){
                for($column = 0; $column < 11; $column++){
                    
                    //Esta parte del replace reemplaza los acentos googleá la función str_replace
                    $nombreHoja = str_replace(
                                            array('á','é','í','ó','ú'),
                                            array('a','e','i','o','u'),
                                            $sheet->getCellByColumnAndRow($column,$row)->getFormattedValue()
                                        );
                    //Esta parte lleva todo a minúsculas googleá la función strtolower de PHP
                    $nombreHoja = strtolower($nombreHoja);                    
                    $nombreHojas[] = $nombreHoja;                                    
                }
            }
               

            //Acá te dice que si la primera celda dice nombre y la segunda dice apellido esto está ok, está aprobado y lo pone en 1
            //Viste que te trae diez resultados o sea por el for que dice desde 0 hasta menor a 11
            //print_r($nombreHojas); die;
            //Si querés lo seguís viendo después
            if($nombreHojas[0] == 'experiencia' && $nombreHojas[1] == 'empresa'){
                $aprobado = 1;
            }
        }

        //print_r($aprobado); // Te dá aprobado 1 porque está ok
        //die;
                
        if($aprobado){
            //Este obtiene la fila más alta del archivo, o sea, la que tiene datos.
            //Lo hace automático.
            $highestRow = $sheet->getHighestDataRow();
            
            //print_r($highestRow); // Te dá 2, ahí le metí una fila mas y dice 3
            // Coconito es el nombre que Charanguito le quería poner a su bebé cuando estuvo "Embarazado"
            //Primero pensamos que era un embarazo psicologico
            //Después resultó todo una estafa
            //Era para obtener mayores ingresos económicos, pedir favores, mas comida, etc
            //Cuando se tenía que hacer la ecografia misteriosamente lo perdió
            //Albertito se llamaba el Patitas? Jjaaja pobre charan

            //Seguí fijandote abajo que onda, ya e tengo que desconectar

            //die;

            $valido = true;
            $error = '';

            R::freeze(true); //Esto de freeze es parque se quede congelado y no uarde los datos hasta que le digas.
                            // Podés googlearlo como php red beam o red bean R::freeze
            R::begin();     // Lo ismo, googlealo
            
            for ($row = 2; $row <= $highestRow; $row++){
                //Esta for se vá desde la fila 2, hasta la más alta  y te vá trayendo todos los datos
                                

                //En este getCellByColumnAndRow el row viene del for, arranca en la segunda fila porque la primera es el nombre
                //En la primera vuelta en ese $row hay un dos, entonces agarrás el dato que está en la columna 0 y fila 2.
                //Pensé que a esta parte ibas a entenderla solo.
                //Hay varias formas de agarrar los datos segun si te llegan como vos queres porque se complica por los formatos
                //Por las fechasm los números, etc
                //getValue
                //getFormattedValue
                //getCalculatedValue
                //getOldCalculatedValue
                //Lo podés googlear, igual casi siempre te ván a venir todos los datos. 
                //Pero por si te pasa, creo que con cualquiera te viene bien casi todo
                $columna0 = $sheet->getCellByColumnAndRow(0,$row)->getFormattedValue();
                $columna1 = $sheet->getCellByColumnAndRow(1,$row)->getFormattedValue();                                                        
                //Esto de str_replace no lo necesitas, ahí esta reeemplazando los , o . por nada
                //$numeroComitente = str_replace(',', '', $numeroComitente);
                //$numeroComitente = str_replace('.', '', $numeroComitente);

                //print_r($numeroComitente); Trae el valor "Coconito"
                //die;

                //Acá R::dispense es para indicar que vas a empezar a preparar un perfil para guardarlo,
                //Vas a acumular los datos para mandarlos a la tabla.    
                
                $orden = R::dispense('experiencialaboral'); //Entre comillas vá tu tabla
                $orden->experiencia = $columna0; //Ahí en nombre es como se llama tu campo en la tabla y qué le vás a meter
                $orden->empresa = $columna1;           

                // Color
                $legajo_id = $sheet->getCellByColumnAndRow(2,$row)->getCalculatedValue();     
                //$color = (int)$color; //Cuando le ponés un int lo forzás a que sea un número

                //Bueno ahora lo que quiero es que el chabón pueda elegir el color
                //Y no tenga que poner números
                //Pone directo el color
                //$color = strtolower($color);
                //Ahi ya tenemos el color y tenemos que usar una funcion para buscarlo en la base
                //Pongo this perfil model color = tal cosa, porque le cargo un parametro
                //o sea, ahi le mando el rojo y lo mando para la funcion
                //Si ponés un print_r($this); vas a ver que te imprime monton de cosas que estan todasahi adentro de $this
                //$this->ExperienciaLaboral_model->color = $color;
                //Ahi pongo que lo que me devuelve esa funcion me lo guarde en $resultadoColor
                //$resultadoColor = $this->ExperienciaLaboral_model->getColorPorNombre();

                //print_r($resultadoColor); die;
                //Me devuelve esto:
                //Array ( [id] => 2 [descripcion] => rojo )
                //Hay muchas formasarriba en la funcion cuando vamos a buscar los datos con mysql de traer los datos
                //R::getAll R::getCol R::getRow son todas cosas de Redbeam
                //Eso tenés que googlearlo
                //También podrías poner SELECT tal from tal limit 1 y que te devuelva un solo dato
                //Pero bueno, lo llamamos así y te trajo esto:
                //Array ( [id] => 2 [descripcion] => rojo )
                //Vos de ahi querés el id nada más
                //$resultadoColor = $resultadoColor['id'];
                //Se entiende?
                //Ahi ya cuando tenés el dato, más abajo querés que si o si te lo haya encontrado en la parte de la validación


                $fechaInicio = $sheet->getCellByColumnAndRow(3,$row)->getCalculatedValue();   
                //$comida = (int)$comida;

                /*$comida = strtolower($comida);
                $this->Perfil_model->comida = $comida;
                $resultadoComida = $this->Perfil_model->getComidaPorNombre(); 
                $resultadoComida = $resultadoComida['id'];*/               

                $fechaSalida = $sheet->getCellByColumnAndRow(4,$row)->getCalculatedValue();   
                //$musica = (int)$musica;  

                $montoMensual = $sheet->getCellByColumnAndRow(5,$row)->getCalculatedValue();   
                //$pelicula = (int)$pelicula;

                $dependencia = $sheet->getCellByColumnAndRow(6,$row)->getCalculatedValue();   
                //$esSoltero = (int)$esSoltero;

                //Con estas validaciones ya pasaría, porque vos ya te fijaste de estar trayendo números donde son numeros y de validar que sean numeros, pero yo ahora lo que quiero es que la persona pueda escribir si o no en la grilla
                //Entonces lo que hago es tomar el dato
                //Lo paso a minúsculas
                //Para no hacer esta boludez if(tal == a 'si' or tal == 'Si')
                //Lo pasás a minúsculas y comparás una sola vez
                /*$esSoltero = strtolower($esSoltero);
                if($esSoltero == 'si'){
                    $esSoltero = true;    
                }else if($esSoltero == 'no'){
                    $esSoltero = false; 
                }*/



                $funciones = $sheet->getCellByColumnAndRow(7,$row)->getCalculatedValue();   
                //$esDeportista = (int)$esDeportista;   

                //$esVegetariano = $sheet->getCellByColumnAndRow(8,$row)->getCalculatedValue();   
                //$esVegetariano = (int)$esVegetariano;   


                //Cuando hacés var_dump te imprime qué tipo de dato es.
                // int(numero entero), float, número con decimales, string (una cadena de caracteres), etc, deberias googlear los tipos de datos de php
                // Y los tipos de datos en Mysql, que se llaman distinto, o igual en algunos casos, un carchar es una cadena de caracteres, o sea un string, googlealo tabien, siempre para poder guardar en la base tiene que coincidir el tipo de dato.
                //Vos por ejemplo si tenes un id, tenes que poenr que sea un int, porque a los números se los puede ordenr de menor a mayor, o darle otro trabajo, segun se necesite, autoincrementar, ordenar, menor a mayor, etc, lo mismo las fechas tienen que ser un date en mysql para poder traerlas, y fijarse tambien de ordenar de menor a mayor, o un rago de fecha o que se yo

                //var_dump($color); die;                                
                                                

                //Igual acá abajo vás a chequear que sea un número, 
                //si querés después probá sacarle el int, o sea, comentas la linea de arriba 
                //y en el excel metes letras, para que te diga que no es un numero
                //Tenés varias funciones, fijate que yo te puse lo de getvalue, getcalculatedvalue, getformatedvalue, ahi lo agarré al dato como calculated y me lo trajo numerico
                //con estas funciones de php, is int, is numeric, is null, vos chequeás cosas, ok? hacés las validaciones
                //Ves que ahí te dejo pasar el color, porque te lo tomo como float
                //y le comprobe que sea numerico
                //is bool, después googlea lo que es un boolean, te dá dos opciones que el fato sea verdadero o que sea falso
                //O sea true o false o 1 o 0 o "true" o "false"
                //Después buscá las diferencias entre true y "true"


                
                //Entonces acá nos fijamos:
                //print_r($resultadoColor); 

                /*if(is_null($resultadoColor)){
                    $error.="La funcion consultó con mysql y no encontró ese color en fila {$row} <br>";
                    $valido = false;
                }

                if(is_null($resultadoComida)){
                    $error.="La funcion consultó con mysql y no encontró esa comida en fila {$row} <br>";
                    $valido = false;
                }

                if(!is_numeric($musica)){
                    $error.="musica inválida en fila {$row} <br>";
                    $valido = false;
                }

                if(!is_numeric($pelicula)){
                    $error.="pelicula inválida en fila {$row} <br>";
                    $valido = false;
                }

                if(!is_bool($esSoltero)){
                    $error.="es soltero inválido en fila {$row} <br>";
                    $valido = false;
                }

                if(!is_bool($esDeportista)){
                    $error.="es deportista inválido en fila {$row} <br>";
                    $valido = false;
                }

                if(!is_bool($esVegetariano)){
                    $error.="es vegetariano inválido en fila {$row} <br>";
                    $valido = false;
                }*/




                //print_r($cantidad); //"The application environment is not set correctly." 
                //die;

                $orden->legajo_id = $legajo_id;
                $orden->fechaInicio = $fechaInicio;
                $orden->fechaSalida = $fechaSalida;
                $orden->montoMensual = $montoMensual;
                $orden->dependencia = $dependencia;
                $orden->funciones = $funciones;                  


                //Completá todos los campos. Me tengo que ir.


//                    echo "<pre>";
//                    print_r($orden);
//                    echo "<pre>";
//                    die;
                
                if ($valido){ // Si es válido significa que si es true o es 1, ya lo pusimos en true, 
                                // se pondría en false si el color no fuera un número entocnes le dice que le haga store(guarde)
                    $this->id = R::store($orden);    
                }

            
            }           
                        
            if ($valido){//Y acá lo mismo, que es válido y que haga commit, o sea que confirma
                R::commit();
                $resultado = array('resultado'=>'OK');
            } else {// Y si está al rollback, o sea, que vuelva todo atras, que no guarde nada, googleá commit y rollback
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