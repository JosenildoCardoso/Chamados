<?php
session_start();
include 'conexao.php';
include 'autentica.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $unidade = $_POST['unidade'];
    $local = $_POST['local'];
    $area = $_POST['area'];
    $usuario_id = $_SESSION['matricula'];
    

    $stmt = $conn->prepare("INSERT INTO chamados (titulo, descricao_problema, data_abertura, matricula, setor, subsetor, area) VALUES (?, ?, NOW(), ?, ?,?,?)");
    $stmt->bind_param("sssiii", $titulo, $descricao, $usuario_id, $unidade, $local, $area);
    $stmt->execute();
    header("Location: index.php");
    exit;
}


    $sql = "SELECT cod, descricao FROM setor ORDER BY descricao ASC";
    $result = $conn->query($sql);
 $sqla = "SELECT cod, descricao FROM areas_chamados ORDER BY descricao ASC";
    $resulta = $conn->query($sqla);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Abrir Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body class="bg-light">
<?php
    include("header.php");
    ?>
<div class="conteudo">
    <h2>Abrir Chamado</h2>
    <form method="post" class="d-flex flex-wrap flex-column gap-3 ">

        <div class="row p-0">
            <label class="form-label text-start">Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>
        <div class="row p-0">
            <label class="form-label text-start">Descrição</label>
            <textarea name="descricao" class="form-control" rows="3" required></textarea>
        </div>
        <div class="d-flex p-0 gap-1 flex-wrap flex-sm-nowrap  ">
        <div class="col-sm-4 col-12  p-0">
            <label class="form-label text-start">Unidade</label>
            <select name="unidade" class="form-select set" required>
              <?php
	 echo "<option value=''></option>";	
    if ($result->num_rows > 0) {
       	
        while ($row = $result->fetch_assoc()){
        $cod = $row['cod'];
		$desc = $row['descricao'];
        echo "<option value='$cod'>$desc</option>";	
        }
    }
?>
            </select>
        </div>
            <div class="col-sm-4 col-12 p-0">
            <label class="form-label text-start">Local</label>
            <select name="local" class="form-select sub" required>
            </select>
        </div>
            <div class="col-sm-4 col-12 p-0">
            <label class="form-label text-start">Área Responsável</label>
            <select name="area" class="form-select" required>
            <?php
            	 echo "<option value=''></option>";	
        if ($resulta->num_rows > 0) {
       	
        while ($row = $resulta->fetch_assoc()){
        $cod = $row['cod'];
		$desc = $row['descricao'];
        echo "<option value='$cod'>$desc</option>";	
        }
    }

    ?>
       </select>
        </div>
        </div>
        <div class="d-flex flex-nowrap justify-content-center gap-3 mt-5">    
        <button type="submit" class="btn btn-primary">Enviar</button>
        <a href="index.php" class="btn btn-secondary">Voltar</a>
        </div>
    </form>
</div>
<script>
    var selset = document.querySelector('.set');
    var selsub = document.querySelector('.sub');
    selset.addEventListener('change', function() {
        var unidade = selset.value;
        fetch('getdep.php?cod=' + unidade)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição: ' + response.status);
                }
                return response.json(); // converte para texto
              //  return response.json(); // converte para JSON
            }).then(cidade => {
                selsub.innerHTML = ''; 
         cidade.forEach(cidade => {
      const option = document.createElement('option');
      option.value = cidade.cod;
      option.textContent = cidade.descricao;
      selsub.appendChild(option);
    });
    }) .catch(erro => {
    console.error('Erro ao buscar Departamentos:', erro);
  })
            
        });

    </script>
</body>
</html>
