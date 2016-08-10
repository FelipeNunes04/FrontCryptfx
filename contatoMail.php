<?php

//pega os dados do $http do Angular
$meuPost = file_get_contents("php://input");

//para acessar os dados: $json.nome ou $Json.email e etc.

if($meuPost){
	$json= json_decode( $meuPost );

	$nome = $json->nome;
	$email = $json->email;
	$assunto = $json->assunto;
    $assunto = "CryptFx - ".$assunto;
	$mensagem = $json->mensagem;
    $to =  "felipennunes@hotmail.com, suporte@cript-fx.com";

    $message = "
    <style type='text/css'>
    body {
    margin:0px;
    font-family: arial;
    font-size:14px;
    }
    .compra{
    	color:red;
    }
    </style>
    <html>
        Recebemos uma mensagem de contato de $nome<br><br>
        Email: $email<br><br>
        Mensagem: <b>$mensagem</b>
    </html>
    ";

}

$headers = 'Content-type: text/html; charset=utf-8' . "\r\n";

if(mail($to, $assunto, $message, $headers)){
  echo "<p class='text-success'>Mensagem Enviada Com Sucesso!</p>";
  echo json_encode(array(

                        "nome"=>$json->nome,
                        "email"=>$json->email,
                        "assunto"=>$json->assunto

                    ));
}else{
  echo "<p class='text-danger'>Sua Mensagem Não Foi Enviada!</p>";
}

//retorna os dados para o success do Angular
