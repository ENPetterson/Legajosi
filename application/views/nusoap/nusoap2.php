<!--POST /soap12 HTTP/1.1
Host: localhost
Content-Type: text/xml; charset=utf-8
Content-Length: length
<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
<soap12:Body>
<Validacion xmlns:i="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://schemas.nosis.com/sac/ws02/types">
<Usuario>474631</Usuario>
<Token>233342</Token>
<Documento>String</Documento>
<RazonSocial>String</RazonSocial>
<Sexo>String</Sexo>
<FechaNacimiento>String</FechaNacimiento>
<Email>String</Email>
<Celular>String</Celular>
<NroGrupoVID>String</NroGrupoVID>
<Cbu>String</Cbu>
</Validacion>
</soap12:Body>
</soap12:Envelope>-->

<?php

require_once APPPATH."/third_party/nusoap/lib/nusoap.php";
 
//Create a new soap server
$server = new soap_server();
 
//Define our namespace
$namespace = "https://calculadora.allaria.test/nusoap.php";
$server->wsdl->schemaTargetNamespace = $namespace;
 
//Configure our WSDL
$server->configureWSDL("HelloWorld");
 
// Register our method
$server->register('HelloWorld');
 
function HelloWorld()
{
return "Hello, World!";
}
 
// Get our posted data if the service is being consumed
// otherwise leave this data blank.
$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
 
// pass our posted data (or nothing) to the soap service
$server->service($POST_DATA);
exit(); 

