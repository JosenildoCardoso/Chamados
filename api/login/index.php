<?php
include dirname(__DIR__, 2) . "\conexao.php";
include dirname(__DIR__, 2) . "\jwt.php";
header('Content-Type: application/json; charset=utf-8');

$jw = new MyJWT();
$secret = "Fut8xa12h@Dnstuff0609Yft5fbh";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $senha = $_POST['senha'];

$stmt = $conn->prepare("SELECT d.login, d.matricula, d.nome, ad.acesso 
                        FROM dados d 
                        INNER JOIN adicionais_chamados ad ON ad.matricula = d.matricula 
                        WHERE login = ? AND senha = ?");
$hash = md5($senha);
$stmt->bind_param("ss", $login, $hash);
$stmt->execute();
$result = $stmt->get_result();

  
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $mat = $row['matricula'];
		$nome = $row['nome'];
        $acesso = $row['acesso'];
     
     

		$data = date('Y-m-d H:i');


        	$payload = [
	"matricula" => $mat,
	"name" => $nome,
    "role" => $acesso,
	"iat" => strtotime($data),
	"exp" => strtotime($data) + 36000, // Token válido por 1 hora
	"ip" => $_SERVER['REMOTE_ADDR'],
	"login" => $login
	
];
		$tk =  MyJWT::encode($payload, $secret);

		setcookie("token", $tk, [
    'expires' => time() + 36000,
    'path' => '/',
    'domain' => '', // ou seu domínio
    'secure' => true,         // só via HTTPS
    'httponly' => true,       // JavaScript não pode acessar
    'samesite' => 'Strict'    // evita CSRF

    
       
	
]);
  echo json_encode(array("status" => "success", "message" => "Login com Sucesso", "token" => $tk, "matricula" => $mat, "name" => $nome, "role" => $acesso));
  exit;
    } else {
	
        echo json_encode(array("status" => "error", "message" => "Usuário ou senha inválidos"));
        exit;
        
    }
}

?>