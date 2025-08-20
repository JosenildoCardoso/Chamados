<?php
include("checklogin.php");
include("checkcaf.php");

if(isset($_GET['unidade']) && !empty($_GET['unidade'])){
	$cdu = mysqli_real_escape_string($conn, $_GET['unidade']);
	$un = " AND atadb.unidade = $cdu ";
}else{
$un = "";	
}

if(isset($_GET['acesso']) && !empty($_GET['acesso'])){
	$adu = mysqli_real_escape_string($conn, $_GET['acesso']);
	$ac = " AND atadb.acesso = $adu ";
}else{
$ac = "";	
}
$limit = 20;
$pg = mysqli_real_escape_string($conn, $_GET['pg']);
$npg = $pg * $limit;
$nome = mysqli_real_escape_string($conn, $_GET['nome']);
$qr = mysqli_query($conn, "SELECT nome, dbuser.login, dbuser.matricula, atadb.acesso as codacesso, acat.descricao as acesso, un.descricao as unidade FROM dados dbuser INNER JOIN adicionais atadb ON dbuser.matricula = atadb.cod  LEFT JOIN setor un ON un.cod = atadb.setor LEFT JOIN acessos acat ON acat.cod = atadb.acesso WHERE (dbuser.nome LIKE '%$nome%' OR dbuser.matricula LIKE '%$nome%') $un $ac AND status = 1 ORDER BY dbuser.nome ASC LIMIT $npg,$limit   ");


$qrt = mysqli_query($conn, "SELECT nome, dbuser.matricula, acat.descricao as acesso, un.descricao as unidade FROM dados dbuser INNER JOIN adicionais atadb ON dbuser.matricula = atadb.cod  LEFT JOIN setor un ON un.cod = atadb.setor LEFT JOIN acessos acat ON acat.cod = atadb.acesso WHERE (dbuser.nome LIKE '%$nome%' OR dbuser.matricula LIKE '%$nome%') $un $ac ");

$total = mysqli_num_rows($qrt);
if (mysqli_num_rows($qr)){
while ($ls = mysqli_fetch_array($qr)){
	$nome = $ls['nome'];
	$mat = $ls['matricula'];
	$unidade = $ls['unidade'];
	$acessocad = $ls['acesso'];
	$codacesso = $ls['codacesso'];
	$login = $ls['login'];
	
	
		echo "<div class='itemu'><span class='mat'>$mat</span><span class='inome'>$nome</span><span class='acs'>$login</span><span class='acs'>$acessocad</span><span class='und'>$unidade</span><span class='funcs'>";
	if(($codacesso <= $acesso)){
		if($codacesso == 9 && $acesso >= 13){
	echo "<a href='edituser.php?cod=$mat'><span class='edit_cad'></span></a><a onclick='excluir($mat)'><span class='removeu'></span></a><a onclick='resetar($mat)'><span class='reset_cad'></span></a>";
		}else if($codacesso > 0){
				echo "<a href='edituser.php?cod=$mat'><span class='edit_cad'></span></a><a onclick='excluir($mat)'><span class='removeu'></span></a><a onclick='resetar($mat)'><span class='reset_cad'></span></a>";	
		}else{
			echo "<a href='viewuser.php?cod=$mat'><span class='edit_cad'>V</span></a>";
		}
	}
		echo "</span></div>";
	
}

echo "<div class='pgatvs'>";
$tpg = ceil($total / $limit);
for ($n = 0; $n < $tpg; $n++){
	$y = $n + 1;
	if ($pg == $n){
	echo "<span class='pgna' onclick='getUsuarios($n);'><b>$y</b></span>";
	}else{
	echo "<span class='pgna' onclick='getUsuarios($n);'>$y</span>";	
	}
	
}
echo "</div>";
echo "<div class='totalbar'><span>Total: $total</span></div>";
}else{
	
		echo "<div class='sres'>Sem Resultados</div>";
}

?>