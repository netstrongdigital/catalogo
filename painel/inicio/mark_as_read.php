<?php
// Configurações de conexão com o banco de dados
include('../../_core/_includes/fast_config.php');

$host = $fast_db_host;
$dbname = $fast_db_name;
$user = $fast_db_user;
$pass = $fast_db_pass;

try {
    // Conectar ao banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar se o ID da notificação foi enviado
    if (!isset($_POST['id'])) {
        // Sai do script sem exibir qualquer mensagem se o ID não for fornecido
        exit;
    }

    $id = (int)$_POST['id'];

    // Atualizar a notificação como visualizada
    $stmt = $pdo->prepare("UPDATE notificacoes SET visualizado = TRUE WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Retornar uma resposta de sucesso
    echo json_encode(["status" => "success"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Erro na conexão: " . $e->getMessage()]);
}
?>
