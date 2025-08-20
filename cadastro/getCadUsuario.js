function getUsuarios(pg) {
	     
     // Declaração de Variáveis
       var xmlreq = CriaRequest();

var idep = document.getElementById("listauser");
var und = document.getElementById("unidade");
var bs = document.getElementById("busca");
	xmlreq.open("GET", 'listausuarios.php?nome=' + bs.value + "&unidade=" + und.value + "&pg=" + pg, true)
	
    	
	
     // Atribui uma função para ser executada sempre que houver uma mudança de ado
	 
     xmlreq.onreadystatechange = function(){
         
         // Verifica se foi concluído com sucesso e a conexão fechada (readyState=4)
         if (xmlreq.readyState == 4) {
              // Verifica se o arquivo foi encontrado com sucesso
             if (xmlreq.status == 200) {
				 if (xmlreq.responseText){
					
				idep.innerHTML = xmlreq.responseText;
				idep.style.display = "block";
			
				 }
				
				
                				 
             }else{
               //  print("Erro: " + xmlreq.statusText);
             }
         }
     };
     xmlreq.send(null);
 }

 
// JavaScript Document