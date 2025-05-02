<?php
$dominioAutorizado = "catalogo.zfoxx.xyz";

// Obtém o domínio do atual pedido
$dominio = $_SERVER['HTTP_HOST'];

// Verifica se o domínio atual é autorizado (incluindo subdomínios)

//if (strpos($dominio, $dominioAutorizado) !== false) {


    // Domínio autorizado, continue com a execução do sistema
    // Restante do código do seu sistema aqui...
include('functions/db.php');
require("functions/phpmailer/PHPMailerAutoload.php");
include('functions/general.php');
include('functions/data.php');
include('functions/upload.php');
include('functions/user.php');
include('functions/aditional.php');


// FUNÇÕES ADICIONAIS

function verifica_horario($id) {
                        global $db_con;
					        $aberto = "disabled";
					        
					        $fechamento_query = "SELECT * FROM `agendamentos` WHERE rel_estabelecimentos_id = $id AND acao = 2";
					        
				            $dia_atual = strtolower(substr(date("l"), 0, 3));
				    
					        // Verifica se está fechado
					        date_default_timezone_set('Brazil/Brasilia'); 
					        
					        $fechamento_data = mysqli_fetch_array(mysqli_query($db_con, $fechamento_query));
					        
					        $horario_fechamento = strtotime($fechamento_data['hora']);
					        $horario_atual = time();
					        
					        $abertura_query = "SELECT * FROM `agendamentos` WHERE rel_estabelecimentos_id = $id AND acao = 1 AND `$dia_atual` = 1";
					        $abertura_result = mysqli_fetch_array(mysqli_query($db_con, $abertura_query));
					        $horario_abertura = strtotime($abertura_result['hora']);
					        
					        if ( $abertura_result[$dia_atual] == 1 && $fechamento_data[$dia_atual == 1] ) {
    					        if ($horario_atual < $horario_abertura || $horario_atual >= $horario_fechamento) {
    					            // false
    					            $aberto = "close";
    					        } else {
    					            $aberto = "open";
    					        }
					        } else {
					            $aberto = "disabled";
					        }
					        
					        
					        return $aberto;
}


