<?php


include("Conexao.php");

class RGOperacao extends Conexao{
	
	public function listarEntidades(){
	
        $sql="SELECT cps.num_contrato AS \"codigoEntidade\"
                      ,pck_utilidades.func_nome_pessoa(cps.pessoa_codigo) AS \"nomeEntidade\"
                      ,regexp_replace(pj.cgc,'[[:punct:]]','') AS \"cnpj\"
                      ,tps.descricao AS \"tipoPlano\"
                      ,(SELECT rtrim(xmlagg(xmlelement(e,pc.registro_ans||':'||pcf.descricao|| ';'))
                                       .extract('//text()'),
                                       ',')
                            FROM padroes_contrato pc
                               ,padroes_conforto pcf
                         WHERE pc.cto_usu_num_contrato = cps.num_contrato
                           AND pcf.codigo=pc.padrao_codigo
                             )  acomodacoes                      
                from contratos_plano_saude cps 
                    ,tipos_plano_saude tps
                    ,pessoas_juridicas pj
                where cps.congenere_pj_pessoa_codigo = 489305
                and cps.data_cancelamento is null
                and cps.tipo_ps_codigo = tps.codigo
                AND pj.pessoa_codigo=cps.pessoa_codigo
                order by 1";



        $resultado=$this->executeQuery($sql);

        return $resultado;
	}

	public function novoProtocolo(){
		$sql="SELECT NVL(MAX(protocolo),0)+1 protocolo FROM vital_webservice";
		$resultado=$this->executeQuery($sql);
		return $resultado[0]["PROTOCOLO"];

	}

	public function cadastrarEnvioBeneficiarios($dados){
		try {
			$protocolo=$this->novoProtocolo();

			$sql="INSERT INTO vital_webservice(protocolo,dados,dataHora,status,descricao,empresa) VALUES(:protocolo,:dados,:dataHora,:status,:descricao,489305)";
			$bind = array(':protocolo' => $protocolo,
										':dados' => $dados,
										':dataHora' => date("d/m/Y H:i:s"),
										':status' => 1,
										':descricao' => "Processando" );


			$resultado=$this->executeQuery($sql,$bind);
			
			return $protocolo;
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	public function listarProtocolo($protocolo){
		$sql="SELECT vw.protocolo
					,vw.status
					,DECODE(vw.status,1,'Analise',2,'Validado',3,'Erro',4,'Importado','OUTRO') descricao 
					,vw.dados 
			   FROM vital_webservice vw
			  WHERE vw.empresa = 489305
			    and vw.protocolo=:protocolo";
		$bind = array(':protocolo' => $protocolo);

		$resultado=$this->executeQuery($sql,$bind);
		return $resultado[0];
	}

	public function listarProtocolosCpt(){
		$sql="SELECT pc.codigo AS \"codigoCpt\",
		       		trim(pc.descricao) AS \"descricaoCpt\"
		    		 ,(select AGRUPAR_TEXTO(pcc.cid_gcid_codigo)
				    	  from protocolo_cpt_cid pcc
				      	where pcc.codigo = pc.codigo) \"cid\"
		 from protocolo_cpt  pc
		 where data_cancelamento is null
		 order by 1";
		$resultado=$this->executeQuery($sql);
		return $resultado;
	}


	/*
	// PROCESSOS INTERNOS AC
	*/
	public function listarProtocolosPorStatus($status){
		$sql="SELECT * 
				FROM vital_webservice vw 
			   WHERE vw.status=:status";
		$bind = array(':status' => $status);

		$resultado = $this->executeQuery($sql,$bind);
		return $resultado;
	}

	public function novoCodigoTempBeneficiario(){
		$sql="SELECT temp_beneficiarios_seq.nextval codigo FROM dual";
		$resultado=$this->executeQuery($sql);
		return $resultado[0]["CODIGO"];
	}


	public function converterData($data){
		if($data!=""){
			$aux=explode("-", $data);
			return $aux[2]."-".$aux[1]."-".$aux[0];	
		}else{
			return "";
		}
		
	}


	public function cadastrarTempBeneficiario($numContrato,$tipoUsuario,$dataVigencia,$cpfBenef,$cpfTitular,$vinculoFamiliar,$padraoCtoCodigo,$nome,$rg,$dataNascimento,$sexo,$estadoCivil,$cns,$nomeMae,$nomePai,$ctpsNumero,$profissao,$dataAdmissao,$pis,$logradouro,$bairro,$codMunicipioIbge,$estado,$cep,$dddTelefone,$telefone,$dddCelular,$foneCelular,$email,$classifRisco,$observacaoDs,$codJustifCarencia,$linkProposta,$numProtocolo,$status,$tipoCadastro,$cnpjCorretora,$nomeCorretora,$portabilidade,$registro_ans_portab,$data_solicit_portab,$cod_produto_portab,$num_protocolo_portab)
	{
		
		$codigo = $this->novoCodigoTempBeneficiario();

		$sql="INSERT INTO temp_beneficiarios(codigo,
											 num_contrato
											 ,tipo_usuario
											 ,data_vigencia
											 ,cpf_benef
											 ,cpf_titular
											 ,vinculo_familiar
											 ,padrao_cto_codigo
											 ,nome
											 ,rg
											 ,data_nascimento
											 ,sexo
											 ,estado_civil
											 ,cns
											 ,nome_mae
											 ,nome_pai
											 ,ctps_numero
											 ,profissao
											 ,data_admissao
											 ,pis
											 ,logradouro
											 ,bairro
											 ,cod_municipio_ibge
											 ,estado
											 ,cep
											 ,ddd_telefone
											 ,telefone
											 ,ddd_celular
											 ,fone_celular
											 ,email
											 ,classif_risco
											 ,observacao_ds
											 ,cod_justif_carencia
											 ,link_proposta
											 ,num_protocolo
											 ,status
											 ,tipo_cadastro
											 ,cnpj_corretora
											 ,nome_corretora
											 ,flg_portabilidade
											 ,registro_ans_portab
											 ,data_solicit_portab
											 ,cod_produto_portab
											 ,num_protocolo_portab
											 )
									 VALUES(:codigo
									 		,:numContrato
									 		,:tipoUsuario
									 		,:dataVigencia
									 		,:cpfBenef
									 		,:cpfTitular
									 		,:vinculoFamiliar
									 		,:padraoCtoCodigo
									 		,:nome
									 		,:rg
									 		,:dataNascimento
									 		,:sexo
									 		,:estadoCivil
									 		,:cns
									 		,:nomeMae
									 		,:nomePai
									 		,:ctpsNumero
									 		,:profissao
									 		,:dataAdmissao
									 		,:pis
									 		,:logradouro
									 		,:bairro
									 		,:codMunicipioIbge
									 		,:estado
									 		,:cep
									 		,:dddTelefone
									 		,:telefone
									 		,:dddCelular
									 		,:foneCelular
									 		,:email
									 		,:classifRisco
									 		,:observacaoDs
									 		,:codJustifCarencia
									 		,:linkProposta
									 		,:numProtocolo
									 		,:status
									 		,:tipoCadastro
									 		,:cnpjCorretora
									 		,:nomeCorretora
									 		,:portabilidade
									 		,:registro_ans_portab
									 		,:data_solicit_portab
									 		,:cod_produto_portab
									 		,:num_protocolo_portab)";
		
		$bind = array(':codigo'				 => $codigo,
						':numContrato'       =>$numContrato,
						':tipoUsuario'       =>$tipoUsuario,
						':dataVigencia'      =>$this->converterData($dataVigencia),
						':cpfBenef'          =>$cpfBenef,
						':cpfTitular'        =>$cpfTitular,
						':vinculoFamiliar'   =>$vinculoFamiliar,
						':padraoCtoCodigo'   =>$padraoCtoCodigo,
						':nome'              =>$nome,
						':rg'                =>$rg,
						':dataNascimento'    =>$this->converterData($dataNascimento),
						':sexo'              =>$sexo,
						':estadoCivil'       =>$estadoCivil,
						':cns'               =>$cns,
						':nomeMae'           =>$nomeMae,
						':nomePai'           =>$nomePai,
						':ctpsNumero'        =>$ctpsNumero,
						':profissao'         =>$profissao,
						':dataAdmissao'      =>$this->converterData($dataAdmissao),
						':pis'               =>$pis,
						':logradouro'        =>$logradouro,
						':bairro'            =>$bairro,
						':codMunicipioIbge'  =>$codMunicipioIbge,
						':estado'            =>$estado,
						':cep'               =>$cep,
						':dddTelefone'       =>$dddTelefone,
						':telefone'          =>$telefone,
						':dddCelular'        =>$dddCelular,
						':foneCelular'       =>$foneCelular,
						':email'             =>$email,
						':classifRisco'      =>$classifRisco,
						':observacaoDs'      =>$observacaoDs,
						':codJustifCarencia' =>$codJustifCarencia,
						':linkProposta'      =>$linkProposta,
						':numProtocolo'      =>$numProtocolo,
						':status'     	 	 =>$status,
						':tipoCadastro'		 =>$tipoCadastro,
						':cnpjCorretora'	 =>$cnpjCorretora,
						':nomeCorretora'	 =>$nomeCorretora,
						':portabilidade' 	 =>$portabilidade,
						':registro_ans_portab' 	 	=>$registro_ans_portab,
						':data_solicit_portab' 	 	=>$this->converterData($data_solicit_portab),
						':cod_produto_portab' 	 	=>$cod_produto_portab,
						':num_protocolo_portab' 	=>$num_protocolo_portab);

		// echo "<pre>";
		// print_r($bind);
		// exit;

		$this->executeQuery($sql,$bind);

		return $codigo;

	}

	public function cadastrarTempBenefProtocolos($codigoTempBenef,$codigoProtocolo,$lateralidade){

		$sql="INSERT INTO temp_benef_protocolo_cpt
				  (codigo_temp_benef, codigo_protocolo, lateralidade)
				VALUES
				  (:codigoTempBenef, :codigoProtocolo,:lateralidade)";
		$bind = array(':codigoTempBenef' => $codigoTempBenef,
						':codigoProtocolo' => $codigoProtocolo,
						':lateralidade' => $lateralidade);

		$resultado=$this->executeQuery($sql,$bind);
		return $resultado;
	}

	public function cadastarTempBenefDoencasOmitidas($codigoTempBenef,$codigoProtocolo,$descricaoDoenca){
		$sql="INSERT INTO temp_benef_doencas_omitidas
				  (codigo_temp_benef, codigo_protocolo, descricao_doenca)
				VALUES
				  (:codigoTempBenef, :codigoProtocolo,:descricaoDoenca)";
		$bind = array(':codigoTempBenef' => $codigoTempBenef,
						':codigoProtocolo' => $codigoProtocolo,
						':descricaoDoenca' => $descricaoDoenca);

		$resultado=$this->executeQuery($sql,$bind);
		return $resultado;	
	}

	public function validarDadosProtocolo($protocolo,$tipo=1){

		$sql=" begin prc_temp_beneficiarios('".$protocolo."','".$tipo."'); end;";

		$bind = array(':protocolo' => $protocolo,
					 ':tipo' => $tipo );
		$resultado=$this->executeQuery($sql,$bind);
		return $resultado;
	}

	public function verificarLogProtocolo($protocolo){
		$sql="SELECT count(1) total
				 FROM log_temp_beneficiarios ltb,
				      temp_beneficiarios tb
				 WHERE tb.codigo=ltb.codigo_temp_benef
				   and  exists (select 1
				                  from contratos_plano_saude cps
				                 where cps.num_contrato = tb.num_contrato
				                   and cps.congenere_pj_pessoa_codigo = 489305)
				   AND tb.num_protocolo=:protocolo";

		$bind = array(':protocolo' => $protocolo );
		$resultado=$this->executeQuery($sql,$bind);
		return $resultado[0]["TOTAL"];
	}

	public function consultarBeneficiariosProtocolo($protocolo)
	{
		
		$sql="SELECT  tb.codigo
			           ,tb.cpf_benef
			           ,tb.status
			           ,DECODE(tb.status,1,'Analise',2,'Validado',3,'Erro',4,'Importado',5,'Aprovado','OUTRO')||'-'||(SELECT rtrim(xmlagg(xmlelement(e, ltb.erro||': '||ltb.campo_erro || ';'))
			                                       .extract('//text()'),
			                                       ',')
			                            FROM log_temp_beneficiarios ltb
			                           WHERE ltb.codigo_temp_benef=tb.codigo
			                             )  erros
					  ,c.codigo_usuario_cartao
				      ,c.num_cadastro||'/'||c.seq_usuario num_cadastro
				      ,c.nome_completo
				      ,c.data_nascimento
				      ,c.data_vigencia
				      ,c.plano
				      ,c.padrao
				      ,c.validade
				      ,c.nome_empresa
				      ,c.cns
				      ,c.nome_completo trilha_magnetica_1
				      ,c.cod_magnetico trilha_magnetica_2
				      ,c.carencia_01
				      ,c.disc_carencia_01
				      ,c.carencia_02
				      ,c.disc_carencia_02
				      ,c.carencia_03
				      ,c.disc_carencia_03
				      ,c.carencia_04
				      ,c.disc_carencia_04
				      ,c.carencia_05
				      ,c.disc_carencia_05
				      -- ,c.carencia_01
				      -- ,c.disc_carencia_01
				      ,c.carencia_06
				      ,c.disc_carencia_06
				      ,c.carencia_07
				      ,c.disc_carencia_07
				      ,c.carencia_08
				      ,c.disc_carencia_08
				      ,c.carencia_09
				      ,c.disc_carencia_09
				      ,c.carencia_10
				      ,c.disc_carencia_10
				      ,c.carencia_11
				      ,c.disc_carencia_11
				      ,c.carencia_12
				      ,c.disc_carencia_12
				      ,c.carencia_13
				      ,c.disc_carencia_13
				      ,c.msg_pre_exist
				      ,c.logo_odonto
				      ,c.num_cartao_odonto
				      ,(SELECT AGRUPAR_TEXTO(a.prot_codigo)
					        FROM protocolo_cpt_aditivo a
					       WHERE a.pessoa_codigo = c.pessoa_codigo
					         AND a.num_contrato = c.num_contrato
					         AND a.seq_familia = c.seq_familia
					         AND a.data_vigencia = c.data_vigencia
					         	--rownum=1
					         	) cpt
				from temp_beneficiarios tb
				     ,CARTAO c				     
				where tb.codigo  = c.cod_temp_benef(+)
				 and  exists (select 1
				                  from contratos_plano_saude cps
				                 where cps.num_contrato = tb.num_contrato
				                   and cps.congenere_pj_pessoa_codigo = 489305)
				  AND dept_geracao(+)='WEBSERVICE'
				AND tb.num_protocolo=:protocolo";

		$bind = array(':protocolo' => $protocolo );
		$resultado=$this->executeQuery($sql,$bind);
		return $resultado;
	}

	public function totalBeneficiariosProtocolo($protocolo){
		$sql="SELECT count(1) total
			    FROM temp_beneficiarios tb
			   WHERE  exists (select 1
				                  from contratos_plano_saude cps
				                 where cps.num_contrato = tb.num_contrato
				                   and cps.congenere_pj_pessoa_codigo = 489305)
			     and tb.num_protocolo=:protocolo";
		$bind = array(':protocolo' => $protocolo);
		$resultado=$this->executeQuery($sql,$bind);
		return $resultado;
	}


	public function cadastrarTransferenciasBeneficiario($protocolo,$codigoBeneficiario,$numeroContrato,$vigencia){
		$sql="INSERT INTO temp_benef_transferencias(num_protocolo,codigo_temp_benef,num_contrato,data_vigencia) 
							 VALUES(:protocolo,:codigoBeneficiario,:numeroContrato,:vigencia)";
		$bind = array(':protocolo' => $protocolo,
					  ':codigoBeneficiario' => $codigoBeneficiario,
					  ':numeroContrato' => $numeroContrato,
					  ':vigencia' => $this->converterData($vigencia));


		$resultado=$this->executeQuery($sql,$bind);

	
		return $resultado;
	}
	public function cadastrarLinkPropostaAdesao($codigoBeneficiario,$linkProposta)
	{
		$sql="INSERT INTO temp_benef_link_proposta(cod_temp_benef,link_proposta) 
			  VALUES(:codigoBeneficiario,:linkProposta)";

		$bind = array(':codigoBeneficiario' => $codigoBeneficiario,
					  ':linkProposta' => $linkProposta);

		$resultado=$this->executeQuery($sql,$bind);

		return $resultado;
	}

}

?>