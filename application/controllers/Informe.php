<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Informe extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $this->load->model('Formulario_model');
        $this->load->model('Esco_model');
        
        $comitentesPresenciales = $this->Formulario_model->getComitentesPresenciales();
        $this->Esco_model->in = implode(',', $comitentesPresenciales);
        $comitentesPresencialesMalCargados = $this->Esco_model->getComitentesPresencialesMalCargados();
        
        $reporteNoCoincide = Array();
        
        if (count($comitentesPresencialesMalCargados) > 0){
            $this->Formulario_model->comitentes = $comitentesPresencialesMalCargados;
            $reporteNoCoincide = $this->Formulario_model->getFormulariosByComitente();
            foreach ($reporteNoCoincide as $key=>$comitente){
                $reporteNoCoincide[$key]['esco'] = 'No Presencial';
            }
        }
        
        
        $comitentesNoPresenciales = $this->Formulario_model->getComitentesNoPresenciales();
        $this->Esco_model->in = implode(',', $comitentesNoPresenciales);
        $comitentesNoPresencialesMalCargados = $this->Esco_model->getComitentesNoPresencialesMalCargados();
        
        $comitentes = Array();
        
        if (count($comitentesNoPresencialesMalCargados) > 0){
            $this->Formulario_model->comitentes = $comitentesNoPresencialesMalCargados;
            $comitentes = $this->Formulario_model->getFormulariosByComitente();
            foreach ($comitentes as $key=>$comitente){
                $comitentes[$key]['esco'] = 'Presencial';
            }
            $reporteNoCoincide = array_merge($reporteNoCoincide, $comitentes);
        }
        
        $formulariosSinComitente = $this->Formulario_model->getFormulariosSinComitente();
        
        $this->load->library('email');

        $html = "
                <html>
                    <meta http-equiv='Content-Type'  content='text/html charset=UTF-8' />
                    <p>Las siguientes solicitudes no Coinciden el Presencial / No presencial entre los dos sistemas:</p>
                    <br>
                    <table border='1'>
                        <tr>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Solicitud </font></th>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Comitente </font></th>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Presentación </font></th>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>DNI </font></th>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Apellido </font></th>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Nombre </font></th>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Responsable </font></th>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Tramite Alta </font></th>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>ESCO </font></th>
                        </tr>";
        
        foreach ($reporteNoCoincide as $r){
            $html .= "
                            <tr>
                                <td align='right'>{$r['id']}</td>
                                <td align='right'>{$r['numComitente']}</td>
                                <td>{$r['fechaPresentacion']}</td>
                                <td align='right'>{$r['numeroDocumento']}</td>    
                                <td>{$r['apellido']}</td>
                                <td>{$r['nombre']}</td>
                                <td>{$r['responsable']}</td>
                                <td>{$r['tramiteAlta']}</td>
                                <td>{$r['esco']}</td>
                            </tr>
                        ";
        }
        
        $html .= "
                        </table>
                        <br>
                    ";
        
        $html .= "
                    <p>Las siguientes solicitudes Finalizadas no tienen cargado el número de comitente</p>
                    <br>
                    <table border='1'>
                        <tr>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Solicitud</font></th>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Presentación</font></th>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>DNI</font></th>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Apellido</font></th>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Nombre</font></th>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Responsable</font></th>
                            <th bgcolor='#5D7B9D'><font color='#FFFFFF'>Tramite Alta</font></th>
                        </tr>";
        
        foreach ($formulariosSinComitente as $r){
            $html .= "
                            <tr>
                                <td align='right'>{$r['id']}</td>
                                <td>{$r['fechaPresentacion']}</td>
                                <td align='right'>{$r['numeroDocumento']}</td>    
                                <td>{$r['apellido']}</td>
                                <td>{$r['nombre']}</td>
                                <td>{$r['responsable']}</td>
                                <td>{$r['tramiteAlta']}</td>
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
        $this->email->set_crlf( "\r\n" );
        
        $this->email->from('no-responder@allaria.com.ar', 'Sistema de Suscripciones');
        $this->email->to(array('altasonline@allariaycia.com','ltopfer@allariaycia.com','lalba@allariaycia.com', 'daniel.montiel@allariaycia.com')); 
        $this->email->bcc(array('jleis@allariaycia.com'));

        $this->email->subject('REPORTE WEB');
        $this->email->message($html);	
        

        $this->email->send();

        echo $this->email->print_debugger();

        
    }
}