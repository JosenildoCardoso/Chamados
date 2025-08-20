<?php
session_start();
include '../conexao.php';
if (!isset($_SESSION['usuario']) || $_SESSION['acesso'] !== '2') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: painel_ti.php");
    exit;
}

$id_chamado = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mensagem = $_POST['mensagem'];
    $usuario = $_SESSION['matricula'];
    $data = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO historico_chamados (chamado_id, usuario, mensagem, data_hora) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $id_chamado, $usuario, $mensagem, $data);
    $stmt->execute();

    header("Location: ver_chamado.php?id=$id_chamado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Responder Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Responder ao Chamado #<?= $id_chamado ?></h2>
    <a href="ver_chamado.php?id=<?= $id_chamado ?>" class="btn btn-secondary mb-3">Voltar</a>
    <form method="post" class="card p-4 bg-white shadow-sm">
        <div class="mb-3">
            <label class="form-label">Mensagem</label>
            <textarea name="mensagem" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar Resposta</button>
    </form>
</div>
</body>
</html>

