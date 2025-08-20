<?php
session_start();
include 'conexao.php';
include("jwt.php");
$jw = new MyJWT();
$secret = "Fut8xa12h@Dnstuff0609Yft5fbh";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM dados WHERE login = '$login' AND senha = '" . md5($senha) . "'";
    $result = $conn->query($sql);
  
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $mat = $row['matricula'];
		$nome = $row['nome'];
        $_SESSION['usuario'] = $login;
        $_SESSION['matricula'] = $mat;
		

		setcookie("token", $jwt, [
    'expires' => time() + 3600,
    'path' => '/',
    'domain' => '', // ou seu domínio
    'secure' => true,         // só via HTTPS
    'httponly' => true,       // JavaScript não pode acessar
    'samesite' => 'Strict'    // evita CSRF
]);


        $res = $conn->query("SELECT acesso FROM adicionais_chamados WHERE matricula = '$mat'");
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $_SESSION['acesso'] = $row['acesso'];
            header("Location: " . ($row['acesso'] == '2' ? "ti/chamados.php" : "ti/ver_chamados.php"));
        } else {
            $_SESSION['acesso'] = '1';
            header("Location: index.php");
        }

		$data = date('Y-m-d H:i');


		$payload = [
	"matricula" => $mat,
	"name" => $nome,
    "role" => $_SESSION['acesso'],
	"iat" => strtotime($data),
	"exp" => strtotime($data) + 36000, // Token válido por 1 hora
	"ip" => $_SERVER['REMOTE_ADDR'],
	"login" => $login
	
];
		$tk =  MyJWT::encode($payload, $secret);

		setcookie("token", $tk, [
    'expires' => time() + 3600,
    'path' => '/',
    'domain' => '', // ou seu domínio
    'secure' => true,         // só via HTTPS
    'httponly' => true,       // JavaScript não pode acessar
    'samesite' => 'Strict'    // evita CSRF
]);
    } else {
		header("Location: login.php?erro=1");
        
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<title>Login</title>
<link rel="stylesheet" href="css/login.css"/>
</head>

<body>

<div class="fullscreen">
<form method="post">
<div class="box">
<span class='tits'>Login</span>

<div class='gplg'>
<input autocomplete="off" id="login" name="login" placeholder="Usuário" class="campotxt iusrtxt" type="text" />
</div>
<div class='gplg'>
<input autocomplete="off" id="senha" name="senha" placeholder="Senha" onkeydown="enviar(event)" type="password" class="campotxt ipasstxt"/>
</div>
<button type="submit" class="btlogin">Entrar</button>

<span id="retorno" class='ret'>
	<?php
	if(isset($_GET['erro'])){
		echo "Usuário ou Senha incorretos!";

	}
	?>
</span>
<span class='gpimg'><img class='imglogo' src="img/imglogin.png"/></span>
</div>
</form>
</div>

</body>


</html>