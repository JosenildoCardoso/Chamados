<?php
include 'conexao.php';

$term = isset($_GET['term']) ? $_GET['term'] : '';

if ($term !== '') {
    $stmt = $conn->prepare("SELECT matricula, nome FROM dados WHERE nome LIKE CONCAT(?, '%') LIMIT 10");
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $result = $stmt->get_result();
    
    //$dados[];
    while ($row = $result->fetch_assoc()) {
        $dados[] = array('id'=>$row['matricula'], 'nome' => $row['nome']);
    }

    header('Content-Type: application/json');
    echo json_encode($dados);
}
?>
