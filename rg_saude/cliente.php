<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('soap.wsdl_cache_enabled', '0'); 
ini_set('soap.wsdl_cache_ttl', '0'); 

error_reporting(E_ALL);

$options=array(
  'trace' => 1,
  'exceptions' => true,
  'connection_timeout' => 1
);

$client = new SoapClient("https://webservices.austaclinicas.com.br/rg_saude/server.php?wsdl", $options);


//////POR ARRAY
//verifica as funçoes
echo '<pre>';
var_dump($client->__getFunctions());

//chama a função
//$function = "getToken";

// $arguments= array('TokenRequest'=>array('usuario' =>'CENE',
//   										'senha'=>'4cU35nT3@1'));
//$obj = $client->__soapCall($function, $xmlVar);
//var_dump($obj);

//$resposta= json_decode(json_encode($obj,true));


//////POR XML

?>