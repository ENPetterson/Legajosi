<?php

require_once APPPATH."/third_party/nusoap/lib/nusoap.php";

$namespace = "https://calculadora.allaria.test/test";
// create a new soap server
$server = new soap_server();
// configure our WSDL
$server->configureWSDL("HelloExample");
// set our namespace
$server->wsdl->schemaTargetNamespace = $namespace;

//Register a method that has parameters and return types
$server->register(
                // method name:
                'HelloWorld', 	
                // parameter list:
                array('name'=>'xsd:string'), 
                // return value(s):
                array('return'=>'xsd:string'),
                // namespace:
                $namespace,
                // soapaction: (use default)
                false,
                // style: rpc or document
                'rpc',
                // use: encoded or literal
                'encoded',
                // description: documentation for the method
                'Simple Hello World Method');

//Create a complex type
$server->wsdl->addComplexType('MyComplexType','complexType','struct','all','',
		       array( 'contact' => array('name' => 'contact',
                                                 'type' => 'xsd:string'),
			      'email'   => array('name' => 'email',
                                                 'type' => 'xsd:string')));

//Register our method using the complex type
$server->register(
                // method name:
                'HelloComplexWorld', 	
                // parameter list:
                array(), 
                // return value(s):
                array('return'=>'tns:MyComplexType'),
                // namespace:
                $namespace,
                // soapaction: (use default)
                false,
                // style: rpc or document
                'rpc',
                // use: encoded or literal
                'encoded',
                // description: documentation for the method
                'Complex Hello World Method');

//Our Simple method
function HelloWorld($name)
{
	return "Hello " . $name;
}

////Our complex method
function HelloComplexWorld()
{
//   return $mycomplextype;
        
   $result = array('contact' => 'Bojjaiah', 'email' => 'bbb');

   
   return $result;	
    
    
    
    
}

// Get our posted data if the service is being consumed
// otherwise leave this data blank.                
$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';

// pass our posted data (or nothing) to the soap service                    
//$server->service($POST_DATA);       
$server->service(file_get_contents("php://input"));
exit();











/*
 *	$Id: client1.php,v 1.3 2007/11/06 14:48:24 snichol Exp $
 *
 *	Client sample that should get a fault response.
 *
 *	Service: SOAP endpoint
 *	Payload: rpc/encoded
 *	Transport: http
 *	Authentication: none
 */

//require_once APPPATH."/third_party/nusoap/lib/nusoap.php";
//$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
//$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
//$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '474631';
//$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '233342';
//$useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';
//$client = new nusoap_client("https://vid.nosis.com/", false,
//						$proxyhost, $proxyport, $proxyusername, $proxypassword);
//$err = $client->getError();
//if ($err) {
//	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
//	echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
//	exit();
//}
//$client->setUseCurl($useCURL);
//// This is an archaic parameter list
//$params = array(
//    'Usuario'         => "474631",
//    'Token'           => '233342',
//    'Documento'       => '34536629',
//    'RazonSocial'     => 'Micaela Petterson',
//    'Sexo'            => 'F',
//    'FechaNacimiento' => '18/11/1988',
//    'Email'           => 'AB-12345',
//    'Celular'         => 'AC-12345',
//    'NroGrupoVID'     => '1',
//    'CBU'             => ''
//);
//$result = $client->call('Validacion', $params, 'https://vid.nosis.com/', 'https://vid.nosis.com/');
//if ($client->fault) {
//	echo '<h2>Fault (Expect - The request contains an invalid SOAP body)</h2><pre>'; print_r($result); echo '</pre>';
//} else {
//	$err = $client->getError();
//	if ($err) {
//		echo '<h2>Error</h2><pre>' . $err . '</pre>';
//	} else {
//		echo '<h2>Result</h2><pre>'; print_r($result); echo '</pre>';
//	}
//}
//echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
//echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
//echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->getDebug(), ENT_QUOTES) . '</pre>';
//

/*
require_once APPPATH."/third_party/nusoap/lib/nusoap.php";
$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '474631';
$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '233342';
$useCURL = isset($_POST['usecurl']) ? $_POST['usecurl'] : '0';
$client = new nusoap_client("https://ws02.nosis.com/soap12", false,
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
$result = $client->call('Validacion', $params, 'https://ws02.nosis.com/soap12', 'https://ws02.nosis.com/soap12');
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
*/



