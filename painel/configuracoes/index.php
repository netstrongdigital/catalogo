<?php
// =========================
// CORE E INCLUDES
// =========================
include('../../_core/_includes/config.php');
session_start();
restrict_estabelecimento();

// =========================
// SEO E HEADER
// =========================
$seo_subtitle = "Configurações";
$seo_description = "";
$seo_keywords = "";
$system_header .= "";
include('../_layout/head.php');
include('../_layout/top.php');
include('../_layout/sidebars.php');
include('../_layout/modal.php');
global $simple_url;

// =========================
// CONSULTA DE DADOS DO ESTABELECIMENTO E USUÁRIO
// =========================
global $numeric_data;
$id = $_SESSION['estabelecimento']['id'];
$queryestabelecimento = mysqli_query($db_con, "SELECT * FROM estabelecimentos WHERE id = '$id' LIMIT 1");
$hasdataestabelecimento = mysqli_num_rows($queryestabelecimento);
$dataestabelecimento = mysqli_fetch_array($queryestabelecimento);
$uid = $dataestabelecimento['rel_users_id'];
$queryusuario = mysqli_query($db_con, "SELECT * FROM users WHERE id = '$uid' LIMIT 1");
$hasdatausuario = mysqli_num_rows($queryusuario);
$datausuario = mysqli_fetch_array($queryusuario);
$delivery = data_info("estabelecimentos", $id, "nomedelivery");
$retirada = data_info("estabelecimentos", $id, "nomeretirada");
$mesa = data_info("estabelecimentos", $id, "nomemesa");

// =========================
// PROCESSAMENTO DO FORMULÁRIO
// =========================
$formdata = $_POST['formdata'];
if ($formdata) {

    // Setar campos

    // Dados gerais

    $nome = mysqli_real_escape_string( $db_con, $_POST['nome'] );
    $descricao = mysqli_real_escape_string( $db_con, $_POST['descricao'] );
    $segmento = mysqli_real_escape_string( $db_con, $_POST['segmento'] );
    $estado = mysqli_real_escape_string( $db_con, $_POST['estado'] );
    $cidade = mysqli_real_escape_string( $db_con, $_POST['cidade'] );
    $subdominio = subdomain( mysqli_real_escape_string( $db_con, $_POST['subdominio'] ) );

    // Aparência

    $cor = mysqli_real_escape_string( $db_con, $_POST['cor'] );
    $exibicao = mysqli_real_escape_string( $db_con, $_POST['exibicao'] );

    // Pagamento

    $pedido_minimo = dinheiro( mysqli_real_escape_string( $db_con, $_POST['pedido_minimo'] ) );
    if( !$pedido_minimo ) {
      $pedido_minimo = "0.00";
    }
    $pagamento_dinheiro = mysqli_real_escape_string( $db_con, $_POST['pagamento_dinheiro'] );

    // DEBITO

    $pagamento_cartao_debito = mysqli_real_escape_string( $db_con, $_POST['pagamento_cartao_debito'] );
    $pagamento_cartao_debito_bandeiras = mysqli_real_escape_string( $db_con, $_POST['pagamento_cartao_debito_bandeiras'] );
    if( $pagamento_cartao_debito == "2" ) {
      $pagamento_cartao_debito_bandeiras = "";
    }

    // CREDITO

    $pagamento_cartao_credito = mysqli_real_escape_string( $db_con, $_POST['pagamento_cartao_credito'] );
    $pagamento_cartao_credito_bandeiras = mysqli_real_escape_string( $db_con, $_POST['pagamento_cartao_credito_bandeiras'] );
    if( $pagamento_cartao_credito == "2" ) {
      $pagamento_cartao_credito_bandeiras = "";
    }

    //Gateways de Pagamentos

    $pagamento_mercadopago = mysqli_real_escape_string( $db_con, $_POST['pagamento_mercadopago'] );
    $pagamento_mercadopago_sandbox = mysqli_real_escape_string( $db_con, $_POST['pagamento_mercadopago_sandbox'] );
    $pagamento_mercadopago_public = mysqli_real_escape_string( $db_con, $_POST['pagamento_mercadopago_public'] );
    $pagamento_mercadopago_secret = mysqli_real_escape_string( $db_con, $_POST['pagamento_mercadopago_secret'] );

    $pagamento_pagseguro = mysqli_real_escape_string( $db_con, $_POST['pagamento_pagseguro'] );
    $pagamento_pagseguro_sandbox = mysqli_real_escape_string( $db_con, $_POST['pagamento_pagseguro_sandbox'] );
    $pagamento_pagseguro_email = mysqli_real_escape_string( $db_con, $_POST['pagamento_pagseguro_email'] );
    $pagamento_pagseguro_token = mysqli_real_escape_string( $db_con, $_POST['pagamento_pagseguro_token'] );

    $pagamento_getnet = mysqli_real_escape_string( $db_con, $_POST['pagamento_getnet'] );
    $pagamento_getnet_sandbox = mysqli_real_escape_string( $db_con, $_POST['pagamento_getnet_sandbox'] );
    $pagamento_getnet_client_id	 = mysqli_real_escape_string( $db_con, $_POST['pagamento_getnet_client_id'] );
    $pagamento_getnet_client_secret = mysqli_real_escape_string( $db_con, $_POST['pagamento_getnet_client_secret'] );
    $pagamento_getnet_seller_id	= mysqli_real_escape_string( $db_con, $_POST['pagamento_getnet_seller_id'] );

    // PIX

    $pagamento_pix = mysqli_real_escape_string( $db_con, $_POST['pagamento_pix'] );
    $pagamento_pix_chave = dinheiro( mysqli_real_escape_string( $db_con, $_POST['pagamento_pix_chave'] ) );
    if( !$pagamento_pix_chave ) {
      $pagamento_pix_chave = "";
    }
    $pagamento_pix_beneficiario = dinheiro( mysqli_real_escape_string( $db_con, $_POST['pagamento_pix_beneficiario'] ) );
    if( !$pagamento_pix_beneficiario ) {
      $pagamento_pix_beneficiario = "";
    }

    // Entrega

    $endereco_cep = mysqli_real_escape_string( $db_con, $_POST['endereco_cep'] );
    $endereco_numero = mysqli_real_escape_string( $db_con, $_POST['endereco_numero'] );
    $endereco_bairro = mysqli_real_escape_string( $db_con, $_POST['endereco_bairro'] );
    $endereco_rua = mysqli_real_escape_string( $db_con, $_POST['endereco_rua'] );
    $endereco_complemento = mysqli_real_escape_string( $db_con, $_POST['endereco_complemento'] );
    $endereco_referencia = mysqli_real_escape_string( $db_con, $_POST['endereco_referencia'] );

    $horario_funcionamento = mysqli_real_escape_string( $db_con, $_POST['horario_funcionamento'] );

    $entrega_retirada = mysqli_real_escape_string( $db_con, $_POST['entrega_retirada'] );
    $entrega_entrega = mysqli_real_escape_string( $db_con, $_POST['entrega_entrega'] );
    $entrega_entrega_tipo = mysqli_real_escape_string( $db_con, $_POST['entrega_entrega_tipo'] );

    $entrega_entrega_valor = dinheiro( mysqli_real_escape_string( $db_con, $_POST['entrega_entrega_valor'] ) );
    if( !$entrega_entrega_valor ) {
      $entrega_entrega_valor = "0.00";
    }

    $nomedelivery = mysqli_real_escape_string( $db_con, $_POST['nomedelivery'] );
    $nomeretirada = mysqli_real_escape_string( $db_con, $_POST['nomeretirada'] );
    $nomemesa = mysqli_real_escape_string( $db_con, $_POST['nomemesa'] );
    $entrega_delivery = mysqli_real_escape_string( $db_con, $_POST['entrega_delivery'] );
    $entrega_balcao = mysqli_real_escape_string( $db_con, $_POST['entrega_balcao'] );
    $entrega_mesa = mysqli_real_escape_string( $db_con, $_POST['entrega_mesa'] );
    $entrega_outros = mysqli_real_escape_string( $db_con, $_POST['entrega_outros'] );
    $entrega_outros_nome = mysqli_real_escape_string( $db_con, $_POST['entrega_nome_outros'] );
    $calcular_frete = mysqli_real_escape_string( $db_con, $_POST['calcular_frete'] );
    // Transportadoras
    $correios_pac = isset($_POST['correios_pac']) ? mysqli_real_escape_string($db_con, $_POST['correios_pac']) : "2";
    $correios_sedex = isset($_POST['correios_sedex']) ? mysqli_real_escape_string($db_con, $_POST['correios_sedex']) : "2";
    $correios_minienvios = isset($_POST['correios_minienvios']) ? mysqli_real_escape_string($db_con, $_POST['correios_minienvios']) : "2";
    $loggi_express = isset($_POST['loggi_express']) ? mysqli_real_escape_string($db_con, $_POST['loggi_express']) : "2";
    $loggi_ponto = isset($_POST['loggi_ponto']) ? mysqli_real_escape_string($db_con, $_POST['loggi_ponto']) : "2";
    $loggi_coleta = isset($_POST['loggi_coleta']) ? mysqli_real_escape_string($db_con, $_POST['loggi_coleta']) : "2";
    $jadlog_package = isset($_POST['jadlog_package']) ? mysqli_real_escape_string($db_con, $_POST['jadlog_package']) : "2";
    $jadlog_com = isset($_POST['jadlog_com']) ? mysqli_real_escape_string($db_con, $_POST['jadlog_com']) : "2";
    $jadlog_centralizado = isset($_POST['jadlog_centralizado']) ? mysqli_real_escape_string($db_con, $_POST['jadlog_centralizado']) : "2";
    $jet_standard = isset($_POST['jet_standard']) ? mysqli_real_escape_string($db_con, $_POST['jet_standard']) : "2";
    $azulcargo_ecommerce = isset($_POST['azulcargo_ecommerce']) ? mysqli_real_escape_string($db_con, $_POST['azulcargo_ecommerce']) : "2";
    $azulcargo_expresso = isset($_POST['azulcargo_expresso']) ? mysqli_real_escape_string($db_con, $_POST['azulcargo_expresso']) : "2";
    $latam_efacil = isset($_POST['latam_efacil']) ? mysqli_real_escape_string($db_con, $_POST['latam_efacil']) : "2";
    $buslog_rodoviario = isset($_POST['buslog_rodoviario']) ? mysqli_real_escape_string($db_con, $_POST['buslog_rodoviario']) : "2";

    // Contato

    $contato_whatsapp = mysqli_real_escape_string( $db_con, $_POST['contato_whatsapp'] );
    $contato_email = mysqli_real_escape_string( $db_con, $_POST['contato_email'] );
    $contato_instagram = mysqli_real_escape_string( $db_con, $_POST['contato_instagram'] );
    $contato_facebook = mysqli_real_escape_string( $db_con, $_POST['contato_facebook'] );
    $contato_youtube = mysqli_real_escape_string( $db_con, $_POST['contato_youtube'] );
    $html = mysqli_real_escape_string( $db_con, $_POST['html'] );

    // Estatisticas

    $estatisticas_analytics = mysqli_real_escape_string( $db_con, $_POST['estatisticas_analytics'] );
    $estatisticas_pixel = mysqli_real_escape_string( $db_con, $_POST['estatisticas_pixel'] );

    // Responsável

    $responsavel_nome = mysqli_real_escape_string( $db_con, $_POST['responsavel_nome'] );
    $responsavel_nascimento = mysqli_real_escape_string( $db_con, $_POST['responsavel_nascimento'] );
    $responsavel_documento_tipo = mysqli_real_escape_string( $db_con, $_POST['responsavel_documento_tipo'] );
    $responsavel_documento = clean_str( mysqli_real_escape_string( $db_con, $_POST['responsavel_documento'] ) );

    // Acesso

    $email = mysqli_real_escape_string( $db_con, $_POST['email'] );
    $pass = mysqli_real_escape_string( $db_con, $_POST['pass'] );
    $repass = mysqli_real_escape_string( $db_con, $_POST['repass'] );

    // Adm

    $status_force = data_info("estabelecimentos",$id,"status_force");
    $excluded = data_info("estabelecimentos",$id,"excluded");

    // Checar Erros

    $checkerrors = 0;
    $errormessage = array();

    // Geral

    // -- Nome

    if( !$nome ) {
      $checkerrors++;
      $errormessage[] = "O nome não pode ser nulo";
    }

    // -- Descrição

    if( !$descricao ) {
      $checkerrors++;
      $errormessage[] = "A descrição não pode ser nula";
    }

    // -- Segmento

    $data_exists = "";
    $results = "";
    $results = mysqli_query( $db_con, "SELECT * FROM segmentos WHERE id = '$segmento'");
    $data_exists = mysqli_num_rows($results);
    if( !$data_exists ) {
      $checkerrors++;
      $errormessage[] = "O Segmento não é valido.";
    }

    // -- Estado

    $data_exists = "";
    $results = "";
    $results = mysqli_query( $db_con, "SELECT * FROM estados WHERE id = '$estado'");
    $data_exists = mysqli_num_rows($results);
    if( !$data_exists ) {
      $checkerrors++;
      $errormessage[] = "Selecione um estado.";
    }

    // -- Cidade

    $data_exists = "";
    $results = "";
    $results = mysqli_query( $db_con, "SELECT * FROM cidades WHERE id = '$cidade'");
    $data_exists = mysqli_num_rows($results);
    if( !$data_exists ) {
      $checkerrors++;
      $errormessage[] = "Selecione uma cidade.";
    }

    // -- Subdominio

    if( data_info( "estabelecimentos", $dataestabelecimento['id'], "subdominio" ) != $subdominio ) {
      $data_exists = "";
      $results = "";
      $has_subdominio = 0;

      // Subdominios

      $subdominios = mysqli_query($db_con,"SELECT * FROM subdominios WHERE subdominio = '$subdominio'");
      $has_subdominios = mysqli_num_rows($subdominios);
      if( $has_subdominios ) {
        $has_subdominio++;
      }

      // Cidades

      $cidades = mysqli_query($db_con,"SELECT * FROM cidades WHERE subdominio = '$subdominio'");
      $has_cidades = mysqli_num_rows($cidades);
      if( $has_cidades ) {
        $has_subdominio++;
      }

      // Estabelecimentos

      $estabelecimentos = mysqli_query($db_con,"SELECT * FROM estabelecimentos WHERE subdominio = '$subdominio'");
      $has_estabelecimentos = mysqli_num_rows($estabelecimentos);
      if( $has_estabelecimentos ) {
        $has_subdominio++;
      }

      if( $has_subdominio ) {
        $checkerrors++;
        $errormessage[] = "O subdominio não é valido ou já está registrado.";
      }
    }

    // Aparência

    // Perfil

    if( $_FILES['perfil']['name'] ) {
      $upload = upload_image( $id, $_FILES['perfil'] );
      $_SESSION['upsts'] = $upload['status'];
      if ( $upload['status'] == "1" ) {
        $perfil = $upload['url'];
        $_SESSION['estabelecimento']['perfil'] = $upload['url'];
      } else {
        $checkerrors++;
        for( $x=0; $x < count( $upload['errors'] ); $x++ ) {
          $_SESSION['uperrors'] = $upload['errors'][$x];
          $errormessage[] = $upload['errors'][$x];
        }
      }
    }

    // Capa

    if( $_FILES['capa']['name'] ) {
      $upload = upload_image( $id, $_FILES['capa'] );
      if ( $upload['status'] == "1" ) {
        $capa = $upload['url'];
      } else {
        $checkerrors++;
        for( $x=0; $x < count( $upload['errors'] ); $x++ ) {
          $errormessage[] = $upload['errors'][$x];
        }
      }
    }

    // -- Cor

    if( !$cor ) {
      $checkerrors++;
      $errormessage[] = "A cor não pode ser nula";
    }

    // Pagamento

    if( $pagamento_pix == "1" ) {
      if( !$pagamento_pix_chave ) {
        $checkerrors++;
        $errormessage[] = "Você deve uma chave pix.";
      }
    }

    if( $pagamento_pix == "1" ) {
      if( !$pagamento_pix_beneficiario ) {
        $checkerrors++;
        $errormessage[] = "Você deve informar nome e sobrenome da conta pix.";
      }
    }

    if( $pagamento_cartao_debito == "1" ) {
      if( !$pagamento_cartao_debito_bandeiras ) {
        $checkerrors++;
        $errormessage[] = "Você deve especificar as bandeiras de cartões de débito aceitas.";
      }
    }

    if( $pagamento_cartao_credito == "1" ) {
      if( !$pagamento_cartao_credito_bandeiras ) {
        $checkerrors++;
        $errormessage[] = "Você deve especificar as bandeiras de cartões de crédito aceitas.";
      }
    }

    if( $pagamento_cartao_alimentacao == "1" ) {
      if( !$pagamento_cartao_alimentacao_bandeiras ) {
        $checkerrors++;
        $errormessage[] = "Você deve especificar as bandeiras do ticket alimentação aceitas.";
      }
    }

    if( $pagamento_outros == "1" ) {
      if( !$pagamento_outros_descricao ) {
        $checkerrors++;
        $errormessage[] = "Você deve especificar as outras formas de pagamento aceitas.";
      }
    }

    if( $pagamento_dinheiro == "2" && $pagamento_cartao == "2" && $pagamento_outros == "2" ) {
      $checkerrors++;
      $errormessage[] = "Você deve permitir ao menos uma forma de pagamento";
    }

    // Entrega

    // -- Endereço completo

    if( !$endereco_rua ) {
      $checkerrors++;
      $errormessage[] = "Você deve preencher o endereço";
    }

    if( $entrega_retirada == "2" && $entrega_entrega == "2" ) {
      $checkerrors++;
      $errormessage[] = "Você deve fazer ou entrega ou retirada.";
    }

    if( $entrega_entrega == "1" ) {
      if( !$entrega_entrega_valor ) {
        $checkerrors++;
        $errormessage[] = "O valor de entrega não pode ser nulo";
      }
    }

    // Acesso

    // -- Responsavel

    if( !$responsavel_nome ) {
      $checkerrors++;
      $errormessage[] = "O nome do responsável não pode ser nulo";
    }

    if( !$responsavel_nascimento ) {
      $checkerrors++;
      $errormessage[] = "A data de nascimento do responsável não pode ser nula";
    }

    if( !$responsavel_documento_tipo ) {
      $checkerrors++;
      $errormessage[] = "O tipo de documento do responsável não pode ser nulo";
    }

    if( !$responsavel_documento ) {
      $checkerrors++;
      $errormessage[] = "O documento do responsável não pode ser nulo";
    }

    // -- E-mail

    if( data_info( "users", $uid, "email" ) != $email ) {
      $data_exists = "";
      $results = "";
      $results = mysqli_query( $db_con, "SELECT * FROM users WHERE email = '$email'");
      $data_exists = mysqli_num_rows($results);
      if( $data_exists ) {
        $checkerrors++;
        $errormessage[] = "O e-mail já está registrado no sistema, por favor tente outro ou faça login!";
      }
    }

    // -- Senhas

    if( $pass != $repass ) {
      $checkerrors++;
      $errormessage[] = "As senhas não coincidem.";
    }

    // Executar registro

    if( !$checkerrors ) {

        function edit_estabelecimento_nome( 

        $nomedelivery,
		$nomeretirada,
        $nomemesa,
        $id,
        $nome,
        $descricao,
        $segmento,
        $estado,
        $cidade,
        $subdominio,
        $perfil,
        $capa,
        $cor,
		$exibicao,
        $pedido_minimo,
        $pagamento_dinheiro,
        $pagamento_cartao_debito,
        $pagamento_cartao_debito_bandeiras,
        $pagamento_cartao_credito,
        $pagamento_cartao_credito_bandeiras,
        $pagamento_mercadopago,
		$pagamento_mercadopago_sandbox,
        $pagamento_mercadopago_public,
        $pagamento_mercadopago_secret,
        $pagamento_pagseguro,
		$pagamento_pagseguro_sandbox,
        $pagamento_pagseguro_email,
        $pagamento_pagseguro_token,
        $pagamento_getnet,
		$pagamento_getnet_sandbox,
		$pagamento_getnet_client_id,
		$pagamento_getnet_client_secret,
		$pagamento_getnet_seller_id,
        $pagamento_pix,
        $pagamento_pix_chave,
		$pagamento_pix_beneficiario,
        $endereco_cep,
        $endereco_numero,
        $endereco_bairro,
        $endereco_rua,
        $endereco_complemento,
        $endereco_referencia,
        $horario_funcionamento,
        $entrega_retirada,
        $entrega_entrega,
        $entrega_entrega_tipo,
        $entrega_entrega_valor,
        $entrega_delivery,
		$entrega_balcao,
		$entrega_mesa,
		$entrega_outros,
		$entrega_outros_nome,
        $contato_whatsapp,
        $contato_email,
        $contato_instagram,
        $contato_facebook,
        $contato_youtube,
        $estatisticas_analytics,
        $estatisticas_pixel,
        $html,
        $responsavel_nome,
        $responsavel_nascimento,
        $responsavel_documento_tipo,
        $responsavel_documento,
        $email,
        $pass,
        $status_force,
        $excluded,
        $calcular_frete,
        $correios_pac,
        $correios_sedex,
        $correios_minienvios,
        $loggi_express,
        $loggi_ponto,
        $loggi_coleta,
        $jadlog_package,
        $jadlog_com,
        $jadlog_centralizado,
        $jet_standard,
        $azulcargo_ecommerce,
        $azulcargo_expresso,
        $latam_efacil,
        $buslog_rodoviario

	 ) {

		global $db_con;
		global $simple_url;
		global $rootpath;

		$datetime = date('Y-m-d H:i:s');

		$updatedquery = "UPDATE estabelecimentos SET 

		    nomedelivery = '$nomedelivery',
		    nomeretirada = '$nomeretirada',
            nomemesa = '$nomemesa',
			nome = '$nome', 
			descricao = '$descricao', 
			segmento = '$segmento', 
			estado = '$estado', 
			cidade = '$cidade', 
			subdominio = '$subdominio', 
			cor = '$cor', 
			exibicao = '$exibicao', 
			pedido_minimo = '$pedido_minimo', 
			pagamento_dinheiro = '$pagamento_dinheiro', 
			pagamento_cartao_debito = '$pagamento_cartao_debito', 
			pagamento_cartao_debito_bandeiras = '$pagamento_cartao_debito_bandeiras', 
			pagamento_cartao_credito = '$pagamento_cartao_credito', 
			pagamento_cartao_credito_bandeiras = '$pagamento_cartao_credito_bandeiras', 		
			pagamento_mercadopago = '$pagamento_mercadopago',
			pagamento_mercadopago_sandbox = '$pagamento_mercadopago_sandbox',
            pagamento_mercadopago_public = '$pagamento_mercadopago_public',
            pagamento_mercadopago_secret = '$pagamento_mercadopago_secret',
            pagamento_pagseguro = '$pagamento_pagseguro',
			pagamento_pagseguro_sandbox = '$pagamento_pagseguro_sandbox',
            pagamento_pagseguro_email = '$pagamento_pagseguro_email',
            pagamento_pagseguro_token = '$pagamento_pagseguro_token',
            pagamento_getnet = '$pagamento_getnet',
            pagamento_getnet_sandbox = '$pagamento_getnet_sandbox',
            pagamento_getnet_client_id = '$pagamento_getnet_client_id',
            pagamento_getnet_client_secret = '$pagamento_getnet_client_secret',
			pagamento_getnet_seller_id = '$pagamento_getnet_seller_id',
			pagamento_pix = '$pagamento_pix', 
			chave_pix = '$pagamento_pix_chave', 
			beneficiario_pix = '$pagamento_pix_beneficiario', 
			endereco_cep = '$endereco_cep', 
			endereco_numero = '$endereco_numero', 
			endereco_bairro = '$endereco_bairro', 
			endereco_rua = '$endereco_rua', 
			endereco_complemento = '$endereco_complemento', 
			endereco_referencia = '$endereco_referencia', 
			horario_funcionamento = '$horario_funcionamento', 
			entrega_retirada = '$entrega_retirada', 
			entrega_entrega = '$entrega_entrega', 
			entrega_entrega_tipo = '$entrega_entrega_tipo', 
			entrega_entrega_valor = '$entrega_entrega_valor', 
			delivery = '$entrega_delivery', 
			balcao = '$entrega_balcao', 
			mesa = '$entrega_mesa', 
			outros = '$entrega_outros', 
			nomeoutros = '$entrega_outros_nome', 
			contato_whatsapp = '$contato_whatsapp', 
			contato_email = '$contato_email', 
			contato_instagram = '$contato_instagram', 
			contato_facebook = '$contato_facebook', 
			contato_youtube = '$contato_youtube',
			estatisticas_analytics = '$estatisticas_analytics', 
			estatisticas_pixel = '$estatisticas_pixel', 
			html = '$html', 
			responsavel_nome = '$responsavel_nome', 
			responsavel_nascimento = '$responsavel_nascimento', 
			responsavel_documento_tipo = '$responsavel_documento_tipo', 
			responsavel_documento = '$responsavel_documento', 
			email = '$email',
			last_modified = '$datetime',
			status_force = '$status_force',
			excluded = '$excluded' ,
      calcular_frete = '$calcular_frete',
      correios_pac = '$correios_pac',
      correios_sedex = '$correios_sedex',
      correios_minienvios = '$correios_minienvios',
      loggi_express = '$loggi_express',
      loggi_ponto = '$loggi_ponto',
      loggi_coleta = '$loggi_coleta',
      jadlog_package = '$jadlog_package',
      jadlog_com = '$jadlog_com',
      jadlog_centralizado = '$jadlog_centralizado',
      jet_standard = '$jet_standard',
      azulcargo_ecommerce = '$azulcargo_ecommerce',
      azulcargo_expresso = '$azulcargo_expresso',
      latam_efacil = '$latam_efacil',
      buslog_rodoviario = '$buslog_rodoviario'

		WHERE id = '$id'";

		if( mysqli_query( $db_con, $updatedquery ) ) {

			$uid = data_info("estabelecimentos",$id,"rel_users_id");

			$updatedquery = "UPDATE users SET email = '$email' WHERE id = '$uid'";
			mysqli_query( $db_con, $updatedquery );

			if( $pass ) {
				$password = md5($pass);
				$updatedquery = "UPDATE users SET password = '$password' WHERE id = '$uid'";
				mysqli_query( $db_con, $updatedquery );
			}

			if( $perfil ) {
				$perfilantigo = data_info("estabelecimentos",$id,"perfil");
				if( $perfilantigo ) {
					unlink( $rootpath."/_core/_uploads/".$perfilantigo );
				}
				$updatedquery = "UPDATE estabelecimentos SET perfil = '$perfil' WHERE id = '$id'";
				mysqli_query( $db_con, $updatedquery );
			}

			if( $capa ) {
				$capaantiga = data_info("estabelecimentos",$id,"capa");
				if( $capaantiga ) {
					unlink( $rootpath."/_core/_uploads/".$capaantiga );
				}
				$updatedquery = "UPDATE estabelecimentos SET capa = '$capa' WHERE id = '$id'";
				mysqli_query( $db_con, $updatedquery );
			}

			$subdominio_antigo = data_info("subdominios",$sid,"subdominio");

			if( $subdominio != $subdominio_antigo ) {
				$updatedquery = "UPDATE subdominios SET subdominio = '$subdominio' WHERE rel_id = '$id'";
				mysqli_query( $db_con, $updatedquery );
			}

			// SALVA LOG

				$log_uid = $_SESSION['user']['id'];
				$log_nome = $_SESSION['user']['nome'];
				$log_lid = "";

				// Tipos

				if( $_SESSION['user']['level'] == "1" ) {
					$log_user_tipo = "O Administrador";
				}

				if( $_SESSION['user']['level'] == "2" ) {
					$log_user_tipo = "A Loja";
				}

				if( $_SESSION['user']['level'] == "3" ) {
					$log_user_tipo = "Afiliado";
				}

				log_register( $log_uid,$log_lid, $log_user_tipo." ".$log_nome." editou o estabelecimento ".$subdominio.".".$simple_url." às ".databr( date('Y-m-d H:i:s') ) );

			// / SALVA LOG

			return true;

		} else {

			return false;

		}

	}

    if( edit_estabelecimento_nome( 

          $nomedelivery,
          $nomeretirada,
          $nomemesa,
          $id,
          $nome,
          $descricao,
          $segmento,
          $estado,
          $cidade,
          $subdominio,
          $perfil,
          $capa,
          $cor,
          $exibicao,
          $pedido_minimo,
          $pagamento_dinheiro,
          $pagamento_cartao_debito,
          $pagamento_cartao_debito_bandeiras,
          $pagamento_cartao_credito,
          $pagamento_cartao_credito_bandeiras,
          $pagamento_mercadopago,
          $pagamento_mercadopago_sandbox,
          $pagamento_mercadopago_public,
          $pagamento_mercadopago_secret,
          $pagamento_pagseguro,
          $pagamento_pagseguro_sandbox,
          $pagamento_pagseguro_email,
          $pagamento_pagseguro_token,
          $pagamento_getnet,
          $pagamento_getnet_sandbox,
          $pagamento_getnet_client_id,
          $pagamento_getnet_client_secret,
          $pagamento_getnet_seller_id,
          $pagamento_pix,
          $pagamento_pix_chave,
          $pagamento_pix_beneficiario,
          $endereco_cep,
          $endereco_numero,
          $endereco_bairro,
          $endereco_rua,
          $endereco_complemento,
          $endereco_referencia,
          $horario_funcionamento,
          $entrega_retirada,
          $entrega_entrega,
          $entrega_entrega_tipo,
          $entrega_entrega_valor,
          $entrega_delivery,
          $entrega_balcao,
          $entrega_mesa,
          $entrega_outros,
          $entrega_outros_nome,
          $contato_whatsapp,
          $contato_email,
          $contato_instagram,
          $contato_facebook,
          $contato_youtube,
          $estatisticas_analytics,
          $estatisticas_pixel,
          $html,
          $responsavel_nome,
          $responsavel_nascimento,
          $responsavel_documento_tipo,
          $responsavel_documento,
          $email,
          $pass,
          $status_force,
          $excluded,
          $calcular_frete,
          $correios_pac,
          $correios_sedex,
          $correios_minienvios,
          $loggi_express,
          $loggi_ponto,
          $loggi_coleta,
          $jadlog_package,
          $jadlog_com,
          $jadlog_centralizado,
          $jet_standard,
          $azulcargo_ecommerce,
          $azulcargo_expresso,
          $latam_efacil,
          $buslog_rodoviario

     ) ) {

      $_SESSION['estabelecimento']['nome'] = $nome;
      header("Location: index.php?msg=sucesso&id=".$id."&errormessage=".urlencode(implode(', ', $errormessage)));
      // echo "Cadastrou";
    } else {
      header("Location: index.php?msg=erro&id=".$id."&errormessage=".urlencode(implode(', ', $errormessage)));
      // echo "Não cadastrou";
    }
  }
}
?>
<?php if ($_GET['msg'] == "complete") { modal_alerta("Criado com sucesso.<br/><br/>Agora finalize seu cadastro com as informações do seu estabelecimento.", "sucesso"); } ?>
<div class="middle minfit bg-gray">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="title-icon pull-left">
          <i class="lni lni-cog"></i>
          <span>Configurações</span>
        </div>
        <div class="bread-box pull-right">
          <div class="bread">
            <a href="<?php panel_url(); ?>"><i class="lni lni-home"></i></a>
            <span>/</span>
            <a href="<?php panel_url(); ?>/configuracoes/">Configurações</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Content -->
    <div class="data box-white mt-16">
      <?php if ($hasdataestabelecimento) { ?>
      <form id="the_form" class="form-wizard" method="POST" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-12">
            <?php if ($checkerrors) { list_errors(); } ?>
            <?php if ($_GET['msg'] == "erro") { modal_alerta("Erro, tente novamente!", "erro"); } ?>
            <?php if ($_GET['msg'] == "sucesso") { modal_alerta("Dados alterados com sucesso!", "sucesso"); } ?>
          </div>
        </div>
        <div id="wizard-estabelecimento">
          <h3>Geral</h3>
          <section>
            <!-- Dados Gerais -->
            <div class="row">
              <div class="col-md-12">
                <div class="title-line mt-0 pd-0">
                  <i class="lni lni-question-circle"></i>
                  <span>Dados gerais</span>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>Nome:</label>
                    <input type="text" name="nome" placeholder="Nome do seu estabelecimento" value="<?php echo htmlclean( $dataestabelecimento['nome'] ); ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>Descrição:</label>
                    <textarea rows="6" name="descricao" placeholder="Descrição do seu estabelecimento"><?php echo htmlclean( $dataestabelecimento['descricao'] ); ?></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-field-default">
                    <label>Segmento:</label>
                    <div class="fake-select">
                      <i class="lni lni-chevron-down"></i>
                      <select id="input-segmento" name="segmento">
                          <option value="">Segmento</option>
                          <?php 
                          $quicksql = mysqli_query( $db_con, "SELECT * FROM segmentos ORDER BY nome ASC LIMIT 999" );
                          while( $quickdata = mysqli_fetch_array( $quicksql ) ) {
                          ?>
                            <option <?php if( $dataestabelecimento['segmento'] == $quickdata['id'] ) { echo "SELECTED"; }; ?> value="<?php echo $quickdata['id']; ?>"><?php echo $quickdata['nome']; ?></option>
                          <?php } ?>
                      </select>
                      <div class="clear"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-field-default">
                    <label>Estado:</label>
                    <div class="fake-select">
                      <i class="lni lni-chevron-down"></i>
                      <select id="input-estado" name="estado">
                          <option value="">Estado</option>
                          <?php 
                          $quicksql = mysqli_query( $db_con, "SELECT * FROM estados ORDER BY nome ASC LIMIT 999" );
                          while( $quickdata = mysqli_fetch_array( $quicksql ) ) {
                          ?>
                            <option <?php if( $dataestabelecimento['estado'] == $quickdata['id'] ) { echo "SELECTED"; }; ?> value="<?php echo $quickdata['id']; ?>"><?php echo $quickdata['nome']; ?></option>
                          <?php } ?>
                      </select>
                      <div class="clear"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-field-default">
                    <label>Cidade:</label>
                    <div class="fake-select">
                      <i class="lni lni-chevron-down"></i>
                      <select id="input-cidade" name="cidade">
                        <option value="">Cidade</option>
                      </select>
                      <div class="clear"></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>URL:</label>
                    <span class="form-tip">A URL que seus clientes usarão para acessar a estabelecimento, não serão permitidos acentos, cedilha, pontos e caracteres especiais.</span>
                    <div class="row lowpadd">
                      <div class="col-md-3 col-xs-6 col-sm-6">
                        <input class="subdomain" type="text" name="subdominio" placeholder="estabelecimento" value="<?php echo subdomain( htmlclean( $dataestabelecimento['subdominio'] ) ); ?>">
                      </div>
                      <div class="col-md-9 col-xs-6 col-sm-6">
                        <input type="text" id="input-nome" name="url" value=".<?php echo $simple_url; ?>" DISABLED>
                      </div>
                    </div>
                </div>
              </div>
            </div>
          <!-- / Dados Gerais -->
          </section>
          <h3>Aparência</h3>
          <section>
          <!-- Aparência -->
            <div class="row">
              <div class="col-md-12">
                <div class="title-line pd-0">
                  <i class="lni lni-construction-hammer"></i>
                  <span>Aparência</span>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
              <label>Foto de perfil:</label>
                <div class="file-preview">
                  <div class="image-preview" id="image-preview" style='background: url("<?php echo thumber( $dataestabelecimento['perfil'], 200 ); ?>") no-repeat center center; background-size: auto 102%;'>
                    <label for="image-upload" id="image-label">Clique ou arraste</label>
                    <input type="file" name="perfil" id="image-upload"/>
                  </div>
                  <span class="explain">Selecione sua foto de perfil clicando no campo ou arrastando o arquivo!</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
              <label>Capa:</label>
                <div class="file-preview">
                  <div class="image-preview image-preview-cover" id="image-preview2" style='background: url("<?php echo imager( $dataestabelecimento['capa'] ); ?>") no-repeat center center; background-size: auto 102%;'>
                    <label for="image-upload2" id="image-label">Clique ou arraste</label>
                    <input type="file" name="capa" id="image-upload2"/>
                  </div>
                  <span class="explain">Selecione sua capa! Tamanho recomendado: 1000x400px</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-field-default">
                    <label>Cor personalizada:</label>
                    <input class="thecolorpicker" type="text" name="cor" placeholder="Cor" value="<?php echo htmlclean( $dataestabelecimento['cor'] ); if( !$dataestabelecimento['cor'] ){ echo '#27293e'; } ?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-field-default">
                    <label>Exibição de Produtos:</label>
                    <div class="fake-select">
                    <select name="exibicao">
                      <option value="<?php echo htmlclean( $dataestabelecimento['exibicao'] );?>">
                      <?php
                      if($dataestabelecimento['exibicao'] == 1) {echo "Grade (Catálogo)";}
                      if($dataestabelecimento['exibicao'] == 2) {echo "Lista (Cardápio)";}
                      ?>
                      </option>
                      <option value="1">Grade (Catálogo)</option>
                      <option value="2">Lista (Cardápio)</option>
                    </select>
                    </div>
                </div>
              </div>
            </div>
          <!-- / Aparência -->
          </section>
          <h3>Pagamento</h3>
          <section>
          <!-- Informações de pagamento -->
            <div class="row">
              <div class="col-md-12">
                <div class="title-line mt-0 pd-0">
                  <i class="lni lni-coin"></i>
                  <span>Pagamento</span>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>Qual valor de pedido minímo?:</label>
                    <input class="maskmoney" type="text" name="pedido_minimo" placeholder="Valor de pedido minímo" value="<?php echo htmlclean( dinheiro( $dataestabelecimento['pedido_minimo'], "BR") ); ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>O estabelecimento aceita dinheiro?</label>
                    <div class="form-field-radio">
                      <input type="radio" name="pagamento_dinheiro" value="1" <?php if( $dataestabelecimento['pagamento_dinheiro'] == 1 OR !$dataestabelecimento['pagamento_dinheiro'] ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="pagamento_dinheiro" value="2" <?php if( $dataestabelecimento['pagamento_dinheiro'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                    <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>O estabelecimento envia maquininha?</label>
                    <div class="form-field-radio">
                      <input type="radio" name="pagamento_cartao_debito" value="1" element-show=".elemento-bandeiras-debito" <?php if( $dataestabelecimento['pagamento_cartao_debito'] == 1 OR !$dataestabelecimento['pagamento_cartao_debito'] ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="pagamento_cartao_debito" value="2" element-hide=".elemento-bandeiras-debito" <?php if( $dataestabelecimento['pagamento_cartao_debito'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                    <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="row elemento-bandeiras-debito <?php if( $dataestabelecimento['pagamento_cartao_debito'] == "2" ){ echo 'elemento-oculto'; }; ?>">
              <div class="col-md-12">
                <div class="form-field-default">
                    <?php
                    if( $dataestabelecimento['pagamento_cartao_debito_bandeiras'] ) {
                      $field_pagamento_debito_bandeiras = $dataestabelecimento['pagamento_cartao_debito_bandeiras'];
                    } else {
                      $field_pagamento_debito_bandeiras = "Visa, Mastercard e Elo";
                    }
                    ?>
                    <label>Quais bandeiras de cartão de débito aceitas?:</label>
                    <input type="text" name="pagamento_cartao_debito_bandeiras" placeholder="Visa, Mastercard e Elo" value="<?php echo htmlclean( $field_pagamento_debito_bandeiras ); ?>">
                </div>
              </div>
            </div>
            <div class="row" style="margin-left:0px;margin-right:0px">
              <div class="col-md-12">
                <div class="title-line mt-0 pd-0">
                <i class="lni lni-coin"></i>
                  <span>Gateways de Pagamento Online</span>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>O estabelecimento aceita PIX?</label>
                    <div class="form-field-radio">
                      <input type="radio" name="pagamento_pix" element-show=".elemento-pagamento-pix" value="1" <?php if( $dataestabelecimento['pagamento_pix'] == 1 OR !$dataestabelecimento['pagamento_pix'] ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="pagamento_pix" element-hide=".elemento-pagamento-pix" value="2" <?php if( $dataestabelecimento['pagamento_pix'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                    <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="row elemento-pagamento-pix <?php if( $dataestabelecimento['pagamento_pix'] == "2" ){ echo 'elemento-oculto'; }; ?>">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>Chave PIX:</label>
					<span class="form-tip">CHAVE ALEATÓRIA - Copiar e Colar no campo abaixo</span>
					<span class="form-tip">CPF/CNPJ - Informar apenas os números</span>
					<span class="form-tip">E-MAIL - Informar email cadastrado como pix</span>
					<span class="form-tip">CELULAR - Ex: DDDNUMERO onde 45988080666</span>
                    <input type="text" name="pagamento_pix_chave" placeholder="Gere uma chave aleatória em seu aplicativo e cole aqui." value="<?php echo htmlclean( $dataestabelecimento['chave_pix'] ); ?>">
                </div>
				<div class="form-field-default">
                    <label>Tipo de Chave:</label>
                    <div class="fake-select">
                    <select name="pagamento_pix_beneficiario">
                          <option value="<?php echo htmlclean( $dataestabelecimento['beneficiario_pix'] );?>"><?php echo htmlclean( $dataestabelecimento['beneficiario_pix'] );?></option>
                          <option value="CPF">CPF</option>
                          <option value="CNPJ">CNPJ</option>
                          <option value="Celular">Celular</option>
                          <option value="E-mail">E-mail</option>
                          <option value="Chave Aleatóroria">Chave Aleatóroria</option>
                    </select>
                    </div>
                </div>
              </div>
            </div>
            <!--Mercado Pago INICIO-->
            <div class="row" style="margin-left:0px;margin-right:0px">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>O estabelecimento aceita pagamento via Mercado Pago?</label>
                    <div class="form-field-radio">
                      <input type="radio" name="pagamento_mercadopago" value="1" element-show=".elemento-mercadopago" <?php if( $dataestabelecimento['pagamento_mercadopago'] == 1 ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="pagamento_mercadopago" value="2" element-hide=".elemento-mercadopago" <?php if( $dataestabelecimento['pagamento_mercadopago'] == 2 OR !$dataestabelecimento['pagamento_mercadopago'] ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                    <div class="clear"></div>
                </div>
              </div>
            </div>
            <!--Mercado Pago Inputs INICIO-->
           <div style="margin-left:0px;margin-right:0px" class="row elemento-mercadopago <?php if( $dataestabelecimento['pagamento_mercadopago'] == "2" ){ echo 'elemento-oculto'; }; ?>">
                      <div class="col-md-12">
                        <div class="form-field-default">  
                           <label>Modo Teste (Sandbox):</label>
                            <select  name="pagamento_mercadopago_sandbox">
                                  <option <?php if ($dataestabelecimento['pagamento_mercadopago_sandbox'] == 1) { echo "selected";}?> value="1" >Sim</option>
                                  <option <?php if ($dataestabelecimento['pagamento_mercadopago_sandbox'] == 0) { echo "selected";}?> value="0" >Não</option>
                            </select>
                            <label>Public key:</label>
                            <input type="text" name="pagamento_mercadopago_public" value="<?php echo htmlclean($dataestabelecimento['pagamento_mercadopago_public'] ); ?>">
                            <label>Acess Token:</label>
                            <input type="text" name="pagamento_mercadopago_secret"  value="<?php echo htmlclean($dataestabelecimento['pagamento_mercadopago_secret'] ); ?>">
                        </div>
                  </div>
            </div>
            <!--Mercado Pago Inputs FIM-->
            <!--Mercado Pago FIM-->
            
            <!-- INÍCIO PagSeguro (comentado para futura implementação) -->
            <?php /*
            <div class="row" style="margin-left:0px;margin-right:0px">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>O estabelecimento aceita pagamento via Pagseguro?</label>
                    <div class="form-field-radio">
                      <input type="radio" name="pagamento_pagseguro" value="1" element-show=".elemento-pagseguro" <?php if( $dataestabelecimento['pagamento_pagseguro'] == 1 ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="pagamento_pagseguro" value="2" element-hide=".elemento-pagseguro" <?php if( $dataestabelecimento['pagamento_pagseguro'] == 2 OR !$dataestabelecimento['pagamento_pagseguro'] ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                    <div class="clear"></div>
                </div>
              </div>
            </div>
            <div style="margin-left:0px;margin-right:0px" class="row elemento-pagseguro <?php if( $dataestabelecimento['pagamento_pagseguro'] == "2" ){ echo 'elemento-oculto'; }; ?>">
              <div class="col-md-12">
                <div class="form-field-default">  
                    <label>Modo Teste (Sandbox):</label>
                    <select  name="pagamento_pagseguro_sandbox">
                          <option <?php if ($dataestabelecimento['pagamento_pagseguro_sandbox'] == 1) { echo "selected";}?> value="1" >Sim</option>
                          <option <?php if ($dataestabelecimento['pagamento_pagseguro_sandbox'] == 0) { echo "selected";}?> value="0" >Não</option>
                    </select>
                    <label>Email Pagseguro:</label>
                    <input type="text" name="pagamento_pagseguro_email" value="<?php echo htmlclean($dataestabelecimento['pagamento_pagseguro_email'] ); ?>">
                    <label>Token Pagseguro:</label>
                    <input type="text" name="pagamento_pagseguro_token"  value="<?php echo htmlclean($dataestabelecimento['pagamento_pagseguro_token'] ); ?>">
                </div>
              </div>
            </div>
            */?>
            <!-- FIM PagSeguro -->
            
            <!-- INÍCIO Getnet (comentado para futura implementação) -->
            <?php /*
            <div class="row" style="margin-left:0px;margin-right:0px">
            <div class="col-md-12">
              <div class="form-field-default">
                  <label>O estabelecimento aceita pagamento via Getnet?</label>
                  <div class="form-field-radio">
                    <input type="radio" name="pagamento_getnet" value="1" element-show=".elemento-getnet" <?php if( $dataestabelecimento['pagamento_getnet'] == 1 ){ echo 'CHECKED'; }; ?>> Sim
                  </div>
                  <div class="form-field-radio">
                    <input type="radio" name="pagamento_getnet" value="2" element-hide=".elemento-getnet" <?php if( $dataestabelecimento['pagamento_getnet'] == 2 OR !$dataestabelecimento['pagamento_getnet'] ){ echo 'CHECKED'; }; ?>> Não
                  </div>
                  <div class="clear"></div>
              </div>
            </div>
            </div>
              <div style="margin-left:0px;margin-right:0px" class="row elemento-getnet <?php if( $dataestabelecimento['pagamento_getnet'] == "2" ){ echo 'elemento-oculto'; }; ?>">
                  <div class="col-md-12">
                          <div class="form-field-default">  
                              <label>Modo Teste (Sandbox):</label>
                              <select  name="pagamento_getnet_sandbox">
                                    <option <?php if ($dataestabelecimento['pagamento_getnet_sandbox'] == 1) { echo "selected";}?> value="1" >Sim</option>
                                    <option <?php if ($dataestabelecimento['pagamento_getnet_sandbox'] == 0) { echo "selected";}?> value="0" >Não</option>
                              </select>
                              <label>Client ID:</label>
                              <input type="text" name="pagamento_getnet_client_id" value="<?php echo htmlclean($dataestabelecimento['pagamento_getnet_client_id'] ); ?>">
                              <label>Client Secret:</label>
                              <input type="text" name="pagamento_getnet_client_secret"  value="<?php echo htmlclean($dataestabelecimento['pagamento_getnet_client_secret'] ); ?>">
                              <label>Seller ID:</label>
                              <input type="text" name="pagamento_getnet_seller_id"  value="<?php echo htmlclean($dataestabelecimento['pagamento_getnet_seller_id'] ); ?>">
                          </div>
                  </div>
               </div>
            */?>
            <!-- FIM Getnet -->
          <!-- / Informações de pagamento -->
          </section>
          <h3>Entrega</h3>
          <section>
          <!-- Informações de entrega -->
            <div class="row">
              <div class="col-md-12">
                <div class="title-line mt-0 pd-0">
                  <i class="lni lni-pin"></i>
                  <span>Entrega</span>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="elemento-endereco">
              <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-6">
                  <div class="form-field-default">
                      <label>CEP</label>
                      <input class="maskcep" type="text" name="endereco_cep" placeholder="CEP" value="<?php echo htmlclean( $dataestabelecimento['endereco_cep'] ); ?>">
                  </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                  <div class="form-field-default">
                      <label>Nº</label>
                      <input type="text" name="endereco_numero" placeholder="Nº" value="<?php echo htmlclean( $dataestabelecimento['endereco_numero'] ); ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-field-default">
                      <label>Bairro</label>
                      <input type="text" name="endereco_bairro" placeholder="Bairro" value="<?php echo htmlclean( $dataestabelecimento['endereco_bairro'] ); ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-field-default">
                      <label>Rua</label>
                      <input type="text" name="endereco_rua" placeholder="Rua" value="<?php echo htmlclean( $dataestabelecimento['endereco_rua'] ); ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-field-default">
                      <label>Complemento</label>
                      <input type="text" name="endereco_complemento" placeholder="Complemento" value="<?php echo htmlclean( $dataestabelecimento['endereco_complemento'] ); ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-field-default">
                      <label>Ponto de referência</label>
                      <input type="text" name="endereco_referencia" placeholder="Complemento" value="<?php echo htmlclean( $dataestabelecimento['endereco_referencia'] ); ?>">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>Horário de funcionamento</label>
                    <textarea rows="7" name="horario_funcionamento" placeholder="Horário de funcionamento"><?php echo htmlclean( $dataestabelecimento['horario_funcionamento'] ); ?></textarea>
                </div>
              </div>
            </div>
            <!-- Linha separadora antes das opções de entrega -->
            <div class="row">
              <div class="col-md-12">
                <div class="title-line mt-0 pd-0"></div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>O estabelecimento faz Delivery?</label>
                    <div class="form-field-radio">
                      <input type="radio" name="entrega_delivery" value="1" element-show=".elemento-nomedelivery" <?php if( $dataestabelecimento['delivery'] == 1 OR !$dataestabelecimento['delivery'] ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="entrega_delivery" value="2" element-hide=".elemento-nomedelivery" <?php if( $dataestabelecimento['delivery'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                    <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="row elemento-nomedelivery <?php if( $dataestabelecimento['delivery'] == "2" ){ echo 'elemento-oculto'; }; ?>">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>Nome da Opção de Delivery:</label>
                    <input type="text" name="nomedelivery" value="<?php echo $delivery;?>">
                </div>
              </div>
            </div>
            <!-- Linha separadora após Opção de Delivery -->
            <div class="row">
              <div class="col-md-12">
                <div class="title-line mt-0 pd-0"></div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>O estabelecimento permite entrega no Balcão?</label>
                    <div class="form-field-radio">
                      <input type="radio" name="entrega_balcao" value="1" element-show=".elemento-nomeretirada" <?php if( $dataestabelecimento['balcao'] == 1 OR !$dataestabelecimento['balcao'] ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="entrega_balcao" value="2" element-hide=".elemento-nomeretirada" <?php if( $dataestabelecimento['balcao'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                    <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="row elemento-nomeretirada <?php if( $dataestabelecimento['balcao'] == "2" ){ echo 'elemento-oculto'; }; ?>">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>Nome da Opção de Balcão:</label>
                    <input type="text" name="nomeretirada" value="<?php echo $retirada;?>">
                </div>
              </div>
            </div>
            <!-- Linha separadora após Opção de Balcão -->
            <div class="row">
              <div class="col-md-12">
                <div class="title-line mt-0 pd-0"></div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>Entrega na mesa?</label>
                    <div class="form-field-radio">
                      <input type="radio" name="entrega_mesa" value="1" element-show=".elemento-nomemesa" <?php if( $dataestabelecimento['mesa'] == 1 OR !$dataestabelecimento['mesa'] ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="entrega_mesa" value="2" element-hide=".elemento-nomemesa" <?php if( $dataestabelecimento['mesa'] == 2 ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                    <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="row elemento-nomemesa <?php if( $dataestabelecimento['mesa'] == "2" ){ echo 'elemento-oculto'; }; ?>">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>Nome da Opção de Mesa:</label>
                    <input type="text" name="nomemesa" value="<?php echo $mesa;?>">
                </div>
              </div>
            </div>
            <!-- Linha separadora após Opção de Mesa -->
            <div class="row">
              <div class="col-md-12">
                <div class="title-line mt-0 pd-0"></div>
              </div>
            </div>
            <!-- Habilitar cálculo de frete na tela do produto (deve ficar por último) -->
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                  <label>Habilitar calculo do frete na tela do produto?</label>
                    <div class="form-field-radio">
                      <input type="radio" name="calcular_frete" value="1" element-show=".elemento-transportadora" <?php if( $dataestabelecimento['calcular_frete'] == 1 ){ echo 'CHECKED'; }; ?>> Sim
                    </div>
                    <div class="form-field-radio">
                      <input type="radio" name="calcular_frete" value="2" element-hide=".elemento-transportadora" <?php if( $dataestabelecimento['calcular_frete'] == 2 OR !$dataestabelecimento['calcular_frete'] ){ echo 'CHECKED'; }; ?>> Não
                    </div>
                </div>
              </div>
            </div>
            <div class="row elemento-transportadora <?php if( $dataestabelecimento['calcular_frete'] == "2" || !$dataestabelecimento['calcular_frete'] ){ echo 'elemento-oculto'; } ?>">
    <div class="col-md-12"><br>
    <div class="transportadoras-grid">
      <div class="box-transportadora">
        <div class="box-title"><b>Correios</b></div>
        <div class="transportadora-opcoes">
          <label><input type="checkbox" name="correios_pac" value="1" <?php if($dataestabelecimento['correios_pac']==1){echo 'checked';} ?>> PAC</label>
          <label><input type="checkbox" name="correios_sedex" value="1" <?php if($dataestabelecimento['correios_sedex']==1){echo 'checked';} ?>> Sedex</label>
          <label><input type="checkbox" name="correios_minienvios" value="1" <?php if($dataestabelecimento['correios_minienvios']==1){echo 'checked';} ?>> Mini Envios</label>
        </div>
      </div>
      <div class="box-transportadora">
        <div class="box-title"><b>Loggi</b></div>
        <div class="transportadora-opcoes">
          <label><input type="checkbox" name="loggi_express" value="1" <?php if($dataestabelecimento['loggi_express']==1){echo 'checked';} ?>> Express</label>
          <label><input type="checkbox" name="loggi_ponto" value="1" <?php if($dataestabelecimento['loggi_ponto']==1){echo 'checked';} ?>> Ponto</label>
          <label><input type="checkbox" name="loggi_coleta" value="1" <?php if($dataestabelecimento['loggi_coleta']==1){echo 'checked';} ?>> Coleta</label>
        </div>
      </div>
      <div class="box-transportadora">
        <div class="box-title"><b>Jadlog</b></div>
        <div class="transportadora-opcoes">
          <label><input type="checkbox" name="jadlog_package" value="1" <?php if($dataestabelecimento['jadlog_package']==1){echo 'checked';} ?>> Package</label>
          <label><input type="checkbox" name="jadlog_com" value="1" <?php if($dataestabelecimento['jadlog_com']==1){echo 'checked';} ?>> .Com</label>
          <label><input type="checkbox" name="jadlog_centralizado" value="1" <?php if($dataestabelecimento['jadlog_centralizado']==1){echo 'checked';} ?>> Centralizado</label>
        </div>
      </div>
      <div class="box-transportadora">
        <div class="box-title"><b>JeT</b></div>
        <div class="transportadora-opcoes">
          <label><input type="checkbox" name="jet_standard" value="1" <?php if($dataestabelecimento['jet_standard']==1){echo 'checked';} ?>> Standard</label>
        </div>
      </div>
      <div class="box-transportadora">
        <div class="box-title"><b>Azul Cargo</b></div>
        <div class="transportadora-opcoes">
          <label><input type="checkbox" name="azulcargo_ecommerce" value="1" <?php if($dataestabelecimento['azulcargo_ecommerce']==1){echo 'checked';} ?>> E-commerce</label>
          <label><input type="checkbox" name="azulcargo_expresso" value="1" <?php if($dataestabelecimento['azulcargo_expresso']==1){echo 'checked';} ?>> Expresso</label>
        </div>
      </div>
      <div class="box-transportadora">
        <div class="box-title"><b>LATAM</b></div>
        <div class="transportadora-opcoes">
          <label><input type="checkbox" name="latam_efacil" value="1" <?php if($dataestabelecimento['latam_efacil']==1){echo 'checked';} ?>> e-Fácil</label>
        </div>
      </div>
      <div class="box-transportadora">
        <div class="box-title"><b>Buslog</b></div>
        <div class="transportadora-opcoes">
          <label><input type="checkbox" name="buslog_rodoviario" value="1" <?php if($dataestabelecimento['buslog_rodoviario']==1){echo 'checked';} ?>> Rodoviário</label>
        </div>
      </div>
    </div>
  </div>
</div>
          <!-- / Informações de entrega -->
          </section>
          <h3>Contato</h3>
          <section>
          <!-- Informações de entrega -->
            <div class="row">
              <div class="col-md-12">
                <div class="title-line mt-0 pd-0">
                  <i class="lni lni-headphone-alt"></i>
                  <span>Contato</span>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>Whatsapp</label>
                    <span class="form-tip">Será o número no qual você receberá ospedidos</span>
                    <input class="maskcel" type="text" name="contato_whatsapp" placeholder="Whatsapp" value="<?php echo htmlclean( $dataestabelecimento['contato_whatsapp'] ); ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>E-mail de contato</label>
                    <input type="text" name="contato_email" placeholder="E-mail" value="<?php echo htmlclean( $dataestabelecimento['contato_email'] ); ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>Instagram</label>
                    <input type="text" name="contato_instagram" placeholder="Instagram" value="<?php echo htmlclean( $dataestabelecimento['contato_instagram'] ); ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>Facebook</label>
                    <input type="text" name="contato_facebook" placeholder="Facebook" value="<?php echo htmlclean( $dataestabelecimento['contato_facebook'] ); ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>Link do google maps</label>
                    <span class="form-tip">Link do botão como chegar ao estabelecimento pelo mapa</span>
                    <input type="text" name="contato_youtube" placeholder="https://www.google.com.br/maps" value="<?php echo htmlclean( $dataestabelecimento['contato_youtube'] ); ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="title-line mt-0 pd-0">
                  <i class="lni lni-stats-up"></i>
                  <span>Estatísticas</span>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>ID de acompanhamento (Google analytics).</label>
                    <input type="text" name="estatisticas_analytics" placeholder="ID de acompanhamento (Google analytics)." value="<?php echo htmlclean( $dataestabelecimento['estatisticas_analytics'] ); ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>ID de acompanhamento (Facebook pixel).</label>
                    <input type="text" name="estatisticas_pixel" placeholder="ID de acompanhamento (Facebook pixel)." value="<?php echo htmlclean( $dataestabelecimento['estatisticas_pixel'] ); ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>HTML adicional:</label>
                    <textarea rows="7" name="html" placeholder="HTML adicional"><?php echo htmlclean( $dataestabelecimento['html'] ); ?></textarea>
                </div>
              </div>
            </div>
          <!-- / Informações de entrega -->
          </section>
          <h3>Usuário</h3>
          <section>
          <!-- Informações de usuario -->
            <div class="row">
              <div class="col-md-12">
                <div class="title-line mt-0 pd-0">
                  <i class="lni lni-user"></i>
                  <span>Responsável</span>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>Nome completo:</label>
                    <input type="text" id="input-nome" name="responsavel_nome" placeholder="Nome completo" value="<?php echo htmlclean( $dataestabelecimento['responsavel_nome'] ); ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                  <div class="form-field-default">
                    <label>Data de nascimento:</label>
                    <input type="text" class="maskdate" id="input-nascimento" name="responsavel_nascimento" placeholder="Data de nascimento" value="<?php echo htmlclean( $dataestabelecimento['responsavel_nascimento'] ); ?>">
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-field-default">
                    <label>Tipo de documento:</label>
                    <div class="fake-select">
                      <i class="lni lni-chevron-down"></i>
                      <select id="input-documento_tipo" name="responsavel_documento_tipo">
                        <option></option>
                        <?php for( $x = 0; $x < count( $numeric_data['documento_tipo'] ); $x++ ) { ?>
                        <option value="<?php echo $numeric_data['documento_tipo'][$x]['value']; ?>" <?php if( $dataestabelecimento['responsavel_documento_tipo'] == $numeric_data['documento_tipo'][$x]['value'] ) { echo 'SELECTED'; }; ?>><?php echo $numeric_data['documento_tipo'][$x]['name']; ?></option>
                        <?php } ?>
                      </select>
                      <div class="clear"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-field-default">
                    <label>Nº do documento:</label>
                    <input type="text" id="input-documento" name="responsavel_documento" placeholder="Nº do documento" value="<?php echo htmlclean( $dataestabelecimento['responsavel_documento'] ); ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="title-line mt-0 pd-0">
                  <i class="lni lni-user"></i>
                  <span>Login</span>
                  <div class="clear"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-field-default">
                    <label>E-mail</label>
                    <input type="text" name="email" placeholder="E-mail" value="<?php echo htmlclean( $dataestabelecimento['email'] ); ?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-field-default">
                    <label>Senha</label>
                    <input type="password" name="pass" placeholder="******">
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-6">
                <div class="form-field-default">
                    <label>Redigite</label>
                    <input type="password" name="repass" placeholder="******">
                </div>
              </div>
            </div>
            <input type="hidden" name="formdata" value="1"/>
          <!-- / Informações de usuario -->
          </section>
        </div>
      </form>
      <?php } else { ?>
        <span class="nulled nulled-edit color-red">Erro, inválido ou não encontrado!</span>
      <?php } ?>
    </div>
    <!-- / Content -->
  </div>
</div>
<?php 
// =========================
// FOOTER
// =========================
$system_footer .= "";
include('../_layout/rdp.php');
include('../_layout/footer.php');
?>
<!-- =========================
     SCRIPTS JS
========================= -->
<script>
function exibe_cidades() {
  var estado = $("#input-estado").children("option:selected").val();
  $("#input-cidade").html("<option>-- Carregando cidades --</option>");
  $("#input-cidade").load("<?php just_url(); ?>/_core/_ajax/cidades.php?estado="+estado);
}
$("#input-estado").change(function() { exibe_cidades(); });
<?php if ($_POST['estado']) { ?>exibe_cidades();<?php } ?>
</script>
<script>
$(document).ready(function() {
  var form = $("#the_form");
  form.validate({
      focusInvalid: true,
      invalidHandler: function() { alert("Existem campos obrigatóriosa serem preenchidos!"); },
      errorPlacement: function errorPlacement(error, element) {element.after(error); },
      rules:{
        nome: { required: true },
        descricao: { required: true },
        segmento: { required: true },
        estado: { required: true },
        cidade: { required: true },
        subdominio: {
            required: true,
            minlength: 2,
            maxlength: 40,
            remote: "<?php just_url(); ?>/_core/_ajax/check_subdominio_actual.php?actual=<?php echo $dataestabelecimento['subdominio']; ?>"
        },
        cor: { required: true },
        pedido_minimo: { required: true },
        endereco_rua: { required: true },
        endereco_bairro: { required: true },
        endereco_numero: { required: true },
        contato_whatsapp: { required: true },
        responsavel_nome: { required: true },
        responsavel_nascimento: { required: true },
        responsavel_documento_tipo: { required: true },
        responsavel_documento: { required: true },
        email: {
            required: true,
            minlength: 4,
            maxlength: 50,
            email: true,
            remote: "<?php just_url(); ?>/_core/_ajax/check_email_actual.php?id=<?php echo $uid; ?>"
        },
        pass: { minlength: 6, maxlength: 40 },
        repass: { minlength: 6, maxlength: 40, equalTo: "input[name=pass]" },
        terms: { required: true }
      },
      messages:{
        nome: { required: "Esse campo é obrigatório" },
        descricao: { required: "Esse campo é obrigatório" },
        segmento: { required: "Esse campo é obrigatório" },
        estado: { required: "Esse campo é obrigatório" },
        cidade: { required: "Esse campo é obrigatório" },
        subdominio: {
            required: "Esse campo é obrigatório",
            remote: "Subdominio já registrado no sistema, por favor escolha outro!",
            minlength: "Mínimo de 2 caracteres",
            maxlength: "Maximo de 40 caracteres"
        },
        pedido_minimo: { required: "Esse campo é obrigatório" },
        endereco_rua: { required: "Esse campo é obrigatório" },
        endereco_bairro: { required: "Esse campo é obrigatório" },
        endereco_numero: { required: "Esse campo é obrigatório" },
        contato_whatsapp: { required: "Esse campo é obrigatório" },
        responsavel_nome: { required: "Esse campo é obrigatório" },
        responsavel_nascimento: { required: "Esse campo é obrigatório" },
        responsavel_documento_tipo: { required: "Esse campo é obrigatório" },
        responsavel_documento: { required: "Esse campo é obrigatório" },
        email: {
            required: "Esse campo é obrigatório",
            email: "Por favor escolha um e-mail válido!",
            remote: "E-mail já registrado no sistema, por favor escolha outro!",
            minlength: "Mínimo de 4 caracteres",
            maxlength: "Maximo de 50 caracteres"
        },
        pass: { minlength: "A senha é muito curta" },
        repass: { minlength: "A senha é muito curta", equalTo: "As senhas não coincidem" },
        terms: { required: "Esse campo é obrigatório" }
      }
  });
  $("#wizard-estabelecimento").steps({
      headerTag: "h3",
      bodyTag: "section",
      enableAllSteps: true,
      showFinishButtonAlways: true,
      transitionEffect: "slideLeft",
      transitionEffectSpeed: 600,
      labels: {
        previous: "Anterior",
        next: "Próximo",
        finish: "Salvar"
      },
      onStepChanging: function (event, currentIndex, newIndex) {
          form.validate().settings.ignore = ":disabled,:hidden";
          return form.valid();
          $('#the_form').trigger("change");
      },
      onFinishing: function (event, currentIndex){
          form.validate().settings.ignore = ":disabled";
          return form.valid();
          $('#the_form').trigger("change");
      },
      onFinished: function (event, currentIndex){
          form.submit();
      }
  });
});
</script>
<script>
$(document).ready(function() {
  // Preview avatar
  $.uploadPreview({
    input_field: "#image-upload",
    preview_box: "#image-preview",
    label_field: "#image-label",
    label_default: "Envie ou arraste",
    label_selected: "Trocar imagem",
    no_label: false
  });
  // Preview capa
  $.uploadPreview({
    input_field: "#image-upload2",
    preview_box: "#image-preview2",
    label_field: "#image-label2",
    label_default: "Envie ou arraste",
    label_selected: "Trocar imagem",
    no_label: false
  });
  $(".subdomain").keyup(function(e) {
    var re = /[^a-zA-Z0-9\-]/;
    var strreplacer = $(this).val();
    strreplacer = strreplacer.replace(re, '');
    strreplacer = strreplacer.toLowerCase();
    $(this).val( strreplacer );
  });
  $( ".elemento-oculto" ).fadeOut(0);
  $(".form-field-radio").click(function() {
    var showlement = $(this).children('input').attr("element-show");
    var hidelement = $(this).children('input').attr("element-hide");
    $( showlement ).fadeIn(100);
    $( hidelement ).fadeOut(100);
    $(this).children('input').prop('checked',true);
  });
  $('#the_form input[type=password]').val('')
  $('.thecolorpicker').spectrum({
    type: "text",
    showPalette: "false",
    showInitial: "true",
    showAlpha: "false",
    cancelText: "Cancelar",
    chooseText: "Escolher"
  });
  $(window).trigger("resize");
  $("#input-estado").change(function() {
    var estado = $(this).children("option:selected").val();
    $("#input-cidade").html("<option>-- Carregando cidades --</option>");
    $("#input-cidade").load("<?php just_url(); ?>/_core/_ajax/cidades.php?estado="+estado+"&cidade=<?php echo $dataestabelecimento['cidade']; ?>");
  });
  $("#input-estado").trigger("change");
  $(".maskdate").mask("99/99/9999",{placeholder:""});
  $(".maskrg").mask("99999999-99",{placeholder:""});
  $(".maskcpf").mask("999.999.999-99",{placeholder:""});
  $(".maskcnpj").mask("99.999.999/9999-99",{placeholder:""});
  $(".maskcel").mask("(99) 99999-9999");
  $(".maskcep").mask("99999-999");
  $(".dater").mask("99/99/9999");
  $(".masktime").mask("99:99:99");
  $(".maskmoney").maskMoney({ prefix: "R$ ", decimal: ",", thousands: "." });
});
</script>
<script src="<?php just_url(); ?>/_core/_cdn/cep/cep.js"></script>