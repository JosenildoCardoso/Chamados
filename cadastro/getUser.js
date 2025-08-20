function getUser(cod) {
	     
     // Declaração de Variáveis
       var xmlreq = CriaRequest();

var imat = document.getElementById("mat");
if (imat.value){
var inome = document.getElementById("nome");
var ilogin = document.getElementById("login");
var iemail = document.getElementById("email");
var icpf = document.getElementById("cpf");
var iacesso = document.getElementById("acesso");
inome.value = "";
ilogin.value = "";
iemail.value = "";

	xmlreq.open("GET", 'getuser.php?mat=' + cod, true);
	
    	
	
     // Atribui uma função para ser executada sempre que houver uma mudança de ado
	 
     xmlreq.onreadystatechange = function(){
         
         // Verifica se foi concluído com sucesso e a conexão fechada (readyState=4)
         if (xmlreq.readyState == 4) {
              // Verifica se o arquivo foi encontrado com sucesso
             if (xmlreq.status == 200) {
		var json = JSON.parse(xmlreq.responseText);
				if(json[0].hasOwnProperty('status')){
					
					if(json[0].status == 1){
					 
					  var nome = json[0].nome;
					   var mat = json[0].matricula;
					    var login = json[0].login;
					   var email = json[0].email;
					   icpf.value = json[0].cpf;
					  
					      iacesso.value = json[0].acesso;
						
					    imat.value = mat;
					   inome.value = nome;
					   ilogin.value = login;
					   ilogin.readOnly = true;
					   ilogin.setAttribute("OnBlur", "void(0)");
					   iemail.value = email;
					     ilogin.style.borderColor = "#ccc";
					   alert("Configure as permissões!");
					   iacesso.focus();
					   
					   	}else if(json[0].status == 2){
						var nome = json[0].nome;
					   var mat = json[0].matricula;
					      ilogin.readOnly = false;
						  ilogin.setAttribute("OnBlur", "getLogin(value)");
						  ilogin.style.borderColor = "#ccc";
					    imat.value = mat;
					   inome.value = nome;
										   ilogin.focus();
						
						
					}else if(json[0].status == 3){
						inome.focus();
						 ilogin.readOnly = false;
						  ilogin.style.borderColor = "#ccc";
						    ilogin.setAttribute("OnBlur", "getLogin(value)");
					}
			
				}
             }else{
               //  print("Erro: " + xmlreq.statusText);
             }
         }
     };
     xmlreq.send(null);
 }
}
 
// JavaScript Document