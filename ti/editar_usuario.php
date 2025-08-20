<?php
session_start();
include '../conexao.php';
if (!isset($_SESSION['usuario']) || $_SESSION['acesso'] !== '2') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['matricula'])) {
    $matricula = $_GET['matricula'];
    $sql = "SELECT * FROM dados WHERE matricula = '$matricula'";
    $result = $conn->query($sql);
    $usuario = $result->fetch_assoc();
    if (!$usuario) {
        header("Location: painel_ti.php");
        exit;
    }
} else {
    header("Location: painel_ti.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];
    $acesso = $_POST['acesso'];

    $stmt = $conn->prepare("UPDATE dados SET nome = ?, senha = ? WHERE matricula = ?");
    $stmt->bind_param("sss", $nome, $senha, $matricula);
    $stmt->execute();

    $stmt_acesso = $conn->prepare("UPDATE adicionais_ti SET acesso = ? WHERE matricula = ?");
    $stmt_acesso->bind_param("ss", $acesso, $matricula);
    $stmt_acesso->execute();

    header("Location: painel_ti.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Editar Usuário</h2>
    <a href="painel_ti.php" class="btn btn-secondary mb-3">Voltar</a>
    <form method="post" class="card p-4 bg-white shadow-sm">
        <div class="mb-3">
            <label class="form-label">Matrícula</label>
            <input type="text" name="matricula" class="form-control" value="<?= $usuario['matricula'] ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="nome" class="form-control" value="<?= $usuario['nome'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" name="senha" class="form-control" value="<?= $usuario['senha'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Acesso</label>
            <select name="acesso" class="form-select" required>
                <option value="admin" <?= $usuario['acesso'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                <option value="usuario" <?= $usuario['acesso'] == 'usuario' ? 'selected' : '' ?>>Usuário Comum</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Atualizar</button>
    </form>
</div>
</body>
</html>


