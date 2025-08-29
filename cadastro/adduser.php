<?php
include("../conexao.php");
include("../config.php");
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
    <li class="breadcrumb-item"><a href="../pageconfig.php">Configurações</a></li>
    <li class="breadcrumb-item"><a href="cad.php">Cadastro de Usuários</a></li>
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
<input class="form-control nome" required autocomplete="off" class="nome" type="text"/>
</div>
<div class="col-2">
<label class='form-label'>CPF</label>
<input autocomplete="off" class='form-control cpf' maxlength="11" type="text"/>
</div>
<div class="col-3">
<label class='form-label'>Função</label>
<input id="funcao" class="form-control funcao"  type="text"/>
</div>
<div class="col-2">
<label class='form-label'>Login*</label>
<input autocomplete="off" class='form-control login' required  data-ant="" onblur="getLoginCad(this)"  type="text"/>
</div>
	</div>

<div class="row mt-3" >
<div class="col-3">
<label class='form-label'>E-mail</label>
<input type="email" class='form-control email'/>
</div>
<div class="col-4">
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
<select  class="form-select acesso">
<?php

	$sets = mysqli_query($conn, "SELECT cod, descricao FROM tipo_acesso_$systemName ORDER BY cod ASC");	

while ($ls = mysqli_fetch_array($sets)){
	$cod = $ls['cod'];
	$desc = $ls['descricao'];

echo "<option value='$cod'>$desc</option>";	

}
?>
</select>
	
</div>
</div>


<div class='gparea row mt-3 d-none' >
<span class='form-label' >Área de Atuação</span>
<div class='col-6 gap-3 d-flex'>
<?php
$sl = mysqli_query($conn, "SELECT cod, descricao FROM areas_$systemName ORDER BY cod ASC");
while($lu = mysqli_fetch_array($sl)){
$cod = $lu['cod'];
$desc = $lu['descricao'];
echo "<span class='col-3 mr-3 d-flex gap-2'><input class='ckarea' type='checkbox' name='aest[]' value='$cod' /><label for='cke-$cod'>$desc</label></span>";
}
?>
</div>
</div>
	


</div>
<div class="col-12 mt-5 gap-2 d-flex justify-content-center">
<button type="submit" class="btn btn-primary btn-lg btsend">Salvar</button><button class="btn btn-lg btn-danger" type="reset">Limpar</button><a class="btn btn-lg btn-secondary " href="cad.php">Cancelar</a>
</div>
</form>



</div>
</div>

</body>
<script src="../js/utils.js?v=3665"></script>
<script>
let atuacao = document.querySelector('.gparea');
document.querySelector('.acesso').addEventListener("change", (e) => {
if(e.target.value > 1){
	atuacao.classList.remove("d-none");
	
}else{
	atuacao.classList.add("d-none");
}
})


const formulario = document.querySelector("form");
formulario.addEventListener("submit", function(e) {
  e.preventDefault(); // evita envio normal

  const forml = e.target;
  // força validação do HTML5
  if (!forml.checkValidity()) {
    forml.reportValidity(); // mostra mensagens nativas
    return; // não prossegue se inválido
  }else{
	salvaUser();
  }
}
)
	

	document.querySelector(".cpf").addEventListener("blur", function(event){
	event.currentTarget.value = CPF(event.currentTarget.value);

	});

const campos = {
	setor:document.querySelector(".setor"),
	subsetor:document.querySelector(".subsetor")
}

	function salvaUser(){


	let ds = confirm("Salvar Usuário?");
	if(ds){	
	let form = {
    nome:document.querySelector(".nome").value,
	cpf:document.querySelector(".cpf").value,
	login:document.querySelector(".login").value,
	funcao:document.querySelector(".funcao").value,
	setor:document.querySelector(".setor").value,
	subsetor:document.querySelector(".subsetor").value,
	acesso:document.querySelector(".acesso").value,
	email:document.querySelector(".email").value,
	area:Array.from(document.querySelectorAll(".ckarea")).filter(en => en.checked).map(en => en.value)

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
			alert(dados.message);
			window.location.href = "cad.php";
		})

	}


 



	}

	window.addEventListener("load", function(){
getDep();

	})

		function getDep(){
fetch("../api/setores/subsetores/" + campos.setor.value,{
headers: {                    // cabeçalhos da requisição
    "Content-Type": "application/json"

	}})
.then(response => response.json())
.then(payload => {
campos.subsetor.innerHTML = "";
campos.subsetor.innerHTML = payload.dados.map(inf => `<option value='${inf.cod}'>${inf.descricao}</option>`).join("");

})


	}

</script>
</html>