<?php session_start(); if (!isset($_SESSION['usuario']) || $_SESSION['acesso'] != 1) { header("Location: ../login.php"); exit; } ?>
<!DOCTYPE html>
<html>
<head><title>Painel do Usuário</title></head>
<body>
    <h2>Bem-vindo, <?php echo $_SESSION['usuario']; ?>!</h2>
    <a href="novo_chamado.php">Abrir Chamado</a><br>
    <a href="meus_chamados.php">Meus Chamados</a><br>
    <a href="../logout.php">Sair</a>
</body>
</html>