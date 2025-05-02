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

    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];

        // Insere a notificação no banco de dados
        $stmt = $pdo->prepare("INSERT INTO notificacoes (titulo, descricao, visualizado, data_criacao) VALUES (:titulo, :descricao, FALSE, NOW())");
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->execute();

        // Redireciona de volta para a página de notificações
        header("Location: index.php"); // Altere 'index.php' para o nome do seu arquivo de notificações
        exit();
    }
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
