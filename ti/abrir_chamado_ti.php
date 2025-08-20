<?php
session_start();
include '../conexao.php';
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $prioridade = $_POST['prioridade'];
    $usuario_id = $_POST['matricula'];

    $stmt = $conn->prepare("INSERT INTO chamados (titulo, descricao_problema, status, prioridade, data_abertura, matricula) VALUES (?, ?, 'Aberto', ?, NOW(), ?)");
    $stmt->bind_param("ssss", $titulo, $descricao, $prioridade, $usuario_id);
    $stmt->execute();
    header("Location: ver_chamados.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Abrir Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
        .lista-sugestoes {
            position: absolute;
            z-index: 1000;
            width: 100%;
            background: white;
            border: 1px solid #ced4da;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
        }
        .lista-sugestoes li {
            padding: 10px;
            cursor: pointer;
        }
        .lista-sugestoes li:hover {
            background-color: #f1f1f1;
        }
        .fxb30{
            flex-basis: 30%;
        }
    </style>
<body class="bg-light">
<?php
    include("../header.php");
    ?>
<div class="container mt-5">
    <h2>Abrir Chamado</h2>
    <form method="post" class="card p-4 bg-white shadow-sm">
        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="descricao" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Prioridade</label>
            <select name="prioridade" class="form-select" required>
                <option value='3'>Alta</option>
                <option value='2'>Média</option>
                <option value='1'>Baixa</option>
            </select>
        </div>
        <div class="mb-3">
        <input type="hidden" name="matricula" id="matricula">
        <label class="form-label">Usuário</label>
    <div class="position-relative">
        <input type="text" id="campo-busca" class="form-control" placeholder="Digite um nome...">
        <ul id="lista-sugestoes" class="list-unstyled d-none lista-sugestoes"></ul>
    </div>
        </div>
        <div class="mb-3">
            <input type="hidden" name="problema" id="problema">
        <label class="form-label">Tipo de Problema</label>
    <div class="position-relative">
        <input type="text" id="campo-prob" class="form-control" placeholder="Digite um problema...">
        <ul id="lista-sugestoes-prob"  class="list-unstyled d-none lista-sugestoes"></ul>
    </div>
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
        <a href="chamados.php" class="btn btn-secondary">Voltar</a>
    </form>
</div>
</body>
<script>
   const input = document.getElementById('campo-busca');
const lista = document.getElementById('lista-sugestoes');
const mat = document.getElementById('matricula');

const inputprob = document.getElementById('campo-prob');
const listaprob = document.getElementById('lista-sugestoes-prob');
const prob = document.getElementById('problema');

input.addEventListener('input', async function() {
    const termo = this.value.trim();
    if (termo.length === 0) {
        lista.innerHTML = '';
        lista.classList.add('d-none');
        return;
    }


    try {
        const data = await buscarDados(termo);  // Chama a função assíncrona para buscar os dados
        lista.innerHTML = '';  // Limpa a lista antes de adicionar novas sugestões
        console.log('Tipo de dados:', Array.isArray(data));
        console.log('Dados:', Object.keys(data).length);
        if (data.length > 0) {
            console.log('Tipo de dados:', Array.isArray(data)); // Isso deve retornar true se for um array
            console.log('Dados:', data.length);
            data.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item.nome;
                li.dataset.id = item.id;
                li.addEventListener('click', () => {
                    input.value = item.nome;
                    mat.value = li.getAttribute("data-id");
                    lista.classList.add('d-none');
                   // buscarDetalhes(item.id);  // Chama a função para buscar detalhes do item
                });
                lista.appendChild(li);
            });
            lista.classList.remove('d-none');
        } else {
            lista.classList.add('d-none');
        }
    } catch (error) {
        console.error('Erro na requisição:', error);
    }
});


inputprob.addEventListener('input', async function() {
    const termo = this.value.trim();
    if (termo.length === 0) {
        listaprob.innerHTML = '';
        listaprob.classList.add('d-none');
        return;
    }
    try {
        const data = await buscarDadosProb(termo);  // Chama a função assíncrona para buscar os dados
        listaprob.innerHTML = '';  // Limpa a lista antes de adicionar novas sugestões
        console.log('Tipo de dados:', Array.isArray(data));
        console.log('Dados:', Object.keys(data).length);
        if (data.length > 0) {
            console.log('Tipo de dados:', Array.isArray(data)); // Isso deve retornar true se for um array
            console.log('Dados:', data.length);
            data.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item.descricao;
                li.dataset.id = item.id;
                li.addEventListener('click', () => {
                    inputprob.value = item.descricao;
                    prob.value = li.getAttribute("data-id");
                    listaprob.classList.add('d-none');

                   // buscarDetalhes(item.id);  // Chama a função para buscar detalhes do item
                });
                listaprob.appendChild(li);
            });
            listaprob.classList.remove('d-none');
        } else {
            listaprob.classList.add('d-none');
        }
    } catch (error) {
        console.error('Erro na requisição:', error);
    }
});

// Função para fazer a requisição assíncrona e retornar os dados
async function buscarDados(termo) {
    const response = await fetch('busca.php?term=' + encodeURIComponent(termo));
    if (!response.ok) {
        throw new Error('Falha na requisição');
    }
    return response.json();  // Retorna a resposta convertida para JSON
}

async function buscarDadosProb(termo) {
    const response = await fetch('getProblemas.php?term=' + encodeURIComponent(termo));
    if (!response.ok) {
        throw new Error('Falha na requisição');
    }
    return response.json();  // Retorna a resposta convertida para JSON
}

// Função para buscar detalhes do item clicado
async function buscarDetalhes(id) {
    try {
        const response = await fetch('detalhes.php?id=' + id);
        if (!response.ok) {
            throw new Error('Falha na requisição para detalhes');
        }
        const data = await response.json();  // Resposta convertida para JSON
        console.log(data);  // Manipula os detalhes do item aqui
        alert('Detalhes do item: ' + data.nome);  // Exemplo de como usar os dados
    } catch (error) {
        console.error('Erro ao buscar detalhes:', error);
    }
}
</script>
</html>
