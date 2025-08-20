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
<input autocomplete="off" id="login" name="login" placeholder="Usuário" class="campotxt iusrtxt login" type="text" />
</div>
<div class='gplg'>
<input autocomplete="off" id="senha" name="senha" placeholder="Senha"  type="password" class="campotxt ipasstxt senha"/>
</div>
<button class="btlogin">Entrar</button>

<span id="retorno" class='ret'>

</span>
<span class='gpimg'><img class='imglogo' src="img/imglogin.png"/></span>
</div>
</form>
</div>

</body>
<script>
document.querySelector('.btlogin').addEventListener('click', function(e) {
     e.preventDefault();
    // Pega os valores dos campos
    const usuario = document.querySelector('.login').value;
    const senha   = document.querySelector('.senha').value;

    // Cria os dados a enviar
    const formData = new URLSearchParams();
    formData.append('login', usuario);
    formData.append('senha', senha);

    // Envia a requisição POST para a API
    fetch('api/login/', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
     
        if(data.status === 'success') {
            // Salva o token se quiser (ex.: localStorage)
            localStorage.setItem('token', data.token);
          
            // Redireciona para index.php
            window.location.href = 'index.php';
        } else {
            // Exibe a mensagem de erro na div
            document.querySelector('.ret').innerHTML = data.message;
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
        document.querySelector('.ret').innerHTML = 'Erro de conexão';
    });
});
</script>

</html>