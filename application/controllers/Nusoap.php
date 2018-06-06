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
    
    
    public function testNosis7(){
        
        
        //Comentario
        
        require_once APPPATH."/third_party/nusoap/lib/nusoap.php";
        $client = new nusoap_client('https://ws02.nosis.com/soap12');

//        $client->setCredentials($exch_user, $exch_pass, 'ntlm');
//        $client->setUseCURL(true);
//        $client->useHTTPPersistentConnection();
////        $client->setCurlOption(CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
////        $client->setCurlOption(CURLOPT_USERPWD, $exch_user.':'.$exch_pass);
//        $client->setCurlOption(CURLOPT_SSL_VERIFYPEER, false);
//        $client->setCurlOption(CURLOPT_SSL_VERIFYHOST, false);
//        $client->soap_defencoding = 'UTF-8';
        
        
        
        $err = $client->getError();
        if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        }
        // Doc/lit parameters get wrapped
        $params = array(
            'Usuario'         => '62112',
            'Token'           => '394233',
            'Documento'       => '34536629',
            'RazonSocial'     => 'Micaela Petterson',
            'Sexo'            => 'F',
            'FechaNacimiento' => '18/11/1988',
            'NroGrupoVID'     => 1
            ); 
        
//        $params = array('name'=>'Bojjaiah');
        
        $result = $client->call('Validacion', $params, '', '', false, true);

//        $result = $client->call('HelloWorld', $params, '', '', false, true);
        
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
        echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
        echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
    }
    
    
    public function testNosis6(){
        
        
        require_once APPPATH."/third_party/nusoap/lib/nusoap.php";
        $client = new nusoap_client('https://calculadora.allaria.test/test');

        $err = $client->getError();
        if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        }
        // Doc/lit parameters get wrapped
//        $params = array(
//            'Usuario'         => "474631",
//            'Token'           => '233342',
//            'Documento'       => '34536629',
//            'RazonSocial'     => 'Micaela Petterson',
//            'Sexo'            => 'F',
//            'FechaNacimiento' => '18/11/1988',
//            'Email'           => 'AB-12345',
//            'Celular'         => 'AC-12345',
//            'NroGrupoVID'     => '1',
//            'CBU'             => ''
//        );
        
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
        echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
        echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
    }
    
    
  /*  
    public function testNosis5(){

        $client = new SoapClient('https://ws02.nosis.com/soap12',
                    array(
                        'trace' => true, 
//                        'keep_alive' => true,
//                        'connection_timeout' => 5000,
//                        'cache_wsdl' => WSDL_CACHE_NONE,
//                        'compression'   => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
                        )
                    );     
        // Doc/lit parameters get wrapped
        $params = array(
            'Usuario'         => '62112',
            'Token'           => '394233',
            'Documento'       => '34536629',
            'RazonSocial'     => 'Micaela Petterson',
            'Sexo'            => 'F',
            'FechaNacimiento' => '18/11/1988',
            'NroGrupoVID'     => 1
            );       
              
//        $client->soap_defencoding = 'UTF-8';
//        $client->decode_utf8 = false;
        
        $result = $client->__call('Validacion', array('parameters' => $params));

        echo "<pre>";
        print_r($client);
        echo "</pre>";
        
        echo "<pre>";
        var_dump($result);
        echo "</pre>";
        
        
//        print_r($client->response);
        
    }        
    
    
    
   
    
    
    public function testNosis4(){
        
        
        require_once APPPATH."/third_party/nusoap/lib/nusoap.php";
       
        $client = new nusoap_client('https://ws02.nosis.com/soap12', array('trace' =>true));      

        // Doc/lit parameters get wrapped
        $params = array(
            'Usuario'         => '62112',
            'Token'           => '394233',
            'Documento'       => '34536629',
            'RazonSocial'     => 'Micaela Petterson',
            'Sexo'            => 'F',
            'FechaNacimiento' => '18/11/1988',
            'NroGrupoVID'     => 1
            );       
                
        $result = $client->call('Validacion', $params, 'http://schemas.nosis.com/sac/ws02/types/Validacion', "http://schemas.nosis.com/sac/ws02/types/Validacion");

        echo "<pre>";
        print_r($client);
        echo "</pre>";
        
        print_r($client->response);
        
        echo "<pre>";
        print_r($result);
        echo "</pre>";      
        
        
    }    
    
//    public function client6(){
//        
//        
////        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
////        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
////        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
////        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
////        $useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';
//        
////        $client = new nusoap_client("http://ws02.nosis.com/soap12", false);
//        
//        
//        
//        $client = new nusoap_client('https://ws02.nosis.com/soap12', array('trace' =>true));
//        
//        
//        
//        $err = $client->getError();
//        if ($err) {
//                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
//                echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
//                exit();
//        }
//        $client->setUseCurl($useCURL);
//        $client->soap_defencoding = 'UTF-8';
//
//        //echo 'You must set your own Google key in the source code to run this client!'; exit();
//        $params = array(
//            'Usuario'         => "474631",
//            'Token'           => '233342',
//            'Documento'       => '34536629',
//            'RazonSocial'     => 'Micaela Petterson',
//            'Sexo'            => 'F',
//            'FechaNacimiento' => '18/11/1988',
//            'Email'           => 'AB-12345',
//            'Celular'         => 'AC-12345',
//            'NroGrupoVID'     => '1',
//            'CBU'             => ''
//        );
//        $result = $client->call("Validacion", $params, '', "http://schemas.nosis.com/sac/ws02/types/Validacion");
//        if ($client->fault) {
//                echo '<h2>Fault</h2><pre>'; print_r($result); echo '</pre>';
//        } else {
//                $err = $client->getError();
//                if ($err) {
//                        echo '<h2>Error</h2><pre>' . $err . '</pre>';
//                } else {
//                        echo '<h2>Result</h2><pre>'; print_r($result); echo '</pre>';
//                }
//        }
//        echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//        echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
//        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
//    }
//    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
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
//        $client = new nusoap_client('http://ws02.nosis.com/soap12', false);      

        $client = new nusoap_client(
                    'https://ws02.nosis.com/soap12',
                    array('trace' =>true,
//                          'connection_timeout' => 500000,
//                          'cache_wsdl' => WSDL_CACHE_BOTH,
//                          'keep_alive' => false,
//                          'encoding'   => 'UTF-8'
//                          'soap_version' => 'SOAP_1_2'
//                          'stream_context' => $context
                          )
                  );
        
        
//                $client->setHeaders('<soap:Header xmlns="https://www.xxxxx.com/aspapi"><Token>xxxxx</Token></soap:Header>');

        
//        $mode = array (
//            'soap_version'  => 'SOAP_1_2', // use soap 1.1 client
//            'keep_alive'    => true,
//            'trace'         => 1,
//            'encoding'      =>'UTF-8',
//            'compression'   => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
//            'exceptions'    => true,
//            'cache_wsdl'    => WSDL_CACHE_NONE,
//            'stream_context' => stream_context_create ( 
//                array (
//                    'http' => array('header' => 'Content-Encoding: XXX'),
//                )
//            )
//        );
//
//        // initialize soap client
//        $client = new nusoap_client ('http://testws02.nosis.com/soap12', $mode );
        
        
        
        
//        $client->http_encoding = 'utf-8';
//        $client->defencoding = 'utf-8';
//        $client->soap_defencoding = 'UTF-8';
//        $client->decode_utf8 = false;

//        $client->setUseCurl($useCURL);
        $client->soap_defencoding = 'UTF-8';
        
        $err = $client->getError();
        if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        }
        // Doc/lit parameters get wrapped
        $params = array(
            'Usuario'         => '62112',
            'Token'           => '394233',
            'Documento'       => '34536629',
            'RazonSocial'     => 'Micaela Petterson',
            'Sexo'            => 'F',
            'FechaNacimiento' => '18/11/1988',
            'NroGrupoVID'     => 1
            ); 
        
//        $params =   '<Usuario>62112</Usuario>'.
//                    '<Token>394233</Token>'.
//                    '<Documento>34536629</Documento>'.
//                    '<RazonSocial>Micaela Petterson</RazonSocial>'.
//                    '<Sexo>F</Sexo>'.
//                    '<FechaNacimiento>18/11/1988</FechaNacimiento>'.
//                    '<NroGrupoVID>1</NroGrupoVID>';
        
//        array('parameters' => $params;
                
        $result = $client->call('Validacion', $params, '',"http://schemas.nosis.com/sac/ws02/types/Validacion");
        
//        $result = $client->call('Validacion', $params, '', '', false, false);
        
        
//        $result = $client->call('Validacion', $params);
        
//        client->send(XML, SoapAction) 
//        as oppposed to $client->call(SoapAction, XML)
        
//        echo "<pre>";
//        print_r($client);
        
        echo "<pre>";
        var_dump($result);
        
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
        echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
        echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
        
        
        
        
        
//        echo "<pre>";
//        print_r($client);
//        echo "</pre>";
//        
//        print_r($client->response);
//        
//        echo "<pre>";
//        echo "Resultadoss:";
//        var_dump($result);
//        echo "</pre>";      
        
        
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
    
    public function testNosis2(){
        
        require_once APPPATH."/third_party/nusoap/lib/nusoap.php";
        

        $client = new nusoap_client('http://testws02.nosis.com/soap12');
        

        
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
            'Email'           => 'AB-12345',
            'Celular'         => 'AC-12345',
            'NroGrupoVID'     => '1',
            'CBU'             => ''
        );
        
//        $params = array('name'=>'Bojjaiah');
        
        $result = $client->call('Validacion', $params, '', '', false, true);
        
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
                        echo '<h2>Error</h2><pre>' . $err . '</pre>';
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
    
    
    public function prueba3(){
        
        require_once APPPATH."/third_party/nusoap/lib/nusoap.php";
       
        $response = new nusoap_client('http://testws02.nosis.com/soap12');

        
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
        
        $result = $response->call('Validacion',array());

        $result1 = $response->call('Validacion',array());

//        echo "<pre>";
//        var_dump($result);
//        echo "<pre>";
//        var_dump($result1);
//        echo "<pre>";
//        echo '<br/><br/>';
        echo "<pre>";
        print_r($response);
        echo "<pre>";
        echo '<br/><br/>';
        
    }


    
    
    
    
    
    public function testNosis(){
        
        
        require_once APPPATH."/third_party/nusoap/lib/nusoap.php";

//        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
//        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
//        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
//        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $client = new nusoap_client('https://calculadora.allaria.test/test');
        
//        echo "<pre>";
//        print_r($client); die;
        
        $err = $client->getError();
        if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        }
        // Doc/lit parameters get wrapped
//        $params = array(
//            'Usuario'         => "474631",
//            'Token'           => '233342',
//            'Documento'       => '34536629',
//            'RazonSocial'     => 'Micaela Petterson',
//            'Sexo'            => 'F',
//            'FechaNacimiento' => '18/11/1988',
//            'Email'           => 'AB-12345',
//            'Celular'         => 'AC-12345',
//            'NroGrupoVID'     => '1',
//            'CBU'             => ''
//        );
        
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
        echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
        echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
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
    
  


    
    
    public function prueba2(){//Funciona. Llama  a Test
        
        require_once APPPATH."/third_party/nusoap/lib/nusoap.php";
       
        $response = new nusoap_client('https://calculadora.allaria.test/test');

        $result = $response->call('HelloWorld',array('name'=>'Bojjaiah'));

        $result1 = $response->call('HelloComplexWorld',array());

        echo "<pre>";
        var_dump($result);
        echo "<pre>";
        var_dump($result1);
        echo "<pre>";
        echo '<br/><br/>';
        echo "<pre>";
        print_r($response);
        echo "<pre>";
        echo '<br/><br/>';
        
    }
    
    public function prueba(){
        
        require_once APPPATH."/third_party/nusoap/lib/nusoap.php";
       
        $response = new soapclient('https://calculadora.allaria.test/nusoap');

        $result = $response->call('HelloWorld',array('name'=>'Bojjaiah'));

        $result1 = $response->call('HelloComplexWorld',array());

        var_dump($result);
        var_dump($result1);
        echo '<br/><br/>';
        echo "<pre>";
        print_r($response);
        echo "<pre>";
        echo '<br/><br/>';

    }
  
    
    public function test(){
//        require_once('../lib/nusoap.php');
//        require_once APPPATH."/third_party/lib/nusoap.php";
//        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
//        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = '474631';
        $proxypassword = '233342';
        $client = new soapclient('https://calculadora.allaria.test/nusoap.php', false, $proxyusername, $proxypassword);
        $err = $client->getError();
        if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        }
        
//        $result = $client->call('Validacion', array(), 'https://vid.nosis.com/', 'getVersion');
        $params = array(
            'nombre'         => "Mica"
        );
//        $result = $client->call('Validacion', $params, 'https://vid.nosis.com/', 'https://vid.nosis.com/');
        $result = $client->call('HelloWorld', array('parameters' => $params), '', '', false, true);
        
        if ($client->fault) {
                echo '<h2>Fault</h2><pre>'; print_r($result); echo '</pre>';
        } else {
                $err = $client->getError();
                if ($err) {
                        echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                        echo '<h2>Result</h2><pre>'; print_r($result); echo '</pre>';
                }
        }
        echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
        echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

    }
    
    public function test2(){
//        require_once('../lib/nusoap.php');
//        require_once APPPATH."/third_party/lib/nusoap.php";
//        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
//        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
//        $proxyusername = '474631';
//        $proxypassword = '233342';
        $client = new soapclient('https://calculadora.allaria.test/nusoap.php', false, $proxyusername, $proxypassword);
        $err = $client->getError();
        if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        }
        
//        $result = $client->call('Validacion', array(), 'https://vid.nosis.com/', 'getVersion');
        $params = array(
            'name'         => "Mica",
        );
//        $result = $client->call('Validacion', $params, 'https://vid.nosis.com/', 'https://vid.nosis.com/');
        $result = $client->call('HelloWorld', array('parameters' => $params), '', '', false, true);
        
        if ($client->fault) {
                echo '<h2>Fault</h2><pre>'; print_r($result); echo '</pre>';
        } else {
                $err = $client->getError();
                if ($err) {
                        echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                        echo '<h2>Result</h2><pre>'; print_r($result); echo '</pre>';
                }
        }
//        echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//        echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
//        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

    }
 
//    public function testNosis3(){
//        
//        $client = new nusoap_client('http://testws02.nosis.com/soap12');
//        
//        $params=array(
//            'Usuario'         => '474631',
//            'Token'           => '233342',
//            'Documento'       => '34536629',
//            'RazonSocial'     => 'Micaela Petterson',
//            'Sexo'            => 'F',
//            'FechaNacimiento' => '18/11/1988',
//            'Email'           => 'AB-12345',
//            'Celular'         => 'AC-12345',
//            'NroGrupoVID'     => '1',
//            'CBU'             => ''
//        );
//        
//        $comando = 'Validacion';
//        $response = $client->call($comando, $params);
//        
//        echo "<pre>";
//        print_r($response);
//        
////        $peticion_xml = $client->__getLastRequest();
////        
////        echo "pre";
////        print_r($peticion_xml);
//        
//    }
    
    

    

    public function test3(){

        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $client = new nusoap_client('https://ws02.nosis.com/soap12', 'wsdl',
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
        
//        echo "<pre>";
//        var_dump($client);
//        echo "</pre>";
//        
        $result = $client->call('Validacion', array('parameters' => $params), '', '', false, true);
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

    
    
    
    public function client1(){
        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';
        $client = new nusoap_client("http://testws02.nosis.com/soap12", false,
                                                        $proxyhost, $proxyport, $proxyusername, $proxypassword);
        $err = $client->getError();
        if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
                echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
                exit();
        }
        $client->setUseCurl($useCURL);
        // This is an archaic parameter list
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
        $result = $client->call('Validacion', $params, 'http://testws02.nosis.com/soap12', 'http://testws02.nosis.com/soap12');
        if ($client->fault) {
                echo '<h2>Fault (Expect - The request contains an invalid SOAP body)</h2><pre>'; print_r($result); echo '</pre>';
        } else {
                $err = $client->getError();
                if ($err) {
                        echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                        echo '<h2>Result</h2><pre>'; print_r($result); echo '</pre>';
                }
        }
        echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
        echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
    }
    
    
    public function client2(){
        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';
        $client = new nusoap_client("http://testws02.nosis.com/soap12", false,
                                                        $proxyhost, $proxyport, $proxyusername, $proxypassword);
        $err = $client->getError();
        if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
                echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
                exit();
        }
        $client->setUseCurl($useCURL);
        $client->useHTTPPersistentConnection();
        $param = array(
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
        $params = array('Validacion' =>
                                        new soapval('Validacion',
                                                    'Validacion',
                                                    $param,
                                                    false,
                                                    'http://testws02.nosis.com/soap12')
                                        );
        $result = $client->call('Validacion', $params, 'http://testws02.nosis.com/soap12', 'http://testws02.nosis.com/soap12');
        if ($client->fault) {
                echo '<h2>Fault</h2><pre>'; print_r($result); echo '</pre>';
        } else {
                $err = $client->getError();
                if ($err) {
                        echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                        echo '<h2>Result</h2><pre>'; print_r($result); echo '</pre>';
                }
        }
        echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
        echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
    }
    
    public function client3(){
        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';
        $client = new nusoap_client("http://testws02.nosis.com/soap12", false,
                                                        $proxyhost, $proxyport, $proxyusername, $proxypassword);
        $err = $client->getError();
        if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
                echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
                exit();
        }
        $client->setUseCurl($useCURL);
        $client->soap_defencoding = 'UTF-8';

        //echo 'You must set your own Google key in the source code to run this client!'; exit();
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
        $result = $client->call("Validacion", $params, "urn:Validacion", "urn:Validacion");
        if ($client->fault) {
                echo '<h2>Fault</h2><pre>'; print_r($result); echo '</pre>';
        } else {
                $err = $client->getError();
                if ($err) {
                        echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                        echo '<h2>Result</h2><pre>'; print_r($result); echo '</pre>';
                }
        }
        echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
        echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
    }
    
    
    
    public function client4(){
        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $client = new soapclient('http://testws02.nosis.com/soap12', true,
                                                        $proxyhost, $proxyport, $proxyusername, $proxypassword);
        $err = $client->getError();
        if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        }
        
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
        
        $result = $client->call('Validacion', $params, 'http://testws02.nosis.com/soap12', 'http://testws02.nosis.com/soap12');

        if ($client->fault) {
                echo '<h2>Fault</h2><pre>'; print_r($result); echo '</pre>';
        } else {
                $err = $client->getError();
                if ($err) {
                        echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                        echo '<h2>Result</h2><pre>' . htmlspecialchars($result, ENT_QUOTES) . '</pre>';
                }
        }
//        echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//        echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
//        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
    }
    
    
    public function client5(){
//        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
//        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
//        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
//        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $client = new soapclient('http://testws02.nosis.com/soap12');
        
        echo "<pre>";
        print_r($client);
        
        $err = $client->getError();
        if ($err) {
                echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
        }
        
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
        
        $result = $client->call('Validacion', $params, 'http://testws02.nosis.com/soap12', 'http://testws02.nosis.com/soap12');
        
        print_r('asd');
        print_r($result);
        
        if ($client->fault) {
                echo '<h2>Fault</h2><pre>'; print_r('Todo mal'); echo '</pre>';
        } else {
                $err = $client->getError();
                if ($err) {
                        echo '<h2>Error</h2><pre>' . $err . '</pre>';
                } else {
                        echo '<h2>Result</h2><pre>' . htmlspecialchars($result, ENT_QUOTES) . '</pre>';
                }
        }
//        echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//        echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
//        echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
    }
    
    */
    
}