<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Chamados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Chamados</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if (isset($_SESSION['acesso']) && $_SESSION['acesso'] === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="/ti/painel_ti.php">Painel TI</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ti/gerenciar_usuarios.php">Usuários</a></li>
                <?php elseif (isset($_SESSION['acesso']) && $_SESSION['acesso'] === 'usuario'): ?>
                    <li class="nav-item"><a class="nav-link" href="/usuario/painel_usuario.php">Painel Usuário</a></li>
                    <li class="nav-item"><a class="nav-link" href="/usuario/abrir_chamado.php">Novo Chamado</a></li>
                <?php endif; ?>
            </ul>
            <span class="navbar-text me-3 col-10">
                <?= $_SESSION['usuario'] ?? '' ?>
            </span>
            <a href="logout.php" class="btn btn-outline-light col-1">Sair</a>
        </div>
    </div>
</nav>
<div class="container">
