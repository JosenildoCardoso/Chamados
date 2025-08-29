<?php
include dirname(__DIR__, 2) . "\conexao.php";
include dirname(__DIR__, 2) . "\config.php";
header('Content-Type: application/json; charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
if($method == 'GET') {
// Pega a página atual (padrão = 0) e calcula offset
$pag = $_GET['pag'] ?? 0;
$limit = 20;
$offset = $pag * $limit;

$sql = "SELECT nome, dbuser.login, dbuser.matricula, atadb.acesso as codacesso, acat.descricao as acesso, un.descricao as setor FROM dados dbuser INNER JOIN adicionais_chamados atadb ON dbuser.cod = atadb.id_user LEFT JOIN setor un ON un.cod = dbuser.setor_user LEFT JOIN tipo_acesso_chamados acat ON acat.cod = atadb.acesso WHERE 1=1 ";


$sqlq = "SELECT COUNT(*) as total  FROM dados dbuser INNER JOIN adicionais_chamados atadb ON dbuser.cod = atadb.id_user  LEFT JOIN setor un ON un.cod = dbuser.setor_user LEFT JOIN tipo_acesso_chamados acat ON acat.cod = atadb.acesso WHERE 1=1";
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
        $stmt = $conn->prepare("DELETE FROM adicionais_$systemName WHERE matricula = ?");
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

}else if($method == 'POST') {
$input = file_get_contents("php://input");
$data = json_decode($input, true);

$stmt1 = $conn->prepare("SELECT COALESCE(MAX(matricula), 0) + 1 AS proximo_id FROM dados");
$stmt1->execute();
$result = $stmt1->get_result();
$mat = $result->fetch_assoc()['proximo_id'];

$nome = $data['nome'];
$email = $data['email'];
$cpf = $data['cpf'];
$login = $data['login'];
$setor = $data['setor'];
$subsetor = $data['subsetor'];
$funcao = $data['funcao'];
$acesso = $data['acesso'];
$areas = $data['area'];

$stlg = $conn->prepare("SELECT * FROM dados WHERE login = '$login'");
$stlg->execute();

if($stlg->num_rows){
      echo json_encode(["status" => "error", "message" => "Login já existe."]); 
}else{
   $stlg->close();
        $stmt = $conn->prepare("INSERT INTO dados (matricula, nome, email, cpf, login, funcao, setor_user, subsetor_user) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("isssssii", $mat, $nome, $email, $cpf, $login, $funcao, $setor, $subsetor);


        if ($stmt->execute()) {
           $id = $conn->insert_id;
             $stmtl = $conn->prepare("INSERT INTO adicionais_$systemName (id_user, acesso) VALUES (?,?)");
             $stmtl->bind_param("ii", $id, $acesso);
             $stmtl->execute();
 $stmtlc = $conn->prepare("INSERT INTO acesso_$systemName (id_user, acesso) VALUES (?,?)");
 $stmtlc->bind_param("ii", $id, $area_acesso);
             foreach($areas as $vlr){
                $area_acesso = $vlr;
           
             $stmtlc->execute();
             
            }

            echo json_encode(["status" => "success", "message" => "Usuário Adicionado."]);
        
            
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao Salvar."]);
    }
}

}else if($method == 'PUT'){
    $input = file_get_contents("php://input");
$data = json_decode($input, true);
       $id = $_GET['id'] ?? null;
        $stmt = $conn->prepare("UPDATE dados SET matricula=?, nome=?, email=?, cpf=?, login=?, funcao=?");
        $stmt->bind_param("isssss", $mat, $nome, $email, $cpf, $login, $funcao);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Usuário Adicionado."]);
        
            
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao Salvar."]);
    }
}
?>