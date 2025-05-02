<?php 
include('../../_core/_includes/config.php');
$token = mysqli_real_escape_string( $db_con, $_GET['token'] );
$eid = mysqli_real_escape_string( $db_con, $_GET['eid'] );
session_id($token);
?>

<?php if( $_SESSION['estabelecimento']['id'] == $eid ) { ?>

	<?php
	if( data_info( "estabelecimentos", $_SESSION['estabelecimento']['id'], "funcionamento" ) == "1" ) { 
	mysqli_query( $db_con, "UPDATE estabelecimentos SET funcionamento = '2' WHERE id = '$eid'" );
	?>
		<div class="fechado">
			<div class="status-icon-container">
				<i class="open-status"></i>
				<span class="funcionamento-text">Fechado</span>
				<i class="lni lni-shuffle"></i>
			</div>
			<div class="menu-title">Estabelecimento indisponível</div>
			<div class="card-subtext">fechado para pedidos</div>
		</div>

	<?php
	} else {
	mysqli_query( $db_con, "UPDATE estabelecimentos SET funcionamento = '1' WHERE id = '$eid'" );
	?>

		<div class="aberto">
			<div class="status-icon-container">
				<i class="open-status"></i>
				<span class="funcionamento-text">Aberto</span>
				<i class="lni lni-shuffle"></i>
			</div>
			<div class="menu-title">Estabelecimento disponível</div>
			<div class="card-subtext">aberto para pedidos</div>
		</div>

	<?php } ?>

<?php } ?>