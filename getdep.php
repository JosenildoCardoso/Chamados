<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexao.php';
// Conexão com o banco (exemplo com MySQLi)


// Recebe parâmetro (ex: código do estado)
$estado = $_GET['cod'] ?? '';

// Consulta segura usando prepared statement para evitar SQL Injection
$stmt = $conn->prepare("SELECT cod, descricao FROM subsetor WHERE tipo = ?");
$stmt->bind_param("i", $estado);
$stmt->execute();
$result = $stmt->get_result();

$cidades = [];
while ($row = $result->fetch_assoc()) {
    $cidades[] = $row;
}

// Fecha a conexão
$stmt->close();
$conn->close();

// Retorna o JSON com a lista de cidades
echo json_encode($cidades, JSON_UNESCAPED_UNICODE);


?>