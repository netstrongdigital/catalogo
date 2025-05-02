<?php
include('../../../_core/_includes/config.php');
restrict_estabelecimento();

$eid = $_SESSION['estabelecimento']['id'];
$pedidos = mysqli_query($db_con, 
    "SELECT p.id 
     FROM pedidos_para_imprimir pi
     JOIN pedidos p ON pi.pedido_id = p.id
     WHERE p.rel_estabelecimentos_id = '$eid'
     AND pi.impresso = 0
     LIMIT 1");

if(mysqli_num_rows($pedidos) > 0) {
    $pedido = mysqli_fetch_assoc($pedidos);
    mysqli_query($db_con, "UPDATE pedidos_para_imprimir SET impresso = 1 WHERE pedido_id = '{$pedido['id']}'");
    header("Location: ".panel_url()."/pedidos/imprimir/?id={$pedido['id']}");
    exit();
}