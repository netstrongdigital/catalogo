<?php
// Conexão com o banco de dados (use as mesmas credenciais do código anterior)
include('../../_core/_includes/fast_config.php');

$host = $fast_db_host;
$dbname = $fast_db_name;
$user = $fast_db_user;
$pass = $fast_db_pass;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// Busca todas as notificações do banco de dados
$stmt = $pdo->query("SELECT * FROM notificacoes ORDER BY data_criacao DESC");
$notificacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Notificações</title>
</head>
<body>
    <h1>Notificações</h1>
    <?php if ($notificacoes): ?>
        <ul>
            <?php foreach ($notificacoes as $notificacao): ?>
                <li>
                    <h3><?php echo htmlspecialchars($notificacao['titulo']); ?></h3>
                    <p><?php echo htmlspecialchars($notificacao['descricao']); ?></p>
                    <small><?php echo date('d/m/Y H:i', strtotime($notificacao['data_criacao'])); ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nenhuma notificação disponível.</p>
    <?php endif; ?>
</body>
</html>
