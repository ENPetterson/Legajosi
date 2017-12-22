<?php
class Mailing_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    
    public $newsletter_id;
    public $listas;
    
    public function enviarNewsletter(){
        $newsletter = R::load('newsletter', $this->newsletter_id);
        $suscriptores = array();
        foreach ($this->listas as $lista) {
            $listaBean = R::load('lista', $lista);
            $beansSuscriptor = R::related($listaBean, 'suscriptor');
            foreach ($beansSuscriptor as $beanSuscriptor) {
                $esta = false;
                /*
                foreach ($suscriptores as $suscriptor){
                    if ($suscriptor['email'] == $beanSuscriptor->mail ){
                        $esta = true;
                        break;
                    }
                }
                 * 
                 */
                if (!$esta){
                    array_push($suscriptores, $beanSuscriptor->mail);
                }
            }

        }
        
        $this->load->model('Newsletter_model');
        $this->Newsletter_model->id = $this->newsletter_id;
        $resultado = $this->Newsletter_model->getContenido(true);
        
        $attachments = array();
        
        $beansAttachments = R::related($newsletter, 'adjunto');        
        foreach($beansAttachments as $beanAttachment){
            if ($beanAttachment->adjuntar == 1){
                array_push($attachments, array(
                    'type' => 'application/pdf',
                    'name' => $beanAttachment->archivo,
                    'content' => $beanAttachment->contenido
                ));
            }
        }
        $tags = array('FH:' . date('Y-m-d H:i:s'), $resultado['subject']);
        
        $contenido = $resultado['contenido'];
        
        $suscriptoresSendgrid = array_chunk($suscriptores, 9999);
        
        foreach ($suscriptoresSendgrid as $susc) {
            $respuesta = $this->enviarMail($contenido, $resultado['senderNombre'], $resultado['senderMail'], $resultado['subject'], $susc, $tags, $resultado['images'], $attachments);
        }
        
        
        $this->load->model('Envio_model');
        $this->Envio_model->newsletter_id = $this->newsletter_id;
        $this->Envio_model->contenido = $contenido;
        $this->Envio_model->subject = $resultado['subject'];
        $this->Envio_model->tags = $tags;
        $this->Envio_model->detalle = $respuesta;
        $envio_id = $this->Envio_model->saveEnvio();
        
        return array('envio_id'=>$envio_id);
        
    }
            
    
    public function enviarMail($html, $senderNombre, $senderMail, $subject, $to, $tags, $images, $attachments){
        
        $this->load->config('sendgrid');
        $this->load->library('sendgrid');
        
        
        $sendgrid_username = $this->config->item('sendgrid_username');
        $sendgrid_password = $this->config->item('sendgrid_password');
        $sendgrid = new SendGrid($sendgrid_username, $sendgrid_password, array("turn_off_ssl_verification" => true));
        
        $email    = new SendGrid\Email();
        
        $email->setSmtpapiTos($to)->
               setFrom($senderMail)->
               setFromName($senderNombre)->
               setSubject($subject)->
               setHtml($html)->
               addHeader('X-Sent-Using', 'SendGrid-API')->
               addHeader('X-Transport', 'web');
        
        
        $imagePath =  $_SERVER['DOCUMENT_ROOT'] . '/tmp/';
        foreach ($images as $image){
            file_put_contents($imagePath . $image['name'] . '.png', base64_decode($image['content']));
            $email->addAttachment($imagePath . $image['name'] . '.png', $image['name'] . '.png', $image['name']);
        }
        
        foreach($attachments as $attach){
            $email->addAttachment($attach);
        }

        
        $response = $sendgrid->send($email);
        
        
        
        return array('response'=>$response->code);
        
        /*
        
        $message = array(
            'html' => $html,
            'subject' => $subject,
            'from_email' => $senderMail,
            'from_name' => $senderNombre,
            'to' => $to,
            'headers' => array('Reply-To' => $senderMail),
            'important' => false,
            'track_opens' => true,
            'track_clicks' => true,
            'auto_text' => false,
            'auto_html' => true,
            'inline_css' => true,
            'url_strip_qs' => null,
            'preserve_recipients' => false,
            'view_content_link' => null,
            'tracking_domain' => null,
            'signing_domain' => null,
            'return_path_domain' => null,
            'merge' => true,
            'merge_language' => 'mailchimp',
            'global_merge_vars' => array(
                array(
                    'name' => 'merge1',
                    'content' => 'merge1 content'
                )
            ),
            'merge_vars' => array(
                array(
                    'rcpt' => 'recipient.email@example.com',
                    'vars' => array(
                        array(
                            'name' => 'merge2',
                            'content' => 'merge2 content'
                        )
                    )
                )
            ),
            'tags' => $tags,
            'subaccount' => null,
            'attachments' => $attachments,
            'images' => $images
        );
        $async = false;
        $ip_pool = 'Main Pool';
        $send_at = '';
        $result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);
        return array('response'=>$result);
         * 
         */
    }

}