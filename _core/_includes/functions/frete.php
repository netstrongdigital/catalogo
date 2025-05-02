<?php
include('../config.php');

function calcular_frete_pacote($cep_origem, $cep_destino, $altura, $largura, $comprimento, $peso) {
    try{
        $url = "https://www.melhorenvio.com.br/api/v2/me/shipment/calculate";
        $token = "$token_melhorenvio";

        // Dados a serem enviados
        $data = json_encode([
            "from" => [
                "postal_code" => $cep_origem ?? ""
            ],
            "to" => [
                "postal_code" => $cep_destino ?? ""
            ],
            "package" => [
                "height" => $altura ?? "",
                "width" => $largura ?? "",
                "length" => $comprimento ?? "",
                "weight" => $peso ?? ""
            ]
        ]);

        // Configuração do cabeçalho
        $headers = [
            "Accept: application/json",
            "Authorization: Bearer $token",
            "Content-Type: application/json",
            "User-Agent: Aplicação $email"
        ];

        // Inicializando cURL
        $curl = curl_init();

        // Configurando a solicitação cURL
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        // Executando a solicitação e capturando a resposta
        $response = curl_exec($curl);

        // Verificando erros
        if (curl_errno($curl)) {
            //'Erro na solicitação cURL: ' . curl_error($curl);
            return false; 
        } else {
            // Exibindo a resposta
            // echo "<pre>";
            // var_dump(json_decode($response, true));
            // echo "</pre>";
            return  json_decode($response, true);
        }

    }catch (Exception $e) {
        return false;
    }
}