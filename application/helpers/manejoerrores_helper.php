<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('enviarMensajeError')){
    function enviarMensajeError($mensaje){
        $CI =& get_instance();
        $CI->load->library('email');

        $html = "
                <html>
                    <p>{$mensaje}</p>
                    <br>
                    <p>
                        Saludos
                    </p>
                </html>
                ";
        $CI->email->from('no-responder@allaria.com.ar', 'Sistema de Suscripciones');
        $CI->email->to(array('jleis@allariaycia.com'));

        $CI->email->subject('ERROR');
        $CI->email->set_crlf( "\r\n" );
        $CI->email->message($html);	

        $CI->email->send();
    }
}