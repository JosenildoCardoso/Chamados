<?php
include 'conexao.php';

$term = isset($_GET['term']) ? $_GET['term'] : '';

if ($term !== '') {
    $stmt = $conn->prepare("SELECT id, descricao FROM tipo_problema WHERE descricao LIKE CONCAT('%', ?, '%') LIMIT 10");
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $result = $stmt->get_result();
    
    //$dados[];
    while ($row = $result->fetch_assoc()) {
        $dados[] = array('id'=>$row['id'], 'descricao' => $row['descricao']);
    }

    header('Content-Type: application/json');
    echo json_encode($dados);
}
?>
