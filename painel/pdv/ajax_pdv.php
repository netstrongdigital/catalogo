<?php

// CORE
include('../../_core/_includes/config.php');
//include "config.php";

global $db_con;
global $_SESSION;


$db_conn = $db_con;

$token = $_SESSION['estabelecimento']['id'];
$id_estabelecimento = $token;

function log_data_ajax($message, $data = null)
{
    //return; // Comente para ativar o log

    date_default_timezone_set('America/Sao_Paulo');

    $log_dir = __DIR__;
    $log_file = $log_dir . '/log.txt';

    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0755, true);
    }

    $current = file_exists($log_file) ? file_get_contents($log_file) : '';
    $log_entry = "[" . date("Y-m-d H:i:s") . "] " . $message;

    if ($data !== null) {
        $log_entry .= " - " . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    $log_entry .= "\n";

    file_put_contents($log_file, $current . $log_entry);
}

//Processar AJAX registrar_pedido_pdv

// Verificar se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obter dados do pedido
    $pedido = $_POST['pedido'];
    $peid = $_POST['pedido'];
    // log_data_ajax('Pedido Array: ' . print_r($pedido, true));

    // Obter dados do cliente do pedido
    $cliente = $pedido['cliente'];

    $itens = $pedido['itens'];

    //$token = $cliente['token'];
    $rel_segmentos_id = $cliente['rel_segmentos_id'];
    $rel_estabelecimentos_id = $cliente['rel_estabelecimentos_id'];
    $nome = $cliente['nome'];
    $whatsapp = $cliente['whatsapp'];
    $estado = $cliente['estado'];
    $cidade = $cliente['cidade'];
    $forma_entrega = $cliente['forma_entrega'];
    $forma_entrega_nome = $cliente['forma_entrega_nome'];
    $endereco_cep = $cliente['endereco_cep'];
    $endereco_numero = $cliente['endereco_numero'];
    $endereco_bairro = $cliente['endereco_bairro'];
    $endereco_rua = $cliente['endereco_rua'];
    $endereco_complemento = $cliente['endereco_complemento'];
    $endereco_referencia = $cliente['endereco_referencia'];
    $forma_pagamento = $cliente['forma_pagamento'];
    $forma_pagamento_nome = $cliente['forma_pagamento_nome'];
    $forma_pagamento_informacao = $cliente['forma_pagamento_informacao'];
    $data_hora = $cliente['data_hora'];
    $vpedido = $cliente['vpedido'];
    $cupom = $cliente['cupom'];

    // Preparar e enviar a resposta
    $response = [];
    try {
        //Registrar pedido
        if ($pedido_pdv = new_pedido_pdv(
            $db_conn,
            $token,
            $rel_segmentos_id,
            $rel_estabelecimentos_id,
            $nome,
            $whatsapp,
            $estado,
            $cidade,
            $forma_entrega,
            $endereco_cep,
            $endereco_numero,
            $endereco_bairro,
            $endereco_rua,
            $endereco_complemento,
            $endereco_referencia,
            $forma_pagamento,
            $forma_pagamento_informacao,
            $data_hora,
            $vpedido,
            $cupom
        )) {
            log_data_ajax('Pedido PDV Sucesso: ' . $pedido_pdv);

            $peid = $pedido_pdv;

            $comprovante = gera_comprovante_pdv($rel_estabelecimentos_id, "texto", "1", $peid, $nome, $whatsapp, $forma_entrega, $forma_entrega_nome, $endereco_rua, $endereco_numero, $endereco_bairro, $endereco_cep, $endereco_complemento, $endereco_cidade, $endereco_referencia, $forma_pagamento_nome, $forma_pagamento_informacao, $itens, $cupom);
            $comprovante_html = gera_comprovante_pdv($rel_estabelecimentos_id, "texto", "1", $peid, $nome, $whatsapp, $forma_entrega, $forma_entrega_nome, $endereco_rua, $endereco_numero, $endereco_bairro, $endereco_cep, $endereco_complemento, $endereco_cidade, $endereco_referencia, $forma_pagamento_nome, $forma_pagamento_informacao, $itens, $cupom);
            $comprovante_html_escapado = mysqli_real_escape_string($db_conn, $comprovante_html);

            mysqli_query($db_conn, "UPDATE pedidos SET comprovante = '$comprovante' WHERE id = '$peid'");
            mysqli_query($db_conn, "UPDATE pedidos SET comprovante_html = '$comprovante_html_escapado' WHERE id = '$peid'");

            $confirmar_pedido = mysqli_query($db_conn, "UPDATE pedidos SET status = '6' WHERE id = '$peid' AND rel_estabelecimentos_id = '$rel_estabelecimentos_id'");

            // patch-bessa update de estoque
            foreach ($pedido["itens"] as $prodkey => $product) {
                $productId = (int)$product["produto_id"];
                $ammoutPurchased = $product["quantidade"];
                $ammoutInStock = $product["estoque"];

                $stockAfterDeduction = (int)$ammoutInStock - (int)$ammoutPurchased;
                $updatedquery = "UPDATE produtos SET posicao = $stockAfterDeduction WHERE id = $productId";

                mysqli_query($db_conn, $updatedquery);
            }

            log_data_ajax('Confirmar Pedido PDV Sucesso: ' . $confirmar_pedido);

            $response['status'] = 'sucesso';
            $response['mensagem'] = 'Pedido registrado com sucesso.';
            $response['pedido_pdv'] = $pedido_pdv;
        } else {
            log_data_ajax('Pedido PDV Erro: ' . $pedido_pdv);
            $response['status'] = 'erro';
            $response['mensagem'] = 'Ocorreu um erro ao registrar o pedido. Por favor, tente novamente.';
            $response['debug'] = $pedido_pdv;
        }
    } catch (Exception $e) {
        // Em caso de erro
        log_data_ajax('Pedido PDV Exception: ' . print_r($e, true));
        $response['status'] = 'erro';
        $response['mensagem'] = 'Ocorreu um erro ao registrar o pedido. Por favor, tente novamente.';
    }

    // Enviar resposta em formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Resposta para métodos não permitidos
    header("HTTP/1.1 405 Method Not Allowed");
    exit;
}


function new_pedido_pdv( 
    $db_conn,
    $token,
  $rel_segmentos_id,
  $rel_estabelecimentos_id,
  $nome,
  $whatsapp,
  $forma_entrega,
  $estado,
  $cidade,
  $endereco_cep,
  $endereco_numero,
  $endereco_bairro,
  $endereco_rua,
  $endereco_complemento,
  $endereco_referencia,
  $forma_pagamento,
  $forma_pagamento_informacao,
  $data_hora,
  $cupom,
  $vpedido
) {

  session_id( $token );
  $status = "1";

  if( mysqli_query( $db_conn, "INSERT INTO pedidos (
      rel_segmentos_id,
      rel_estabelecimentos_id,
      nome,
      whatsapp,
      forma_entrega,
      estado,
      cidade,
      endereco_cep,
      endereco_numero,
      endereco_bairro,
      endereco_rua,
      endereco_complemento,
      endereco_referencia,
      forma_pagamento,
      forma_pagamento_informacao,
      status,
      data_hora,
      cupom,
      v_pedido
  ) VALUES (
      '$rel_segmentos_id',
      '$rel_estabelecimentos_id',
      '$nome',
      '$whatsapp',
      '$forma_entrega',
      '$estado',
      '$cidade',
      '$endereco_cep',
      '$endereco_numero',
      '$endereco_bairro',
      '$endereco_rua',
      '$endereco_complemento',
      '$endereco_referencia',
      '$forma_pagamento',
      '$forma_pagamento_informacao',
      '$status',
      '$data_hora',
      '$cupom',
      '$vpedido'
  );") ) {

      $peid = mysqli_insert_id($db_conn);
      $comprovante = gera_comprovante($rel_estabelecimentos_id,"texto","1",$peid);

      mysqli_query( $db_conn, "UPDATE pedidos SET comprovante = '$comprovante' WHERE id = '$peid'" );

      // CUPOM
      $checkcupom = mysqli_query( $db_conn, "SELECT * FROM cupons WHERE codigo = '$cupom' AND rel_estabelecimentos_id = '$rel_estabelecimentos_id' LIMIT 1");
      $hascupom = mysqli_num_rows( $checkcupom );
      $datacupom = mysqli_fetch_array( $checkcupom );

      if( $hascupom ) {
          $newquantidade = $datacupom['quantidade'] - 1;
          if( $newquantidade <= 0 ) {
              $newquantidade = 0;
          }
          mysqli_query( $db_conn, "UPDATE cupons SET quantidade = '$newquantidade' WHERE codigo = '$cupom' AND rel_estabelecimentos_id = '$rel_estabelecimentos_id'" );
      }

      // SALVA LOG

      // / SALVA LOG

      return $peid;
  
  } else {

      return false;

  }

}



function gera_comprovante_pdv(
    $eid,
    $modo,
    $tamanho,
    $numero,
    $nome,
    $whatsapp,
    $forma_entrega,
    $forma_entrega_nome,
    $endereco_rua,
    $endereco_numero,
    $endereco_bairro,
    $endereco_cep,
    $endereco_complemento,
    $endereco_cidade,
    $endereco_referencia,
    $forma_pagamento_pdv,
    $forma_pagamento_informacao,
    $itens,
    $cupom
) {

    global $_SESSION;

    $nome_estab = mysqli_query( $db_conn, "SELECT nome FROM estabelecimentos WHERE id = '$eid' LIMIT 1");

	$estabelecimento = mysqli_fetch_array( $nome_estab );


    $subdominio_estab = mysqli_query( $db_conn, "SELECT subdominio FROM estabelecimentos WHERE id = '$eid' LIMIT 1");

	$subdominio = mysqli_fetch_array( $subdominio_estab );

    // $estabelecimento = data_info("estabelecimentos", $eid, "nome");
    // //Subdominio do estabelecimento
    // $subdominio = data_info("estabelecimentos", $eid, "subdominio");

    $horario = date('d/m/Y \à\s H:i');

    $subtotal = array();
    $comprovante = "";
    $comprovante .= "------\n<br>";
    $comprovante .= "<strong>" . strtoupper("Pedido " . $estabelecimento . ": #" . $numero . " </strong><br> \n");

    $comprovante .= "------\n\n<br><br>";

    if ($tamanho == "1") {

        $comprovante = trim($comprovante, "\n<br>");
        $comprovante .= "\n<br>" . $horario . "\n<br>";
        $comprovante .= "------\n\n<br><br>";

        $comprovante .= "<strong>Nome:</strong><br> \n";
        $comprovante .= $nome . " \n\n<br><br>";
        $comprovante .= "<strong>Whatsapp:</strong> \n<br>";
        $comprovante .= $whatsapp . " \n\n<br><br>";


        // $forma_entrega = $forma_entrega_nome;

        // //Se o nome da forma de entrega contiver "retirada" ou "loja" então não exibe o endereço
        // if (strpos($forma_entrega_nome, 'Retirada') !== false || strpos($forma_entrega_nome, 'Loja') !== false) {

        // }
        
            $comprovante .= "<strong>Endereço:</strong> \n";

            if ($endereco_rua != '' || $endereco_rua != null) {
                $comprovante .= "" . $endereco_rua . ", ";
            }

            if ($endereco_numero != '' || $endereco_numero != null) {
                $comprovante .= "" . $endereco_numero . ", ";
            }

            if ($endereco_bairro != '' || $endereco_bairro != null) {
                $comprovante .= "" . $endereco_bairro . ", ";
            }

            if ($endereco_cep != '' || $endereco_cep != null) {
                $comprovante .= "" . $endereco_cep . ", ";
            }

            if ($endereco_complemento != '' || $endereco_complemento != null) {
                $comprovante .= "" . $endereco_complemento . ", ";
            }

            if ($endereco_cidade != '' || $endereco_cidade != null) {
                $comprovante .= "" . $endereco_cidade . ", ";
            }

            if ($endereco_referencia != '' || $endereco_referencia != null) {
                $comprovante .= "" . $endereco_referencia . ", ";
            }


            $comprovante .= "\n\n";

        if ($forma_pagamento_pdv != '' || $forma_pagamento_pdv != null) {

            $forma_pagamento = $forma_pagamento_pdv;
            $informacaopagamento = $forma_pagamento_informacao;
        }

        $comprovante .= "<strong>Forma de pagamento:</strong> \n<br>";

        $comprovante .= $forma_pagamento . $informacaopagamento . " \n\n<br><br>";
        $comprovante .= "------\n<br>";
        $comprovante .= "<strong>PRODUTOS</strong> \n<br>";
        $comprovante .= "------\n\n<br><br>";
    }

    //Itens é uma array e cada item é uma array com os dados do produto
    foreach ($itens as $key => $value) {
        $pid = $value['produto_id'];
        $nome_produto = $value['produto_nome'];
        $produto_ref = 'Selecionado'/*$value['ref']*/;
        $query_content = mysqli_query($db_conn, "SELECT * FROM produtos WHERE id = '$pid' AND status = '1' ORDER BY id ASC LIMIT 1");
        $data_content = mysqli_fetch_array($query_content);
        $valor_final = $data_content['valor_promocional'];
        $comprovante .= "#" . $produto_ref . " \n<br>";
        $comprovante .= "<div class='prod-comprovante'><img src='https://" . $subdominio . "." . $simple_url . "/_core/_upload/" . $data_content['destaque'] . "'>";
        $comprovante .= "<div class='txt-valor-prod'><strong>" . $value['quantidade'] . " x</strong> " . $nome_produto . "\n<br>";
        if ($value['variacoes'] != "") {
            $variacoes = $value['variacoes'];
            $comprovante .= $variacoes . "\n<br>";
        }
        $comprovante .= "<strong>Valor:</strong> R$ " . dinheiro($value['subtotal_item'], "BR") . "\n</div></div><br>";
        $comprovante .= "\n<br>";
        $subtotal[] .= $value['subtotal_item'];
    }

    $subtotal_valor = array_sum($subtotal);
    $subtotal = "R$ " . dinheiro($subtotal_valor, "BR");

    // Cupom
    $datetime = date("Y-m-d h:i:s");
    $cupom = $cupom;

    if ($cupom == "") {
        $cupom_ativo = "0";
        $cupom_desc = "Nenhum desconto aplicado";
        $cupom_descontado = "0";
    } else {
        $cupom_ativo = "1";
        $cupom_desc = $cupom;
        $cupom_descontado = "0";
    }

    // Frete

    //Se o nome da forma de entrega contiver "retirada" ou "loja" então não exibe o endereço
    if (strpos($forma_entrega_nome, 'Retirada') !== false || strpos($forma_entrega_nome, 'Loja') !== false) {
        $frete_desc = "Retirada no local";
    } else {
        $frete_desc = $forma_entrega_nome;
    }

    $total = "R$ " . (dinheiro($subtotal_valor, "BR"));

    $comprovante .= "------\n<br>";
    $comprovante .= "<strong>Subtotal:</strong> " . $subtotal . "\n<br>";
    if ($cupom_ativo) {
        $comprovante .= "<strong>Desconto:</strong> " . $cupom_desc . "\n<br>";
    }
    $comprovante .= "<strong>Entrega:</strong> " . $frete_desc . "\n<br>";
    $comprovante .= "------\n\n<br><br>";
    $comprovante .= "<strong>Total:</strong> " . $total . "\n<br>";

    if ($modo == "html") {
        /*$comprovante = str_replace("'", "", $comprovante);
        $comprovante = htmlcleanbb($comprovante);
        $comprovante = bbzap($comprovante);
        $comprovante = nl2br($comprovante);*/
        $stylehtml = "<style> 
                        .prod-comprovante {
                            display: flex;
                            gap: 15px;
                        }
                        .prod-comprovante img {
                            width: 77px;
                            height: 77px;
                        }
                    </style>";
        return $comprovante . $stylehtml;
    } else if ($modo == "texto") {
        $comprovante = str_replace("'", "", $comprovante);
        $comprovante = htmlcleanbb($comprovante);
        return $comprovante;
    }
}
