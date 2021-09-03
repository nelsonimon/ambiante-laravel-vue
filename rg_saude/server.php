<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('soap.wsdl_cache_enabled', '0'); 
ini_set('soap.wsdl_cache_ttl', '0'); 
ini_set('memory_limit', '-1');
ini_set("allow_url_fopen", 1);
//ini_set('max_execution_time', 300);
error_reporting(E_ALL);

$data = file_get_contents("php://input"); 

$fp = fopen("envios/mensagem_".date("Ymd_His").".txt","wb");
  fwrite($fp,$data);
  fclose($fp);
  
//include("VitalOperacao.php");
include("RGService.php");

if(isset($_GET["interno"]) && $_GET["interno"]=="true"){

    $rg = new RGService();

    $rg->processarLote(8);

}else{
    $soap = new SoapServer('RG.wsdl',array('cache_wsdl' => WSDL_CACHE_NONE));
    $soap->setClass("RGService");
    $soap->handle();    
}



?>