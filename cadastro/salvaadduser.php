<?php
include("checklogin.php");
include("checkcaf.php");
$mat = mysqli_real_escape_string($conn, $_POST['matricula']);
$nome = mysqli_real_escape_string($conn, strtoupper($_POST['nome']));
$login = mysqli_real_escape_string($conn, strtolower($_POST['login']));
$setor = mysqli_real_escape_string($conn,$_POST['setor']);
$subsetor = mysqli_real_escape_string($conn,$_POST['subsetor']);
$funcao = mysqli_real_escape_string($conn,$_POST['funcao']);
$acessocad = $_POST['acesso'];
$cpf = mysqli_real_escape_string($conn,$_POST['cpf']);

$del = mysqli_query($conn, "DELETE FROM acesso_subunidade WHERE matricula = $mat");

if(isset($_POST['unidade'])){
	$unidade = mysqli_real_escape_string($conn,$_POST['unidade']);
	if($acessocad == 2 || $acessocad ==3){
	$gsub = mysqli_query($conn, "SELECT cod FROM subunidade WHERE tipo = $unidade GROUP BY cod");

	if(mysqli_num_rows($gsub)){
	
		while($lt = mysqli_fetch_array($gsub)){
			$cdsb = $lt['cod'];
			$insab = mysqli_query($conn, "INSERT INTO acesso_subunidade (matricula, farmacia, tipo_acesso) VALUES ($mat, $cdsb, 2)");
	

			
		}
	}
	
}
}else{
	$unidade = "NULL";
}

if(isset($_POST['farmacia'])){
	$subunidade = mysqli_real_escape_string($conn, $_POST['farmacia']);
}else{
	$subunidade = "NULL";
}

if(isset($_POST['presc'])){
$presc = mysqli_real_escape_string($conn,$_POST['presc']);;
}else{
	$presc = "NULL";
}



if($acessocad > $acesso){
	$acessocad = $acesso;
}
if(isset($_POST['asol'])){
	$asol = $_POST['asol'];
}else{
	$asol = "";
}

if(isset($_POST['aest'])){
	$aest = $_POST['aest'];
}else{
	$aest = "";
}

if(isset($_POST['comp'])){
	$comp = $_POST['comp'];
}else{
	$comp = "";
}

if(isset($_POST['sbunds'])){
	$sbs = $_POST['sbunds'];
}else{
	$sbs = "";
}




function limpaCPF($valor){
$valor = preg_replace('/[^0-9]/', '', $valor);
   return $valor;
}

$cpf = limpaCPF($cpf);


$del = mysqli_query($conn, "DELETE FROM acesso_sol WHERE matricula = $mat");
if($asol){
	$asol = $_POST['asol'];
foreach($asol as $ck => $vlr){
$insa = mysqli_query($conn, "INSERT INTO acesso_sol (matricula, acesso) VALUES ($mat, $vlr)");
}
}

$del = mysqli_query($conn, "DELETE FROM acesso_compras WHERE matricula = $mat");
if($acessocad == 9){
if($comp){
	$comp = $_POST['comp'];
foreach($comp as $ck => $vlr){
$insa = mysqli_query($conn, "INSERT INTO acesso_compras (matricula, acesso) VALUES ($mat, $vlr)");
}
}

}


if($sbs){
	$sbs = $_POST['sbunds'];
	$tpac = $_POST['tpacesso'];
foreach($sbs as $ck => $vlr){
	$tipoa = $tpac[$vlr];
$insa = mysqli_query($conn, "INSERT INTO acesso_subunidade (matricula, farmacia, tipo_acesso) VALUES ($mat, $vlr, $tipoa)");

}
}

if($acessocad >=13){
	$getc = mysqli_query($conn, "SELECT * FROM tipo_categorias WHERE ativo_est = 1");
	while($ls = mysqli_fetch_array($getc)){
		$cod = $ls['cod'];
		$insa = mysqli_query($conn, "INSERT INTO acesso_compras (matricula, acesso) VALUES ($mat, $cod)");
	}
	
}


$del = mysqli_query($conn, "DELETE FROM acesso_estoque WHERE matricula = $mat");
if($acessocad == 11 || $acessocad == 12){
if($aest){
	$aest = $_POST['aest'];
foreach($aest as $ck => $vlr){
$insa = mysqli_query($conn, "INSERT INTO acesso_estoque (matricula, acesso) VALUES ($mat, $vlr)");
}
}

}


$del = mysqli_query($conn, "DELETE FROM acesso_unidade WHERE matricula = $mat");
if($presc){
	$unds = $_POST['unds'];
foreach($unds as $ck => $vlr){
$insa = mysqli_query($conn, "INSERT INTO acesso_unidade (matricula, unidade) VALUES ($mat, $vlr)");
}
}

$email = $_POST['email'];
$ins = mysqli_query($conn, "INSERT INTO dados (matricula, nome, login, email, senha, cpf, funcao) VALUES ($mat, '$nome', '$login', '$email',  MD5('123456'), '$cpf', '$funcao') ON DUPLICATE KEY UPDATE nome='$nome', email='$email', login='$login', cpf='$cpf', funcao='$funcao'");


$per = mysqli_query($conn, "INSERT INTO adicionais (cod, acesso, unidade, subunidade, prescreve, setor, subsetor) VALUES ($mat, $acessocad, $unidade, $subunidade, $presc, $setor, $subsetor) ON DUPLICATE KEY UPDATE acesso=$acessocad, unidade=$unidade, prescreve=$presc, setor=$setor, subsetor=$subsetor, subunidade=$subunidade");


header("location:cad.php");




?>