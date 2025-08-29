<?php 
include("../conexao.php");


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<title>Usuários</title>
</head>
<script>


</script>
<style>


</style>
<script>

</script>
<body>
<?php
include("../header.php");
?>
<div class="container">
<div class="container mt-5">
	<h2>Usuários</h2>

<div class="users">
<div class="row">
	<div class="col-4">
<input class="busca form-control" placeholder="Buscar Usuário/Matrícula" onkeyup="getUsers()" type="text"  />
	</div><div class="col-4">
	<select  onchange="getUsers()" class="unidade form-select"><?php
$uns = mysqli_query($conn, "SELECT cod, descricao FROM setor ORDER BY cod ASC");
echo "<option value=''>Filtrar Setor</option>";	
while ($ls = mysqli_fetch_array($uns)){
	$cod = $ls['cod'];
	$desc = $ls['descricao'];

echo "<option value='$cod'>$desc</option>";	

}
?></select>
	</div><div class="col-2">
		<select  onchange="getUsers()" class="tpacesso form-select"><?php
$uns = mysqli_query($conn, "SELECT cod, descricao FROM tipo_acesso_chamados ORDER BY cod ASC");
echo "<option value=''>Filtrar Acesso</option>";	
while ($ls = mysqli_fetch_array($uns)){
	$cod = $ls['cod'];
	$desc = $ls['descricao'];

echo "<option value='$cod'>$desc</option>";	

}
?></select>
	</div><div class="col-2">
	<a href="adduser.php" class="btnovo btn btn-success mb-3">Adicionar Novo</a>
	</div>
</div>
<div class='row bg-secondary' style="color:#ccc;"><span class='col-1'>Matrícula</span><span class='col-4'>Nome</span><span class="col-2">Login</span><span class="col-2">Acesso</span><span class="col-2">Setor</span><span class="col-1"></span></div>
<div id="listauser" class="lista">

</div>


</div>
</div>

</div>
<div class="rodape">
</div>
</body>
<script>


	  const form = {
	  unidade:document.querySelector('.unidade'),
      acesso:document.querySelector('.tpacesso'),
	  busca:document.querySelector('.busca')
};

	window.addEventListener('load', function() {
    getUsers();
	
});

function getUsers(pag=0){
 
fetch('../api/usuarios/' + '?unidade=' + form.unidade.value + '&acesso=' + form.acesso.value + '&nome=' + form.busca.value + '&pag=' + pag)
  .then(response => response.json())
  .then(dados => {
    const lista = document.querySelector('.lista');
    lista.innerHTML = ''; // limpa antes de inserir
if(dados.total != 0){
    dados.dados.forEach(usuario => {
      const item = document.createElement('div');
      item.classList.add('row');
      item.className = "border-bottom row mt-1";
      item.innerHTML = `
        <span class="col-1 bg-light text-center">${usuario.matricula}</span>
        <span class="col-4">${usuario.nome}</span>
        <span class="col-2 bg-light">${usuario.login}</span>
        <span class="col-2">${usuario.acesso}</span>
        <span class="col-2 bg-light text-truncate">${usuario.setor}</span>
        <span class="col-1 gap-1 d-flex">` + `<a class='bi bi-pencil-square' href='editar.php?id=${usuario.matricula}'></a>` + `<button class='btremove border-0 bi bi-trash3-fill' data-id='${usuario.matricula}'></button>` + `<button class='btreset bi bi-arrow-clockwise border-0' data-id='${usuario.matricula}'></button>` + `</span>`;
       

      lista.appendChild(item);
    });

}
  })
  .catch(erro => console.error('Erro ao buscar usuários:', erro));
}


document.querySelector('.lista').addEventListener("click", (e) => {
  if(e.target.classList.contains("btremove")) {
    const id = e.target.dataset.id;
    removeUser(id); 
  }
});

document.querySelector('.lista').addEventListener("click", (e) => {
  if(e.target.classList.contains("btreset")) {
    const id = e.target.dataset.id;
    resetPass(id); 
  }
});


function removeUser(id) {
  if (confirm("Tem certeza que deseja excluir este usuário?")) {
    fetch('../api/usuarios/' + id, {
      method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
      if (data.status == 'success') {
        alert("Usuário excluído com sucesso!");
        getUsers(); // Atualiza a lista de usuários
      } else {
        alert("Erro ao excluir usuário: " + data.message);
      }
    })
    .catch(error => console.error('Erro ao excluir usuário:', error));
  }
}

function removeUser(id) {
  if (confirm("Tem certeza que deseja excluir este usuário?")) {
    fetch('../api/usuarios/' + id, {
      method: 'DELETE'
    })
    .then(response => response.json())
    .then(data => {
      if (data.status == 'success') {
        alert("Usuário excluído com sucesso!");
        getUsers(); // Atualiza a lista de usuários
      } else {
        alert("Erro ao excluir usuário: " + data.message);
      }
    })
    .catch(error => console.error('Erro ao excluir usuário:', error));
  }
}
function resetPass(id) {
  if (confirm("Tem certeza que deseja resetar a senha deste usuário?")) {
    fetch('../api/usuarios/' + id + '/reset', {
      method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
      if (data.status == 'success') {
        alert("Senha resetada com sucesso!");
        getUsers(); // Atualiza a lista de usuários
      } else {
        alert("Erro ao resetar senha: " + data.message);
      }
    })
    .catch(error => console.error('Erro ao resetar senha:', error));
  }
}

// Seleciona todos os botões de "mostrar"


</script>

</html>