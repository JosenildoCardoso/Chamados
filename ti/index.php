<?php
session_start();
include '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM dados WHERE login = '$login' AND senha = '" . md5($senha) . "'";
    $result = $conn->query($sql);
  
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $mat = $row['matricula'];
        $_SESSION['usuario'] = $login;
        $_SESSION['matricula'] = $mat;
        $res = $conn->query("SELECT acesso FROM adicionais_ti WHERE matricula = '$mat'");
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $_SESSION['acesso'] = $row['acesso'];
            header("Location: " . ($row['acesso'] == '2' ? "painel_ti.php" : "ver_chamados.php"));
        } else {
            $_SESSION['acesso'] = '1';
            header("Location: ver_chamados.php");
        }
    } else {
        $erro = "Login inválido.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Sistema de Chamados - Login</h2>
    <?php if (isset($erro)) echo "<div class='alert alert-danger'>$erro</div>"; ?>
    <form method="post" class="card p-4 bg-white shadow-sm">
        <div class="mb-3">
            <label class="form-label">Login</label>
            <input type="text" name="login" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" name="senha" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Entrar</button>
    </form>
</div>
</body>
</html>
