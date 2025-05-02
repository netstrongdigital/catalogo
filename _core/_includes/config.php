<?php

include('fast_config.php');
set_time_limit(90);
ob_start();

// Debug
error_reporting(0);

// error_reporting(E_ALL);

// Time
date_default_timezone_set('America/Sao_Paulo');

// Url
$httprotocol = "https://";

if( !$_SERVER['HTTPS'] ) {
	$fixprotocol = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header("Location: ".$fixprotocol);
}

$suport_url = $httprotocol."$dominio/conheca/#contato";
$system_url = $httprotocol."$dominio/administracao";
$panel_url = $httprotocol."$dominio/painel";
$admin_url = $httprotocol."$dominio/administracao";
$just_url = $httprotocol."$dominio";
$app_url = $httprotocol."$dominio/app";
$simple_url = "$dominio";
$afiliado_url = $httprotocol."$dominio/afiliado";

// Comissão Afiliados
$comissao_afiliados = "$valor_comissionamento";

// Title
$seo_title = "$fast_seo_title";
$seo_description = "$fast_seo_description";

$titulo_topo = "$fast_titulo_topo"; 
$titulo_rodape = $fast_titulo_rodape;
$sub_titulo_rodape ="$fast_sub_titulo_rodape";
$titulo_rodape_marketplace ="$fast_titulo_rodape_marketplace";

// Redes/Whatsapp/Email
$whatsapp = $numero_whatsapp;
$usrtelefone = $numero_whatsapp;

$email ="$fast_email";
$youtube ="$fast_youtube";
$instagram="$fast_instagram";
$facebook ="$fast_facebook";

// Db
$db_host = "$fast_db_host";
$db_user = "$fast_db_user";
$db_pass = "$fast_db_pass";
$db_name = "$fast_db_name";

// SMTP
$smtp_host = "$fast_smtp_host"; 
$smtp_name = "$fast_smtp_name"; 
$smtp_user = "$fast_smtp_user";
$smtp_pass = "$fast_smtp_pass";

// Manunten
$manutencao = false;

if( $manutencao ) {
	include("manutencao.php");
	die;
}

// Includes
include("functions.php");

// Tokens
$token_melhorenvio = "$fast_token_melhorenvio";

// Recaptcha
// Gerar em: https://www.google.com/recaptcha/admin/
$recaptcha_sitekey = "$fast_recaptcha_sitekey";
$recaptcha_secretkey = "$fast_recaptcha_secretkey";

//External token Utilizado para receber os callbacks do mercado pago pro sistema, pode manter padr
$external_token = "$fast_external_token";

// Mercado pago
// Gerar em: https://www.mercadopago.com.br/developers/panel/credentials
$mp_sandbox = false;

if ($mp_sandbox == true) {
	$mp_public_key = "$fast_mp_public_key";
	$mp_acess_token = "$fast_mp_acess_token";

} else {
	$mp_public_key = "$fast_mp_public_key";
	$mp_acess_token = "$fast_mp_acess_token";
	$mp_client_id = "$fast_mp_client_id";
	$mp_client_secret = "$fast_mp_client_secret";
}

// Plano padr (id)
$plano_default = "$fast_plano_default";

// Root path
$rootpath = $_SERVER["DOCUMENT_ROOT"];

// Images
$image_max_width = 1000;
$image_max_height = 1000;
$gallery_max_files  = 10;

// Global header and footer
$system_header = "";
$system_footer = "";

// Keep Alive
if( $_SESSION['user']['logged'] == "1" && strlen( $_SESSION['user']['keepalive'] ) >= 10 && $_SESSION['user']['keepalive'] != $_COOKIE['keepalive'] ) {
	setcookie( 'keepalive', "kill", time() - 3600 );
	if( strlen( $_SESSION['user']['keepalive'] ) >= 10 ) {
		setcookie( 'keepalive', $_SESSION['user']['keepalive'], (time() + (120 * 24 * 3600)) );
	}
}

$keepalive = $_COOKIE['keepalive'];

if( $_SESSION['user']['logged'] != "1" && strlen( $keepalive ) >= 10 ) {
	make_login($keepalive,"","keepalive","2");
}

?>