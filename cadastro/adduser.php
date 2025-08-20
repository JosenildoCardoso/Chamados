<?php
include("checklogin.php");
include("checkcaf.php");
$gtm = mysqli_query($conn, "SELECT MAX(matricula)+1 as mat FROM dados");
$ff = mysqli_fetch_array($gtm);
$mat = $ff['mat'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/style.css"/>
<link rel="stylesheet" href="css/cad.css"/>
<title>Adicionar Usuário</title>
</head>


<body onLoad="doOnLoad()">
<?php
include("header.php");
?>
<div class="corpo">
<div class="conteudo">
			<div class='barnav'><a class='itnav' href='index.php'>Inicio</a><span class='sep'>></span><a class='itnav' href='config.php'>Cadastro e Configurações</a><span class='sep'>></span><a class='itnav' href='cad.php'>Cadastro de Usuários</a><span class='sep'>></span><span class='itnavds'>Adicionar Usuário</span></div>
<span class="titc"><a href='cad.php' class='btback mg0'></a><span class="tit mg0 fxb100">Adicionar Novo</span></span>
<form id="frs" method="post" class='dpfxwp pd30 bkcazulfundo fxb100' action="salvaadduser.php">
	<div class='dpfx fxb100 gap10'>
<div class="itform fxb10">
<label>Registro*</label>
<input autocomplete="off" readonly="readonly" value='<?php echo $mat ?>'  onkeypress="return SomenteNumero(event)"  class="mg0"  id="mat" name="matricula" type="text"/>
</div>
<div class="itform fxb40">
<label>Nome*</label>
<input autocomplete="off" id="nome" name="nome" type="text"/>
</div>
<div class="itform fxb15">
<label>CPF</label>
<input autocomplete="off" id="cpf" maxlength="11" onblur="CPF(value)" name="cpf" type="text"/>
</div>
<div class="itform">
<label>Funcao</label>
<input id="funcao"  name="funcao" type="text"/>
</div>
<div class="itform fxb15">
<label>Login*</label>
<input autocomplete="off"  data-ant="" id="login" onblur="getLoginCad(this)"  name="login" type="text"/>
</div>
	</div>
<div class="fxb100 gap10 dpfx mgt20" >
<div class="itform fxb25">
<label>E-mail</label>
<input id="email" name="email" type="text"/>
</div>
<div class="itform fxb25">
<label>Setor</label>
<select id="setor" name="setor" onChange="getDep()">
<?php
$uns = mysqli_query($conn, "SELECT cod, descricao FROM setor ORDER BY cod ASC");
while ($ls = mysqli_fetch_array($uns)){
	$cod = $ls['cod'];
	$desc = $ls['descricao'];

echo "<option value='$cod'>$desc</option>";	

}
?>
</select>
</div>
		<div class="itform fxb30">
<label>Sub-Setor</label>
<select id="subsetor" name="subsetor">

</select>
</div>
	<div class="itform fxb20">
<label>Acesso</label>
<select id="acesso" name="acesso" onChange="cAcesso()">
<?php
	if($acesso < $_ac_administrador_geral){
$sets = mysqli_query($conn, "SELECT cod, descricao FROM acessos WHERE cod <= $acesso AND cod != $_ac_comprador  ORDER BY cod ASC");
	}else{
	$sets = mysqli_query($conn, "SELECT cod, descricao FROM acessos WHERE cod <= $acesso ORDER BY cod ASC");	
	}
while ($ls = mysqli_fetch_array($sets)){
	$cod = $ls['cod'];
	$desc = $ls['descricao'];

echo "<option value='$cod'>$desc</option>";	

}
?>
</select>
</div>
<div class="itform fxb8">
<label>Prescritor</label>
<select id="presc" name="presc" onchange="change(value)" >
<option value="0">Não</option>
<option value="1">Sim</option>
</select>
</div>
</div>

	<div id="gpfarm" class="grupofarmacia fxb100 dpfxwp gap10" style="display: none">
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
	
	
	<div id="gpunid" class='gpund fxb100' style="display: none">
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
	
	<div id="gpsol" class='gpund fxb100 dpfx'>
		<span class='titd' >Área de Acesso para Requisições</span>
<div class="fxb100 dpfxwp gap10">
<?php
$sl = mysqli_query($conn, "SELECT * FROM tipo_categorias WHERE ativo_sol = 1 ORDER BY descricao  ASC");
while($lu = mysqli_fetch_array($sl)){
$cod = $lu['cod'];
$desc = $lu['descricao'];
echo "<span class='itck'><input id='cks-$cod' type='checkbox' name='asol[]' value='$cod' /><label for='cks-$cod'>$desc</label></span>";
}
?>
</div>
</div>
	
	
	<div id="gpest" class='gpund fxb100 dpfx' >
		<span class='titd' >Área de Administração</span>
<div class='fxb100 dpfxwp gap10'>
<?php
$sl = mysqli_query($conn, "SELECT * FROM tipo_categorias WHERE ativo_est = 1 ORDER BY descricao  ASC");
while($lu = mysqli_fetch_array($sl)){
$cod = $lu['cod'];
$desc = $lu['descricao'];
echo "<span class='itck'><input id='cke-$cod' type='checkbox' name='aest[]' value='$cod' /><label for='cke-$cod'>$desc</label></span>";
}
?>
</div>
</div>
	
		<div id="gpcomp" class='gpund fxb100' style="display: none">
			<span class='titd'>Permissão de Acesso para Comprador</span>
<div class='fxb100 dpfxwp gap10'>
<?php
$sl = mysqli_query($conn, "SELECT * FROM tipo_categorias WHERE ativo_comp = 1 ORDER BY descricao  ASC");
while($lu = mysqli_fetch_array($sl)){
$cod = $lu['cod'];
$desc = $lu['descricao'];
echo "<span class='itck'><input id='ckc-$cod' type='checkbox' name='comp[]' value='$cod' /><label for='ckc-$cod'>$desc</label></span>";
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
<script src="js/cad.js?v=2.39"></script>
<script>
	var matuser = 0;
	function doOnLoad(){
		cAcesso();
		getDep();
		
	}
	
 

</script>
</html>