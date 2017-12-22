<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Importar extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->helper('manejoerrores');
    }
    
    public function index(){
        error_reporting(-1);
        ini_set('display_errors', 1);
        switch (ENVIRONMENT) {
            case 'javierdev':
            case 'desajavier':
            case 'test.gestionsgr':
            case 'jleisubuntu':
                R::addDatabase('remoto', 'mysql:host=alta.local;dbname=altaweb', 'root','Cuervo10');
                break;
            default :
                R::addDatabase('remoto', 'mysql:host=alta.allaria.com.ar;dbname=altaalla_datos', 'altaalla_user',';J.f+cBfUrUk');
        }
        
        R::selectDatabase('remoto');
        $ultimoRemoto = R::getCell('select max(id) from formulario');
        $prospectos = R::getAll('select * from prospecto order by id');
        
        R::selectDatabase('default');
        R::freeze(true);
        R::begin();
        
        foreach($prospectos as $prospectoRemoto ){
            $prospecto = R::load('prospecto', $prospectoRemoto['id']);
            if ($prospecto->id == 0){
                try {
                    R::exec("insert prospecto(id) values({$prospectoRemoto['id']})");
                    $prospecto = R::load('prospecto', $prospectoRemoto['id']);
                } catch (Exception $ex){
                $mensaje = 'Error insertando el prospecto <br>Mensaje:' . $ex->getMessage();
                enviarMensajeError($mensaje);
                die();
                }
            }
            $prospecto->nombre = $prospectoRemoto['nombre'];
            $prospecto->apellido = $prospectoRemoto['apellido'];
            $prospecto->email = $prospectoRemoto['email'];
            R::store($prospecto);
        }
        
        
        $ultimoLocal = R::getCell('select max(id) from formulario');
        
        for ($i=$ultimoLocal+1; $i<=$ultimoRemoto; $i++){
            
            $this->importar_uno($i);
            
        }
        
        //Actualizar emails verificados
        
        

        R::selectDatabase('remoto');
        $emails1 = R::getCol('select id from titular where email1Verificado = 1');
        $emails2 = R::getCol('select id from titular where email2Verificado = 1');
        
        $emailsTodos = R::getCol('select id from titular where email1Verificado = 1 or email2Verificado =1');
        
        R::selectDatabase('default');
        R::commit();
        
        //Busco todos los que se les actualizo el mail y no lo tenian actualizado
        $titulares = R::getCol('select distinct id from titular where email1Verificado <> 1 and email2Verificado <> 1 and id in ('. implode(',', $emailsTodos) . ')');
        if (count($titulares) > 0){
            $sql = 'select formulario_id, numeroDocumento, apellido, nombre from titular where id in (' . implode(',', $titulares) . ')';
            $datosTitulares = R::getAll($sql);



            $this->load->library('email');

            $html = "
                    <html>
                        <p>Las siguientes solicitudes acaban de verificar su e-mail:</p>
                        <br>
                        <table border='1'>
                            <tr>
                                <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Solicitud</font></th>
                                <th bgcolor='#5D7B9D'><font color='#FFFFFF'>DNI</font></th>
                                <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Apellido</font></th>
                                <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Nombre</font></th>
                            </tr>";

            foreach ($datosTitulares as $d) {
                $html .= "
                            <tr>
                                <td align='right'>{$d['formulario_id']}</td>
                                <td align='right'>{$d['numeroDocumento']}</td>    
                                <td>{$d['apellido']}</td>
                                <td>{$d['nombre']}</td>
                            </tr>
                        ";

            }


            $html .= "
                        </table>
                        <br>
                        <p>
                            Saludos
                        </p>
                    </html>
                    ";
            $this->email->from('no-responder@allaria.com.ar', 'Sistema de Suscripciones');
            $this->email->to(array('altasonline@allariaycia.com'));
            $this->email->bcc(array('jleis@allariaycia.com'));

            $this->email->subject('VERIFICA MAIL');
            $this->email->set_crlf( "\r\n" );
            $this->email->message($html);	

            $this->email->send();

            echo $this->email->print_debugger();
        }
        
        /*
         * Aca actualizo la fecha de presentacion de los que estan en null
         */
        $sql = 'update formulario set fechaPresentacion = fh_creacion where  fechaPresentacion is null;';
        try {
            R::exec($sql);
        } catch (Exception $ex) {
            $mensaje = 'Error actualizando las fechas de presentacion <br>Mensaje:' . $ex->getMessage();
            enviarMensajeError($mensaje);
            die();
        }

        if (count($emails1) > 0){
            $sql = 'update titular set email1Verificado = 1 where id in (' . implode(',',$emails1) . ')';
            try {
                R::exec($sql);
            } catch (Exception $e){
                $mensaje = 'Error actualizando el email1 enviado<br>mensaje: ' . $e->getMessage();
                enviarMensajeError($mensaje);
                die();
            }
        }
        if (count($emails2) > 0){
            $sql = 'update titular set email2Verificado = 1 where id in (' . implode(',',$emails2) . ')';
            try {
                R::exec($sql);
            } catch (Exception $e){
                $mensaje = 'Error actualizando el email2 enviado<br>mensaje: ' . $e->getMessage();
                enviarMensajeError($mensaje);
                die();
            }
        }
        
    }
    
    private function validateDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
    
    public function caso($formulario_id){
        error_reporting(-1);
        ini_set('display_errors', 1);
        switch (ENVIRONMENT) {
            case 'javierdev':
            case 'desajavier':
            case 'test.gestionsgr':
            case 'jleisubuntu':
                R::addDatabase('remoto', 'mysql:host=alta.local;dbname=altaweb', 'root','Cuervo10');
                break;
            default :
                R::addDatabase('remoto', 'mysql:host=alta.allaria.com.ar;dbname=altaalla_datos', 'altaalla_user',';J.f+cBfUrUk');
        }
        R::selectDatabase('default');
        R::freeze(true);
        R::begin();
        $this->importar_uno($formulario_id);
        R::selectDatabase('default');
        R::commit();
        
    }
    
    private function importar_uno($formulario_id){
        
        R::selectDatabase('remoto');
        $formularioRemoto = R::load('formulario', $formulario_id);
        $formulario = $formularioRemoto->export();

        R::selectDatabase('default');
        try {
            R::exec("insert formulario(id) values({$formulario_id})");
        } catch (Exception $e){
            $mensaje = 'Error insertando en la tabla formulario<br> mensaje: '. $e->getMessage();
            enviarMensajeError($mensaje);
            R::rollback();
            die();
        }

        $formularioLocal = R::load('formulario', $formulario_id);

        foreach (array_keys($formulario) as $key) {
            $formularioLocal->{$key} = $formulario[$key];
        }
        $formularioLocal->fechaPresentacion = $formularioRemoto['fh_creacion'];
        $formularioLocal->fechaEstado = $formularioRemoto['fh_creacion'];
        if ($formularioLocal->perfilCuenta == 'may250K'){
            $tramiteAlta = R::load('tramitealta', 1);
        } else {
            $tramiteAlta = R::load('tramitealta', 3);
        }
        $formularioLocal->tramitealta = $tramiteAlta;
        try {
            R::store($formularioLocal);
        } catch (Exception $e){
            $mensaje = 'Error Actualizando el formulario <br> mensaje: ' . $e->getMessage();
            enviarMensajeError($mensaje);
            R::rollback();
            die();
        }

        R::selectDatabase('remoto');
        $esPrimero = true;
        foreach ($formularioRemoto->ownTitular as $indice=>$titularRemoto){
            if ($esPrimero){
                $idTitular = $titularRemoto->id;
                $email = $titularRemoto->email1;
                $nombre = $titularRemoto->nombre . ' ' . $titularRemoto->apellido;
                $esPrimero = false;
            }
            $titular = $titularRemoto->export();

            R::selectDatabase('default');
            try {
                R::exec("insert titular(id) values({$titularRemoto['id']})");
            } catch (Exception $e){
                $mensaje = 'Error insertando en la tabla titular<br>mensaje: ' . $e->getMessage();
                enviarMensajeError($mensaje);
                R::rollback();
                die();
            }

            $titularLocal = R::load('titular', $titularRemoto['id']);
            foreach (array_keys($titular) as $key){
                switch ($key){
                    case 'formulario_id':
                        $titularLocal->formulario = $formularioLocal;
                        break;
                    case 'fechaNacimiento':
                        if ($this->validateDate($titular[$key])){
                            $titularLocal->{$key} = $titular[$key];
                        }
                        break;
                    default:
                        $titularLocal->{$key} = $titular[$key];
                }
            }

            try{
                R::store($titularLocal);
            } catch (Exception $e){
                $mensaje = 'Error actualizando el titular<br>mensaje: ' . $e->getMessage();
                enviarMensajeError($mensaje);
                R::rollback();
                die();
            }

            R::selectDatabase('remoto');
            foreach ($titularRemoto->ownResidencia as $residenciaRemoto){
                $residencia = $residenciaRemoto->export();

                R::selectDatabase('default');
                
                try {
                    R::exec("insert residencia(id) values({$residencia['id']})");
                } catch (Exception $e){
                    $mensaje = 'Error insertando en residencia<br>mensaje:' . $e->getMessage();
                    enviarMensajeError($mensaje);
                    R::rollback();
                    die();
                }

                $residenciaLocal = R::load('residencia', $residencia['id']);
                foreach (array_keys($residencia) as $key){
                    if ($key <> 'titular_id'){
                        $residenciaLocal->{$key} = $residencia[$key];
                    }
                }
                $residenciaLocal->titular = $titularLocal;
                
                try{
                    R::store($residenciaLocal);
                } catch (Exception $e){
                    $mensaje = 'Error actualizando la residencia<br>mensaje: ' . $e->getMessage();
                    enviarMensajeError($mensaje);
                    R::rollback();
                    die();
                }
            }

        }

        R::selectDatabase('remoto');
        foreach ($formularioRemoto->ownAdjunto as $adjuntoRemoto){
            $adjunto = $adjuntoRemoto->export();
            R::selectDatabase('default');
            
            try {
                R::exec("insert adjunto(id) values({$adjunto['id']})");
            } catch (Exception $e){
                $mensaje = 'Error insertando adjunto<br>mensaje:' . $e->getMessage();
                enviarMensajeError($mensaje);
                R::rollback();
                die();
            }

            $adjuntoLocal = R::load('adjunto', $adjunto['id']);
            foreach (array_keys($adjunto) as $key){
                if ($key <> 'formulario_id'){
                    $adjuntoLocal->{$key} = $adjunto[$key];
                }
            }
            $adjuntoLocal->formulario = $formularioLocal;
            
            try {
                R::store($adjuntoLocal);
            } catch (Exception $e){
                $mensaje = 'Error actualizando adjunto<br>Mensaje:' . $e->getMessage();
                enviarMensajeError($mensaje);
                R::rollback();
                die();
            }
        }



        echo "<p>Importado {$formularioLocal->id}</p>";

        if ($formularioLocal->retail_id > 0 ){
            R::selectDatabase('default');
            $this->load->model('Formulario_model');
            $this->Formulario_model->id = $formularioLocal->id;
            $ficha = $this->Formulario_model->getFicha();

            if ($this->isSecure()){
                $url = 'https://';
            } else {
                $url = 'http://';
            }
            
            $url .=  $_SERVER['HTTP_HOST'] . '/generador/fichaRetail.php';
            $fields = array(
                          'datos'=>urlencode(json_encode($ficha))
                    );

            $fields_string = '';
            //url-ify the data for the POST
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string,'&');

            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

            //execute post
            $result = curl_exec($ch);

            //close connection
            curl_close($ch);

            $archivo = FCPATH . 'generador' . substr($result, 1);

            $textoEmail = "Estimad@ {$nombre},<br>
                            Adjunto al presente encontrarás los formularios de apertura de cuenta.<br>
                            <p>Para avanzar, necesitamos que los imprimas, firmes donde se indica, y los envíes por correo postal a la siguiente dirección:</p>
                            <p><b>Allaria Ledesma & Cia – Sector Retail</b><br>
                                25 de Mayo 359, Piso 12<br>
                                C1002ABG – CABA</p>
                            <p>Para adelantar el proceso, podes enviarlos escaneados por e-mail a clientes@fondosallaria.com.ar</p>
                            <p>Por cualquier consulta sobre este proceso, por favor comunícate con nuestros asesores al 11.5555.6099 o directo con tu Oficial de Cuentas.</p> ";

            $this->load->model('Mailing_model');
            
            $respuesta = $this->Mailing_model->enviarMail($textoEmail, 'Allaria Ledesma', 'altasonline@allaria.com.ar', 'Solicitud de alta de cuenta', array($email), 'AltaOnline', array(), array($archivo));

            $titular = R::load('titular', $idTitular);
            $titular->respuesta1 = $respuesta['response'];
            
            try{
                R::store($titular);
            } catch (Exception $e){
                $mensaje = 'Error guardando el resultado del envío del mail<br>Mensaje: ' . $e->getMessage();
                enviarMensajeError($mensaje);
                R::rollback();
                die();
            }

            unlink($archivo);

            $this->load->library('email');

            $html = "
                    <html>
                        <p>La siguiente persona acaba de completar el formulario:</p>
                        <br>
                        <p>Nombre: {$nombre}</p>
                        <p>E-mail: {$email}</p>
                        <p>Solicitud: {$formularioLocal->id}</p>
                        <br>
                        <p>
                            Saludos
                        </p>
                    </html>
                    ";
            $this->email->from('no-responder@allaria.com.ar', 'Sistema de Suscripciones');
            $this->email->to(array('clientes@fondosallaria.com.ar'));

            $this->email->subject('Formulario Completado');
            $this->email->set_crlf( "\r\n" );
            $this->email->message($html);	
            
            
            $this->email->send();

            echo $this->email->print_debugger();

        }
        
    }
    
    function isSecure() {
      return
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || $_SERVER['SERVER_PORT'] == 443;
    }

}
