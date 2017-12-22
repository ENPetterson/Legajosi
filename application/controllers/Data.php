<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Data extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }
    
    public function import(){
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 600);
        $clave = $this->generarClave();
        $url = URL_SUSCRIPCION . '/data/getData/' . $clave;
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,$url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Suscripcion');
        $datosEnc = curl_exec($curl_handle);
        curl_close($curl_handle);
        
        $datos = json_decode($datosEnc);

        
        if ($datos[0]->id > 0){

            $this->load->model('Data_model');
            $this->Data_model->datos = $datos[0];
            $resultado = $this->Data_model->importar();

            echo json_encode($resultado);

            if ($resultado['id'] > 0){
                $clave = $this->generarClave();
                $idEnc = urlencode(urlencode(urlencode($this->encrypt->encode($resultado['solicitud']))));
                $url = URL_SUSCRIPCION . '/data/setEnviado/' . $idEnc . '/' . $clave;
                $curl_handle=curl_init();
                curl_setopt($curl_handle, CURLOPT_URL,$url);
                curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Suscripcion');
                $datosEnc = curl_exec($curl_handle);
                curl_close($curl_handle);

            }
        }

    }
    
    public function importarProductores(){
        $this->load->model('Data_model');
        $this->Data_model->importarProductores();
    }
    
    private function generarClave(){
        $fecha = new DateTime();
        $fechaStr = $fecha->format('Y-m-d H:i:s');
        
        $clave = urlencode(urlencode(urlencode($this->encrypt->encode($fechaStr))));
        
        return $clave;
    }
    
   
}
