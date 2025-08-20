<?php
session_start();
include '../conexao.php';
if (!isset($_SESSION['usuario']) || $_SESSION['acesso'] !== '2') {
    header("Location: ../login.php");
    exit;
}
if (isset($_GET['id'])) {
    $id_chamado = $_GET['id'];
    $sql = "SELECT * FROM chamados WHERE id = '$id_chamado'";
    $result = $conn->query($sql);
    $chamado = $result->fetch_assoc();
} else {
    header("Location: painel_ti.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $prioridade = $_POST['prioridade'];

    $stmt = $conn->prepare("UPDATE chamados SET status = ?, prioridade = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $prioridade, $id_chamado);
    $stmt->execute();
    header("Location: painel_ti.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Detalhes do Chamado</h2>
    <a href="painel_ti.php" class="btn btn-secondary mb-3">Voltar</a>
    <div class="card p-4 bg-white shadow-sm">
        <h5><strong>ID:</strong> <?= $chamado['id'] ?></h5>
        <h5><strong>Título:</strong> <?= $chamado['titulo'] ?></h5>
        <p><strong>Descrição:</strong> <?= $chamado['descricao_problema'] ?></p>
        <p><strong>Status:</strong> <?= $chamado['status'] ?></p>
        <p><strong>Prioridade:</strong> <?= $chamado['prioridade'] ?></p>
        <p><strong>Data de Abertura:</strong> <?= $chamado['data_abertura'] ?></p>
        
        <form method="post" class="mt-4">
            <div class="mb-3">
                <label class="form-label">Alterar Status</label>
                <select name="status" class="form-select" required>
                    <option <?= $chamado['status'] == 'Aberto' ? 'selected' : '' ?>>Aberto</option>
                    <option <?= $chamado['status'] == 'Em Atendimento' ? 'selected' : '' ?>>Em Atendimento</option>
                    <option <?= $chamado['status'] == 'Finalizado' ? 'selected' : '' ?>>Finalizado</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Alterar Prioridade</label>
                <select name="prioridade" class="form-select" required>
                    <option <?= $chamado['prioridade'] == 'Alta' ? 'selected' : '' ?>>Alta</option>
                    <option <?= $chamado['prioridade'] == 'Média' ? 'selected' : '' ?>>Média</option>
                    <option <?= $chamado['prioridade'] == 'Baixa' ? 'selected' : '' ?>>Baixa</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Atualizar Chamado</button>
        </form>
    </div>
</div>
</body>
</html>
