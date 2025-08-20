<?php
session_start();
include '../conexao.php';
if (!isset($_SESSION['usuario']) || $_SESSION['acesso'] !== '2') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST['matricula'];
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];
    $acesso = $_POST['acesso'];

    $stmt = $conn->prepare("INSERT INTO dados (matricula, nome, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $matricula, $nome, $senha);
    $stmt->execute();

    $stmt_acesso = $conn->prepare("INSERT INTO adicionais_ti (matricula, acesso) VALUES (?, ?)");
    $stmt_acesso->bind_param("ss", $matricula, $acesso);
    $stmt_acesso->execute();

    header("Location: painel_ti.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Cadastrar Novo Usuário</h2>
    <a href="painel_ti.php" class="btn btn-secondary mb-3">Voltar</a>
    <form method="post" class="card p-4 bg-white shadow-sm">
        <div class="mb-3">
            <label class="form-label">Matrícula</label>
            <input type="text" name="matricula" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" name="senha" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Acesso</label>
            <select name="acesso" class="form-select" required>
                <option value="admin">Administrador</option>
                <option value="usuario">Usuário Comum</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Cadastrar</button>
    </form>
</div>
</body>
</html>

