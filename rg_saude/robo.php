<?php

	$arquivos[]=array('formulario' 	=> '321321',
					  'url'			=>'http://www.devmedia.com.br/imagens/portal2010/logo-devmedia.png' );


	$arquivos[]=array('formulario' 	=> '222222',
					  'url'			=>'http://www.devmedia.com.br/imagens/portal2010/logo-devmedia.png' );


	$retorno = array('arquivos' =>  $arquivos);

	echo json_encode($retorno,true);

?>