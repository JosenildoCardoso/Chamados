<?php
include dirname(__DIR__, 2) . "\conexao.php";
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
if($method == 'GET') {
// Pega a página atual (padrão = 0) e calcula offset
$pag = $_GET['pag'] ?? 0;
$limit = 20;
$offset = $pag * $limit;

$sql = "SELECT nome, dbuser.login, dbuser.matricula, atadb.acesso as codacesso, acat.descricao as acesso, un.descricao as unidade FROM dados dbuser INNER JOIN adicionais_chamados atadb ON dbuser.matricula = atadb.matricula  LEFT JOIN setor un ON un.cod = atadb.setor LEFT JOIN acessos_chamados acat ON acat.cod = atadb.acesso WHERE 1=1 ";


$sqlq = "SELECT COUNT(*) as total  FROM dados dbuser INNER JOIN adicionais_chamados atadb ON dbuser.matricula = atadb.matricula  LEFT JOIN setor un ON un.cod = atadb.setor LEFT JOIN acessos_chamados acat ON acat.cod = atadb.acesso WHERE 1=1";
$params = [];
$types  = "";
$dados = [];
// filtro por unidade
if (!empty($_GET['unidade'])) {
    $sql .= " AND atadb.setor = ?";
	$sqlq .= " AND atadb.setor = ?";
    $params[] = $_GET['unidade'];
	$types .= "i"; // se unidade for inteiro
}

// filtro por nome ou matrícula usando LIKE
if (!empty($_GET['nome'])) {
    $sql .= " AND (dbuser.nome LIKE ? OR dbuser.matricula LIKE ?)";
	 $sqlq .= " AND (dbuser.nome LIKE ? OR dbuser.matricula LIKE ?)";
    $nomeLike = "%{$_GET['nome']}%";
    $params[] = $nomeLike;
    $params[] = $nomeLike;
    $types .= "ss";
}

if (!empty($_GET['acesso'])) {
    $sql .= " AND atadb.acesso =  ?";
	 $sqlq .= " AND atadb.acesso =  ?";
    $params[] = $_GET['acesso'];
    $types .= "i";
}

//$sql .= " ORDER BY dbuser.nome LIMIT $pag OFFSET $limit";


$stmt = $conn->prepare($sql);
$stmtq = $conn->prepare($sqlq);

// bind só se houver parâmetros
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
	 $stmtq->bind_param($types, ...$params);
	}

$stmt->execute();
$result = $stmt->get_result();
$stmtq->execute();
$resultq = $stmtq->get_result();
$total = $resultq->fetch_assoc()['total'];

if($result->num_rows > 0){
while ($row = $result->fetch_assoc()) {
    $dados[] = $row;
}
$array = array("total" => $total, "por_pagina" => $limit, "pagina" => $pag, "dados" => $dados);


}else{
	
	$array = array("total" => 0, "por_pagina" => $limit, "pagina" => $pag, "dados" => null);
}

echo json_encode($array);
}else if($method == 'DELETE') {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $stmt = $conn->prepare("DELETE FROM adicionais_chamados WHERE matricula = ?");
        $stmt->bind_param("s", $id);
     //   if ($stmt->execute()) {
            if (1!=1) {
            echo json_encode(["status" => "success", "message" => "Usuário excluído com sucesso."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Erro ao excluir usuário."]);
        }
            
    } else {
        echo json_encode(["status" => "error", "message" => "ID do usuário não fornecido."]);
    }

}
?>