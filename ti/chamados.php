<?php
session_start();
include '../conexao.php';
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
$matricula = $_SESSION['matricula'];
$sql = "SELECT * FROM chamados WHERE matricula = '$matricula'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meus Chamados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include("../header.php"); ?>
<div class="container mt-5">
    <h2>Meus Chamados</h2>
    <a href="abrir_chamado_ti.php" class="btn btn-success mb-3">Abrir Novo Chamado</a>
    <a href="logout.php" class="btn btn-secondary mb-3 float-end">Sair</a>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr><th>ID</th><th>Título</th><th>Status</th><th>Prioridade</th><th>Abertura</th></tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['titulo'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['prioridade'] ?></td>
                    <td><?= $row['data_abertura'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
