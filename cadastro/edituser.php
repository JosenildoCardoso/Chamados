<?php
include("checklogin.php");
include("checkcaf.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/style.css"/>
<link rel="stylesheet" href="css/cad.css"/>
<title>Editar Usuário</title>
</head>

<body onLoad="doOnLoad()">
<?php
	
	
include("mask.php");
if (isset($_GET['cod'])){
	$cd = mysqli_real_escape_string($conn, $_GET['cod']);
	$gu = mysqli_query($conn, "SELECT dbuser.matricula, dbata.setor, subunidade, dbata.subsetor, dbata.prescreve, dbuser.nome, funcao, cpf, login, email, acesso, dbata.unidade FROM dados dbuser INNER JOIN adicionais dbata ON dbata.cod = dbuser.matricula WHERE dbata.cod = $cd");


	if (mysqli_num_rows($gu)){
	$gd = mysqli_fetch_array($gu);
	$mat = $gd['matricula'];
	$nome = $gd['nome'];
	$email = $gd['email'];
	$logincad = $gd['login'];
	$acessocad = $gd['acesso'];
	$cpf = mask($gd['cpf'], '###.###.###-##');
	$unidade = $gd['unidade'];
	$subunidade = $gd['subunidade'];
	$funcao = $gd['funcao'];
	$presc = $gd['prescreve'];
	$setor = $gd['setor'];
	$subsetor = $gd['subsetor'];
		
	if($acessocad > $acesso){
		header("location:cad.php");	
		exit;
	}
	

}else{
	
header("location:cad.php");
exit;
}
}

include("header.php");
?>
<div class="corpo">
<div class="conteudo">
<div class='barnav'><a class='itnav' href='index.php'>Inicio</a><span class='sep'>></span><a class='itnav' href='config.php'>Cadastro e Configurações</a><span class='sep'>></span><a class='itnav' href='cad.php'>Cadastro de Usuários</a><span class='sep'>></span><span class='itnavds'>Editar Usuário</span></div>
<span class="titc"><a href='cad.php' class='btback mg0'></a><span class="tit mg0 fxb100">Editar Usuários</span></span>
<form id="frs" method="post" class='fxb100 dpfxwp pd30 bkcazulfundo' action="salvaadduser.php">
	<div class='dpfx fxb100 gap10'>
<div class="itform fxb10">
<label>Registro*</label>
<input autocomplete="off" readonly="readonly" value='<?php echo $mat ?>'  onkeypress="return SomenteNumero(event)"  id="mat" name="matricula" type="text"/>
</div>
<div class="itform fxb40">
<label>Nome*</label>
<input autocomplete="off" value="<?php echo $nome; ?>" id="nome" name="nome" type="text"/>
</div>
<div class="itform fxb12">
<label>CPF</label>
<input autocomplete="off" id="cpf" value="<?php echo $cpf; ?>" maxlength="11" onblur="CPF(value)" name="cpf" type="text"/>
</div>
<div class="itform">
<label>Funcao</label>
<input id="funcao"  value="<?php echo $funcao; ?>" name="funcao" type="text"/>
</div>
<div class="itform fxb18">
<label>Login*</label>
<input autocomplete="off" data-ant="<?php echo $logincad; ?>" value="<?php echo $logincad; ?>" id="login" onblur="getLoginCad(this)" name="login" type="text"/>
</div>
	</div>
<div class="fxb100 gap10 dpfx mgt20">
<div class="itform fxb25">
<label>E-mail</label>
<input id="email" value="<?php echo $email; ?>"  name="email" type="text"/>
</div>

	<div class="itform fxb25">
<label>Setor</label>
<select id="setor" name="setor" onChange="getDep()">
<?php
$uns = mysqli_query($conn, "SELECT cod, descricao FROM setor ORDER BY descricao ASC");
while ($ls = mysqli_fetch_array($uns)){
	$cod = $ls['cod'];
	$desc = $ls['descricao'];

	if($setor == $cod){
	echo "<option selected value='$cod'>$desc</option>";		
	}else{
echo "<option value='$cod'>$desc</option>";	
	}


}
?>
</select>
</div>
		<div class="itform fxb30">
<label>Sub-Setor</label>
<select id="subsetor" name="subsetor">
	<?php
$unsv = mysqli_query($conn, "SELECT cod, descricao FROM subsetor WHERE tipo = $setor ORDER BY descricao ASC");
while ($lsv = mysqli_fetch_array($unsv)){
	$cod = $lsv['cod'];
	$desc = $lsv['descricao'];

	if($subsetor == $cod){
	echo "<option selected value='$cod'>$desc</option>";		
	}else{
echo "<option value='$cod'>$desc</option>";	
	}


}
	?>
</select>
</div>
	<div class="itform fxb20">
<label>Acesso</label>
<select id="acesso" name="acesso" onChange="cAcesso()" >
<?php
	if($acesso < $_ac_administrador_geral){
$sets = mysqli_query($conn, "SELECT cod, descricao FROM acessos WHERE cod <= $acesso AND cod != $_ac_comprador ORDER BY cod ASC");
	}else{
	$sets = mysqli_query($conn, "SELECT cod, descricao FROM acessos WHERE cod <= $acesso  ORDER BY cod ASC");	
	}
while ($ls = mysqli_fetch_array($sets)){
	$cod = $ls['cod'];
	$desc = $ls['descricao'];
	if ($cod == $acessocad){
	echo "<option selected value='$cod'>$desc</option>";	
	}else{
echo "<option value='$cod'>$desc</option>";	
}
}
?>
</select>
</div>
<div class="itform fxb20">
<label>Prescritor</label>
<select id="presc" name="presc" onchange="change(value)">
<?php
if($presc == 1){
echo "<option value='0'>Não</option>
<option selected value='1'>Sim</option>";
}else if($presc == 0){
	echo "<option selected value='0'>Não</option>
<option value='1'>Sim</option>";
}else{
echo "<option value='1'>Sim</option>
<option selected value='0'>Não</option>
";	
}
?>
</select>
</div>
</div>
	
<div id="gpfarm" class="grupofarmacia fxb100 dpfxwp gap10 mgt10" style="display: none">
<div class="itform fxb30">
<label>Unidade</label>
<select id="unidade" name="unidade" onchange='getFarm()' >
<?php
$uns = mysqli_query($conn, "SELECT cod, descricao FROM unidades ORDER BY cod ASC");
while ($ls = mysqli_fetch_array($uns)){
	$cod = $ls['cod'];
	$desc = $ls['descricao'];

	if ($cod == $unidade){
	echo "<option selected value='$cod'>$desc</option>";	
	}else{
echo "<option value='$cod'>$desc</option>";	
}

}
?>
</select>
</div>
	
	
<div id="gpsubfarm" class='gpund fxb100'>
	<span class='titd' >Acesso para Farmácias</span>
<div id="gpsubs" class='fxb100 dpfxwp gap10'>

</div>
</div>
	</div>

	
	
	<div id="gpunid" class='gpund fxb100'>
	<span class='titd' >Unidades onde Prescreve</span>
<div class='fxb100 dpfxwp gap10'>
<?php
$sl = mysqli_query($conn, "SELECT * FROM unidades LEFT JOIN acesso_unidade ac ON ac.unidade = unidades.cod AND matricula = $mat WHERE status_inv != 0 ORDER BY descricao ASC");
while($lu = mysqli_fetch_array($sl)){
$cod = $lu['cod'];
$desc = $lu['descricao'];
$dund = $lu['unidade'];
if($dund){
$ck = "checked";	
}else{
$ck = "";	
}
echo "<span class='itck'><input id='ck-$cod' type='checkbox' $ck name='unds[]' value='$cod' /><label for='ck-$cod'>$desc</label></span>";	
}
?>
</div>
</div>
		
	<div id="gpsol" class='gpund fxb100' style="display: flex">
		<span class='titd' >Área de Acesso para Requisições</span>
<div class="dpfxwp fxb100 gap10">
<?php
	
$sl = mysqli_query($conn, "SELECT * FROM tipo_categorias LEFT JOIN acesso_sol ac ON ac.acesso = tipo_categorias.cod AND matricula = $mat WHERE ativo_sol = 1  ORDER BY descricao ASC");
	
while($lu = mysqli_fetch_array($sl)){
$cod = $lu['cod'];
$desc = $lu['descricao'];
$dund = $lu['acesso'];
if($dund){
$ck = "checked";	
}else{
$ck = "";	
}
echo "<span class='itck'><input id='cks-$cod' type='checkbox' $ck name='asol[]' value='$cod' /><label for='cks-$cod'>$desc</label></span>";	
}
?>
</div>
</div>
	
	<div id="gpest" class='gpund fxb100' style="display: flex">
		<span class='titd' >Área de Administração</span>
<div class="dpfxwp fxb100 gap10" >
<?php
$sl = mysqli_query($conn, "SELECT * FROM tipo_categorias LEFT JOIN acesso_estoque ac ON ac.acesso = tipo_categorias.cod AND matricula = $mat WHERE ativo_est = 1 ORDER BY descricao  ASC");
while($lu = mysqli_fetch_array($sl)){
$cod = $lu['cod'];
$desc = $lu['descricao'];
$dund = $lu['acesso'];
if($dund){
$ck = "checked";	
}else{
$ck = "";	
}
echo "<span class='itck'><input id='cke-$cod' type='checkbox' $ck name='aest[]' value='$cod' /><label for='cke-$cod'>$desc</label></span>";
}
?>
</div>
</div>
	
		<div id="gpcomp" class='gpund fxb100' style="display: none">
			<span class='titd'>Permissão de Acesso para Comprador</span>
<div class="dpfxwp fxb100 gap10">
<?php

	
	$sl = mysqli_query($conn, "SELECT * FROM tipo_categorias LEFT JOIN acesso_compras ac ON ac.acesso = tipo_categorias.cod AND matricula = $mat WHERE ativo_comp = 1  ORDER BY descricao ASC");
	
while($lu = mysqli_fetch_array($sl)){
$cod = $lu['cod'];
$desc = $lu['descricao'];
$dund = $lu['acesso'];
if($dund){
$ck = "checked";	
}else{
$ck = "";	
}
echo "<span class='itck'><input id='ckc-$cod' type='checkbox' $ck name='comp[]' value='$cod' /><label for='ckc-$cod'>$desc</label></span>";	
}
?>
</div>
</div>
<div class="gpbotoes">
<span id="bts" onclick="valida()" class="btdefaze pd10 mg0 fxb150p">Salvar</span><a class="btdefvme pd10 mg0 fxb150p" href="cad.php">Cancelar</a>
</div>
</div>
</form>

</div>
<?php
include("rodape.php");
?>
</body>
<script src="js/reqajax.js"></script>
<script src="js/funcgerais.js?v=2.39"></script>
<script src="js/cad.js?v=2.42"></script>
<script>
var matuser = <?php echo $mat; ?>;
	
	function doOnLoad(){
		cAcesso();
	change();
		getFarm();
	}
	

</script>
</html>