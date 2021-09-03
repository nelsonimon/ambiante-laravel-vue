<?php

include("RGOperacao.php");
class RGService {

	//public $carteirinha;
	protected $operacao;

	public function __construct(){
		$this->operacao= new RGOperacao();
	}

	public function getToken($params){

		if($params->usuario=='RG' && $params->senha=='RGA8#1346Z.X2')
		{
			$data["token"]=strtoupper(md5('RG'.date('d/m/Y').'AUSTA'));  
		}else{
			$data["erro"]='USUARIO E/OU SENHA INVALIDOS;';
		}
		//$data["token"]='A1B2C3D4';
		return $data;
	}

	public function validarToken($token){
		if($token==strtoupper(md5('RG'.date('d/m/Y').'AUSTA'))){
			return true;
		}else{
			return false;
		}
	}

	public function listarEntidades($params){

		if($this->validarToken($params->token)){
			$resultado=$this->operacao->listarEntidades();

			$i=0;



			foreach ($resultado as $entidade) {

				$acomodacoes=substr($entidade["ACOMODACOES"],0,-1);
				$aux=explode(";", $acomodacoes);

				$acomodacoes=array();

				foreach ($aux as $acomo) {
					$aux2=explode(":", $acomo);
					$acomodacoes["acomodacao"][]=array("registroAns"=>$aux2[0]
						,"descricao"=>$aux2[1]);
				}

				$resultado[$i]["acomodacoes"]=$acomodacoes;
				$i++;
			}

            // $data["erro"]=json_encode($resultado);
            // return

			$data["entidades"]=$resultado;
		}else{
			$data["erro"]='TOKEN INVALIDO;';
		}

		return $data;
	}

	public function listarProtocolosCpt($params){
		if($this->validarToken($params->token)){
			$resultado=$this->operacao->listarProtocolosCpt();

			$data["cpts"]=$resultado;
		}else{
			$data["erro"]='TOKEN INVALIDO;';
		}

		return $data;
	}

	public function enviarClientes($params){
		// echo 'oi';
		// exit;

		if($this->validarToken($params->token)){
			try {
				$dados=json_encode($params);

				// echo "<pre>";
				// print_r($dados);
				// exit;

				$resultado=$this->operacao->cadastrarEnvioBeneficiarios($dados);

				$this->processarLote($resultado);

				$totalBeneficiario = $this->operacao->totalBeneficiariosProtocolo($resultado);

				// echo "<pre>";
				// print_r($totalBeneficiario);
				// exit;

				if($totalBeneficiario[0]["TOTAL"]==0){
					throw new Exception("Erro ao cadastrar beneficiarios. Verifique tipos e limites informados.");	            	
					//throw new Exception($totalBeneficiario);
					//print_r($totalBeneficiario);
					//exit;

				}

				$data["protocolo"]=$resultado;

			} catch (\Exception $e) {
				$data["erro"]=$e->getMessage();
				return $data;
			}

		}else{
			$data["erro"]='TOKEN INVALIDO;';
		}

		return $data;
	}

	public function consultarProtocolos($params){

		if($this->validarToken($params->token)){
            //$dados=json_encode($params);
            //$resultado=$this->operacao->cadastrarEnvioBeneficiarios($dados);
			$pro=json_decode(json_encode($params->protocolos),true);

			$erro=array();
      //       $data["erro"]=json_encode($pro);
    		// return $data;

			if(!is_array($pro["protocolo"])){
				$aux=$pro["protocolo"];
				unset($pro["protocolo"]);

				$pro["protocolo"][]=(int)$aux;

			}
			$i=0;
			foreach ($pro["protocolo"] as $protocolo) {

				$resultado=$this->operacao->listarProtocolo($protocolo);

				if($resultado["PROTOCOLO"]==""){
					$erro[] ="Protocolo: ".$protocolo." não encontrado";
				}

				$prot[$i]=array("numeroProtocolo"=>(int)$protocolo,
					"status"=>$resultado["STATUS"],
					"observacao"=>$resultado["DESCRICAO"]);


				$erros = $this->operacao->verificarLogProtocolo($protocolo);

				// $data["erro"]=json_encode($erros);
    			//   			return $data;


				$beneficiario=array();

				//se existe erros
				$ben=array();
				$cpts=array();
				if((int)$erros>=0){
					$beneficiario=$this->operacao->consultarBeneficiariosProtocolo($protocolo);

					// $data["erro"]=json_encode($beneficiario);
    	// 			return $data;



					foreach ($beneficiario as $benef ) 
					{



						if($benef["STATUS"]==5)
						{
							$carteirinha=array(
								"codigo_usuario_cartao"=>$benef["CODIGO_USUARIO_CARTAO"],
								"num_cadastro"=>$benef["NUM_CADASTRO"],
								"nome_completo"=>$benef["NOME_COMPLETO"],
								"data_nascimento"=>$benef["DATA_NASCIMENTO"],
								"data_vigencia"=>$benef["DATA_VIGENCIA"],
								"plano"=>$benef["PLANO"],
								"padrao"=>$benef["PADRAO"],
								"validade"=>$benef["VALIDADE"],
								"nome_empresa"=>$benef["NOME_EMPRESA"],
								"cns"=>$benef["CNS"],
								"trilha_magnetica_1"=>$benef["TRILHA_MAGNETICA_1"],
								"trilha_magnetica_2"=>$benef["TRILHA_MAGNETICA_2"],
								"carencia_01"=>$benef["CARENCIA_01"],
								"disc_carencia_01"=>$benef["DISC_CARENCIA_01"],
								"carencia_02"=>$benef["CARENCIA_02"],
								"disc_carencia_02"=>$benef["DISC_CARENCIA_02"],
								"carencia_03"=>$benef["CARENCIA_03"],
								"disc_carencia_03"=>$benef["DISC_CARENCIA_03"],
								"carencia_04"=>$benef["CARENCIA_04"],
								"disc_carencia_04"=>$benef["DISC_CARENCIA_04"],
								"carencia_05"=>$benef["CARENCIA_05"],
								"disc_carencia_05"=>$benef["DISC_CARENCIA_05"],
								"carencia_01"=>$benef["CARENCIA_01"],
								"disc_carencia_01"=>$benef["DISC_CARENCIA_01"],
								"carencia_06"=>$benef["CARENCIA_06"],
								"disc_carencia_06"=>$benef["DISC_CARENCIA_06"],
								"carencia_07"=>$benef["CARENCIA_07"],
								"disc_carencia_07"=>$benef["DISC_CARENCIA_07"],
								"carencia_08"=>$benef["CARENCIA_08"],
								"disc_carencia_08"=>$benef["DISC_CARENCIA_08"],
								"carencia_09"=>$benef["CARENCIA_09"],
								"disc_carencia_09"=>$benef["DISC_CARENCIA_09"],
								"carencia_10"=>$benef["CARENCIA_10"],
								"disc_carencia_10"=>$benef["DISC_CARENCIA_10"],
								"carencia_11"=>$benef["CARENCIA_11"],
								"disc_carencia_11"=>$benef["DISC_CARENCIA_11"],
								"carencia_12"=>$benef["CARENCIA_12"],
								"disc_carencia_12"=>$benef["DISC_CARENCIA_12"],
								"carencia_13"=>$benef["CARENCIA_13"],
								"disc_carencia_13"=>$benef["DISC_CARENCIA_13"],
								"msg_pre_exist"=>$benef["MSG_PRE_EXIST"],
								"logo_odonto"=>$benef["LOGO_ODONTO"],
								"num_cartao_odonto"=>$benef["NUM_CARTAO_ODONTO"]
							);	

							if($benef['CPT'] != "")
							{
								$aux = explode(';', $benef['CPT']);

								foreach($aux as $cpt)
								{
									if($cpt)
									{
										$cpts[]=$cpt;
									}

								}
							}



							


				// $data["erro"]=json_encode($carteirinha);
    // 			  			return $data;

							$ben[]=array("cpfBeneficiario"=>$benef["CPF_BENEF"],
								"status"	=>$benef["STATUS"],
								"numeroCartao"=>$benef["CARTEIRINHA"],
								"observacao" => $benef["ERROS"],
								"dadosCartao"=>$carteirinha,
								"cptsAplicadas" => $cpts
							);



						}else{
							$ben[]=array("cpfBeneficiario"=>$benef["CPF_BENEF"],
								"status"	=>$benef["STATUS"],
									//"numeroCartao"=>$benef["CARTEIRINHA"],
								"observacao" => $benef["ERROS"],
							);
						}

						
					}

					$prot[$i]["beneficiarios"]["beneficiario"]=$ben;
				}
				//$prot[]["beneficiarios"][]

				$i++;

			}

            // $data["protocolos"]=array("protocolo"=>array("numeroProtocolo"=>1,
            //                                             "status"=>1,
            //                                             "observacao"=>"Processando"));

			if(count($erro)>0){
				$data["erro"]=implode(";", $erro);
			}
			$data["protocolos"]=$prot;
           // $data["protocolos"]=$pro["protocolo"];

		}else{
			$data["erro"]='TOKEN INVALIDO;';
		}

		return $data;
	}



	public function processarLote($protocolo){
		try {
			$prot=$this->operacao->listarProtocolo($protocolo);

			$dados=$prot["DADOS"];
			$dados=json_decode($dados,true);


			$numProtocolo=$prot["PROTOCOLO"];


			if(!isset($dados["beneficiarios"]["beneficiario"][0])){
				//echo 'aqui';
				$aux=$dados["beneficiarios"]["beneficiario"];
    			
    			unset($dados["beneficiarios"]["beneficiario"]);

				$dados["beneficiarios"]["beneficiario"][]=$aux;    		
			}
    		

   //  		foreach ($dados["beneficiarios"]["beneficiario"] as $benef) {
			// 	print_r($benef["linksPropostaAdesao"]['linkPropostaAdesao']);

			// 	//print_r($benef);
			// }

			// exit;

							
			
			foreach ($dados["beneficiarios"]["beneficiario"] as $benef) {

				$cadastro=$benef["dadosCadastrais"];
				$declaracaoSaude=$benef["declaracaoSaude"];
				$transferenciasContratos=$benef["transferenciasContratos"];
				$linksPropostas=$benef["linksPropostaAdesao"];

				if(count($linksPropostas['linkPropostaAdesao']) > 1)
				{
					$link=trim($linksPropostas['linkPropostaAdesao'][0]);
				}
				else
				{
					$link=trim($linksPropostas['linkPropostaAdesao']);
				}



				$codigoTempBenef=$this->operacao->cadastrarTempBeneficiario($benef["codigoEntidade"]
																			,$cadastro["tipoBeneficiario"]
																			,$cadastro["dataVigencia"]
																			,$cadastro["cpfBeneficiario"]
																			,$cadastro["cpfTitular"]
																			,$cadastro["relacaoDependencia"]
																			,$cadastro["padraoConforto"]
																			,$cadastro["nomeBeneficiario"]
																			,$cadastro["rg"]
																			,$cadastro["dataNascimento"]
																			,$cadastro["sexo"]
																			,$cadastro["estadoCivil"]
																			,$cadastro["cartaoNacional"]
																			,$cadastro["nomeMae"]
																			,isset($cadastro["nomePai"])?$cadastro["nomePai"]:""
																			,isset($cadastro["carteiraProfissional"])?$cadastro["carteiraProfissional"]:""
																			,isset($cadastro["profissao"])?$cadastro["profissao"]:""
																			,isset($cadastro["dataAdmissao"])?$cadastro["dataAdmissao"]:""
																			,isset($cadastro["pis"])?$cadastro["pis"]:""
																			,$cadastro["enderecoCompleto"]
																			,$cadastro["bairro"]
																			,$cadastro["codigoMunicipioIbge"]
																			,$cadastro["estado"]
																			,$cadastro["cep"]
																			,isset($cadastro["dddTelefoneResidencial"])?$cadastro["dddTelefoneResidencial"]:""
																			,isset($cadastro["telefoneResidencial"])?$cadastro["telefoneResidencial"]:""
																			,isset($cadastro["dddCelular"])?$cadastro["dddCelular"]:""
																			,isset($cadastro["telefoneCelular"])?$cadastro["telefoneCelular"]:""
																			,isset($cadastro["email"])?$cadastro["email"]:""
																			,$declaracaoSaude["classificaoRisco"]
																			,isset($declaracaoSaude["observacao"])?$declaracaoSaude["observacao"]:""
																			,isset($benef["codigoCarencia"])?$benef["codigoCarencia"]:""
																			,isset($link)?trim($link):""
																			,$numProtocolo
																			,1
																			,$benef["tipoCadastro"]
																			,$benef["dadosCorretora"]["cnpjCorretora"]
																			,$benef["dadosCorretora"]["nomeCorretora"]
																			,isset($benef["portabilidade"])?$benef["portabilidade"]:""
																			,isset($benef["registroAnsPortab"])?$benef["registroAnsPortab"]:""
																			,isset($benef["dataSolicitPortab"])?$benef["dataSolicitPortab"]:""
																			,isset($benef["codProdutoPortab"])?$benef["codProdutoPortab"]:""
																			,isset($benef["numProtocoloPortab"])?$benef["numProtocoloPortab"]:"");


				//cadastra os protocolos
				if(isset($declaracaoSaude["protocolosCpt"]) ){

					if(!isset($declaracaoSaude["protocolosCpt"]["protocoloCpt"][0])){
						//echo 'aqui';
						$aux=$declaracaoSaude["protocolosCpt"]["protocoloCpt"];

		    			// print_r($aux);
		    			//unset($dados["beneficiarios"]["beneficiario"]);

						$declaracaoSaude["protocolosCpt"]["protocoloCpt"][]=$aux;    			
					}


					foreach ($declaracaoSaude["protocolosCpt"]["protocoloCpt"] as $protocoloCpt) {

						if(isset($protocoloCpt["codigoProtocolo"])){ //se não for nulo
							$this->operacao->cadastrarTempBenefProtocolos($codigoTempBenef
								,$protocoloCpt["codigoProtocolo"]
								,isset($protocoloCpt["lateralidade"])?$protocoloCpt["lateralidade"]:'');	
						}
					}
				}

				//cadastra os protocolos
				if(isset($declaracaoSaude["doencasOmitidas"]) && count($declaracaoSaude["doencasOmitidas"])>0){
				//echo "aqui";
					foreach ($declaracaoSaude["doencasOmitidas"] as $doenca) {
				//		echo "foi";
				//		echo "<pre>";
				//		print_r($doenca);
						if(isset($doenca["codigoProtocolo"])){ //se não for nulo
				//			echo "ahaa";
							$this->operacao->cadastarTempBenefDoencasOmitidas($codigoTempBenef
								,$doenca["codigoProtocolo"]
								,isset($doenca["descricaoDoenca"])?$doenca["descricaoDoenca"]:'');	
						}
					}
				}

				//throw new Exception(json_encode($transferenciasContratos), 1);

				if(isset($transferenciasContratos["contrato"]) && count($transferenciasContratos["contrato"])>0)
				{

					if(!isset($transferenciasContratos["contrato"][0])){
						//echo 'aqui';
						$aux=$transferenciasContratos["contrato"];

		    			// print_r($aux);
		    			//unset($dados["beneficiarios"]["beneficiario"]);

						$transferenciasContratos["contrato"][]=$aux;    			
					}


					//echo "aqui";
					foreach ($transferenciasContratos["contrato"] as $contrato) {
					//		echo "foi";
					//		echo "<pre>";
					//		print_r($doenca);

						if(isset($contrato["numeroContrato"]))
						{ //se não for nulo
							//echo "ahaa";
							$this->operacao->cadastrarTransferenciasBeneficiario($numProtocolo
								,$codigoTempBenef
								,$contrato["numeroContrato"]
								,$contrato["vigenciaContrato"]);
							// throw new Exception(json_encode($contrato), 1);
						}
					}
				}




			

				if(isset($linksPropostas["linkPropostaAdesao"]) && count($linksPropostas["linkPropostaAdesao"])>0)
				{	

					// print_r($link);
					// exit;
							
					
					if(count($linksPropostas['linkPropostaAdesao']) > 1)
					{
						

						//echo "aqui";
						foreach ($linksPropostas['linkPropostaAdesao'] as $key=> $linkProposta) 
						{	
							
							if(isset($linkProposta))
							{ 
								$this->operacao->cadastrarLinkPropostaAdesao($codigoTempBenef
									,$linkProposta);
								
							}
						}
					} 
					else
					{
						if(isset($linksPropostas['linkPropostaAdesao']))
							{ 
								$this->operacao->cadastrarLinkPropostaAdesao($codigoTempBenef
									,trim($linksPropostas['linkPropostaAdesao']));
								
							}
					}

					
				}
				



				//print_r($benef);
				//echo $codigoTempBenef;
			}

			$this->operacao->validarDadosProtocolo((int)$numProtocolo,1);


		} catch (\Exception $e) {
			//return $e->getMessage();
			throw new \Exception($e->getMessage());
			
		}
		

		//exit;




	}

}
?>