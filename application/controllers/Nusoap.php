<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/PHPExcel.php";
require_once APPPATH."/third_party/nusoap/lib/nusoap.php";

class Nusoap extends MY_AuthController{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $views = array(
            'template/encabezado', 
            'template/menu',
            'nusoap/nusoap', 
            'template/pie'
            );
        foreach ($views as $value) {
            $this->load->view($value);
        }
    }
    
    public function testNosis3(){
        
        
        require_once APPPATH."/third_party/nusoap/lib/nusoap.php";
       
//        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
//        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
//        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
//        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
//
//        $client = new nusoap_client("https://secure2.e-xact.com/vplug-in/transaction/rpc-enc/service.asmx?wsdl", 
//                  array('trace' =>true,'connection_timeout' => 500000,'cache_wsdl' => WSDL_CACHE_BOTH,'keep_alive' => false), 
//                  $proxyhost, 
//                  $proxyport, 
//                  $proxyusername, 
//                  $proxypassword); 

//        $aHTTP['http']['header'] =  "User-Agent: PHP-SOAP/5.5.11\r\n";
//        $aHTTP['http']['header'].= "username: XXXXXXXXXXX\r\n"."password: XXXXX\r\n";
//        $context = stream_context_create($aHTTP);       
//        $client = new nusoap_client('http://testws02.nosis.com/soap12', array('trace' => 1,"stream_context" => $context));
//        $client = new nusoap_client('http://testws02.nosis.com/soap12');      

//        $client = new nusoap_client(
//                    'http://testws02.nosis.com/soap12',
//                    array('trace' =>true,
//                          'connection_timeout' => 500000,
//                          'cache_wsdl' => WSDL_CACHE_BOTH,
//                          'keep_alive' => false,
//                          'encoding'   => 'UTF-8'
////                          'stream_context' => $context
//                          )
//                  );
        
        $mode = array (
            'soap_version'  => 'SOAP_1_2', // use soap 1.1 client
            'keep_alive'    => true,
            'trace'         => 1,
            'encoding'      =>'UTF-8',
            'compression'   => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
            'exceptions'    => true,
            'cache_wsdl'    => WSDL_CACHE_NONE,
            'stream_context' => stream_context_create ( 
                array (
                    'http' => array('header' => 'Content-Encoding: XXX'),
                )
            )
        );

        // initialize soap client
        $client = new nusoap_client ('http://testws02.nosis.com/soap12', $mode );
        
//        $client->http_encoding = 'utf-8';
//        $client->defencoding = 'utf-8';
//        $client->soap_defencoding = 'UTF-8';
//        $client->decode_utf8 = false;

        $err = $client->getError();
        if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        }
        // Doc/lit parameters get wrapped
        $params = array(
            'Usuario'         => '474631',
            'Token'           => '233342',
            'Documento'       => '34536629',
            'RazonSocial'     => 'Micaela Petterson',
            'Sexo'            => 'F',
            'FechaNacimiento' => '18/11/1988',
            'Email'           => '',
            'Celular'         => '',
            'NroGrupoVID'     => '1',
            'CBU'             => ''
        );
                
//        $result = $client->call('Validacion', $params, '', '', false, true);
        $result = $client->call('Validacion', $params);
        
//        client->send(XML, SoapAction) 
//        as oppposed to $client->call(SoapAction, XML)
        
        echo "<pre>";
        print_r($client);
        
        echo "<pre>";
        print_r($result);
        
        // Check for a fault
        if ($client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($result);
                echo '</pre>';
        } else {
                // Check for errors
                $err = $client->getError();
                if ($err) {
                        // Display the error
                        echo '<h2>Error:</h2><pre>' . $err . '</pre>';
                } else {
                        // Display the result
                        echo '<h2>Result</h2><pre>';
                        print_r($result);
                        echo '</pre>';
                }
        }
//        echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//        echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
//        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
    }    
    
    
    public function prueba4(){
        
        require_once APPPATH."/third_party/nusoap/lib/nusoap.php";
       
        
        $aHTTP['http']['header'] =  "User-Agent: PHP-SOAP/5.5.11\r\n";
        $aHTTP['http']['header'].= "username: XXXXXXXXXXX\r\n"."password: XXXXX\r\n";
        $context = stream_context_create($aHTTP);
        
//        $response = new SoapClient('http://testws02.nosis.com/soap12',array('trace' => 1,"stream_context" => $context));

        $response = new SoapClient('http://testws02.nosis.com/soap12',
                                    array('trace' =>true,
                                    'connection_timeout' => 500000,
                                    'cache_wsdl' => WSDL_CACHE_BOTH,
                                    'keep_alive' => false,
            ));
//    'compression'   => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,

        
        $params = array(
            'Usuario'         => "474631",
            'Token'           => '233342',
            'Documento'       => '34536629',
            'RazonSocial'     => 'Micaela Petterson',
            'Sexo'            => 'F',
            'FechaNacimiento' => '18/11/1988',
            'Email'           => 'AB-12345',
            'Celular'         => 'AC-12345',
            'NroGrupoVID'     => '1',
            'CBU'             => ''
        );
        
        $result = $response->call('Validacion', $params, '', '', false, true);

//        $result = $response->call('Validacion',array());

//        echo "<pre>";
//        var_dump($result);
//        echo "<pre>";
//        var_dump($result1);
//        echo "<pre>";
//        echo '<br/><br/>';
        echo "<pre>";
        print_r($response);
        echo "<pre>";
        
        echo "<pre>";
        var_dump($result);
        echo "<pre>";
        
        echo '<br/><br/>';
        
    }
        
    
    public function test(){

        $client = new nusoap_client('https://calculadora.allaria.test/test');

        $err = $client->getError();
        if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        }

        $params = array('name'=>'Bojjaiah');
        
        $result = $client->call('HelloWorld', $params, '', '', false, true);
        
        echo "<pre>";
        print_r($result);
        
        // Check for a fault
        if ($client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($result);
                echo '</pre>';
        } else {
                // Check for errors
                $err = $client->getError();
                if ($err) {
                        // Display the error
                        echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                        // Display the result
                        echo '<h2>Result</h2><pre>';
                        print_r($result);
                        echo '</pre>';
                }
        }
    }
    
    public function tests(){

        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $client = new nusoap_client('http://www.xignite.com/xquotes.asmx?WSDL', 'wsdl',
                                                        $proxyhost, $proxyport, $proxyusername, $proxypassword);
        
        $err = $client->getError();
        if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        }
        // Doc/lit parameters get wrapped
        $params = array(
            'Usuario'         => "474631",
            'Token'           => '233342',
            'Documento'       => '34536629',
            'RazonSocial'     => 'Micaela Petterson',
            'Sexo'            => 'F',
            'FechaNacimiento' => '18/11/1988',
            'Email'           => 'AB-12345',
            'Celular'         => 'AC-12345',
            'NroGrupoVID'     => '1',
            'CBU'             => ''
        );
        

        
        $result = $client->call('GetQuickQuotes', array('parameters' => $params), '', '', false, true);
        
        // Check for a fault
        if ($client->fault) {
                echo '<h2>Fault</h2><pre>';
                print_r($result);
                echo '</pre>';
        } else {
                // Check for errors
                $err = $client->getError();
                if ($err) {
                        // Display the error
                        echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                        // Display the result
                        echo '<h2>Result</h2><pre>';
                        print_r($result);
                        echo '</pre>';
                }
        }
        echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
        echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
    }
    
    
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
    
    
    
}