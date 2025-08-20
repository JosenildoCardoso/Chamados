<?php
session_start();
include '../conexao.php';
if (!isset($_SESSION['usuario']) || $_SESSION['acesso'] !== '2') {
    header("Location: ../login.php");
    exit;
}

$sql = "SELECT d.matricula, d.nome, d.senha, a.acesso 
        FROM dados d 
        LEFT JOIN adicionais_ti a ON d.matricula = a.matricula";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Gerenciar Usuários</h2>
    <a href="painel_ti.php" class="btn btn-secondary mb-3">Voltar</a>
    <a href="cadastrar_usuario.php" class="btn btn-success mb-3">Novo Usuário</a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Matrícula</th>
                    <th>Nome</th>
                    <th>Senha</th>
                    <th>Acesso</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($usuario = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $usuario['matricula'] ?></td>
                        <td><?= $usuario['nome'] ?></td>
                        <td><?= $usuario['senha'] ?></td>
                        <td><?= $usuario['acesso'] ?></td>
                        <td>
                            <a href="editar_usuario.php?matricula=<?= $usuario['matricula'] ?>" class="btn btn-sm btn-primary">Editar</a>
                            <a href="excluir_usuario.php?matricula=<?= $usuario['matricula'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

