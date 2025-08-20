<?php
session_start();
include '../conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['acesso'] !== '2') {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['matricula'])) {
    $matricula = $_GET['matricula'];

    // Exclui primeiro da tabela adicionais_ti
    $stmt1 = $conn->prepare("DELETE FROM adicionais_ti WHERE matricula = ?");
    $stmt1->bind_param("s", $matricula);
    $stmt1->execute();

    // Depois da tabela principal de usuários
    $stmt2 = $conn->prepare("DELETE FROM dados WHERE matricula = ?");
    $stmt2->bind_param("s", $matricula);
    $stmt2->execute();
}

header("Location: gerenciar_usuarios.php");
exit;


