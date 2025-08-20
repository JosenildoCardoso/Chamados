<?php
include("../conexao.php");
$gtm = mysqli_query($conn, "SELECT MAX(matricula)+1 as mat FROM dados");
$ff = mysqli_fetch_array($gtm);
$mat = $ff['mat'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Adicionar Usuário</title>
</head>


<body>
<?php
include("../header.php");
?>
<div class="container">
<div class="container mt-5">
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Configurações</a></li>
    <li class="breadcrumb-item"><a href="#">Cadastro de Usuários</a></li>
    <li class="breadcrumb-item active" aria-current="page">Adicionar Novo</li>
  </ol>
</nav>
	<h2>Usuários</h2>
<form>
	<div class='row'>
<div class="col-1">
<label class='form-label'>Registro</label>
<input readonly="readonly" value='<?php echo $mat ?>'  class="form-control"  type="text"/>
</div>
<div class="col-4">
<label class='form-label'>Nome*</label>
<input class="w-100 form-control" autocomplete="off" class="nome" type="text"/>
</div>
<div class="col-2">
<label class='form-label'>CPF</label>
<input autocomplete="off" class='form-control cpf' maxlength="11" type="text"/>
</div>
<div class="col-3">
<label class='form-label'>Funcao</label>
<input id="funcao" class="form-control"  class="funcao" type="text"/>
</div>
<div class="col-2">
<label class='form-label'>Login*</label>
<input autocomplete="off" class='form-control'  data-ant="" onblur="getLoginCad(this)"  class="login" type="text"/>
</div>
	</div>

<div class="row" >
<div class="col-4">
<label class='form-label'>E-mail</label>
<input type="text" class='form-control email'/>
</div>
<div class="col-3">
<label class='form-label'>Setor</label>
<select onChange="getDep()" class='form-select setor'>
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
		<div class="col-3">
<label class='form-label'>Sub-Setor</label>
<select class='form-select subsetor'>

</select>
</div>
	<div class="col-2">
<label class='form-label'>Acesso</label>
<select id="acesso" name="acesso" onChange="cAcesso()" class="form-select">
<?php

	$sets = mysqli_query($conn, "SELECT cod, descricao FROM acessos_chamados ORDER BY cod ASC");	

while ($ls = mysqli_fetch_array($sets)){
	$cod = $ls['cod'];
	$desc = $ls['descricao'];

echo "<option value='$cod'>$desc</option>";	

}
?>
</select>
	
</div>
</div>


	<div class='row' >
		<span class='form-label' >Área de Administração</span>
<div class='col-6'>
<?php
$sl = mysqli_query($conn, "SELECT cod, descricao FROM areas_chamados ORDER BY cod ASC");
while($lu = mysqli_fetch_array($sl)){
$cod = $lu['cod'];
$desc = $lu['descricao'];
echo "<span class='itck'><input id='cke-$cod' type='checkbox' name='aest[]' value='$cod' /><label for='cke-$cod'>$desc</label></span>";
}
?>
</div>
</div>
	

<div class="gpbotoes">
<button id="bts" onclick="valida()" class="btn btn-danger">Salvar</button><a class="btn " href="cad.php">Cancelar</a>
</div>
</div>
</form>

</div>
</div>

</body>
<script src="../js/utils.js?v=3665"></script>
<script>
	

	document.querySelector(".cpf").addEventListener("blur", function(event){
	event.currentTarget.value = CPF(event.currentTarget.value);

	});

const campos = {
	setor:document.querySelector(".setor"),
	subsetor:document.querySelector(".subsetor")
}

	function salvaUser(){

		
	let form = {
    nome:document.querySelector(".nome").value,
	cpf:document.querySelector(".cpf").value,
	login:document.querySelector(".login").value,
	funcao:document.querySelector(".funcao").value,
	setor:document.querySelector(".setor").value,
	subsetor:document.querySelector(".subsetor").value,
	acesso:document.querySelector(".acesso").value

	}
		fetch("../api/usuarios/",{
			method:'POST',
			headers: {                    // cabeçalhos da requisição
    "Content-Type": "application/json"
  },
  body: JSON.stringify(form)   // corpo da requisição (payload)
	})
		.then(response => response.json())
		.then(dados => {
			dados.status
		})

	}
 

	function getDep(){
fetch("../api/setores/subsetores/" + campos.setor.value,{
headers: {                    // cabeçalhos da requisição
    "Content-Type": "application/json"

	}})
.then(response => response.json())
.then(payload => {
campos.subsetor.innerHTML = "";
payload.dados.forEach(element => {
	console.log("RECEBENDO");
	campos.subsetor.innerHTML += `<option value='${element.cod}'>${element.descricao}</option>`;
	
});

})




	}

</script>
</html>