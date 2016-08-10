<?php

//pega os dados do $http do Angular
$meuPost = file_get_contents("php://input");

//para acessar os dados: $json.nome ou $Json.email e etc.

if($meuPost){
	$json= json_decode( $meuPost );

	$nome = $json->nome;
    $cpf = $json->cpf;
	$email = $json->email;
	$bitcoin = $json->bitcoin;
	$compra = $json->compra;
	$reais = $json->reais;
	$telefone = $json->tel;
	$wallet = $json->wallet;
	$data = $json->data;



	$assunto = 'Compra de Bitcoins';
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
        Ordem de Compra <b>$compra</b> <br>
        Obrigado por sua compra de $bitcoin BTC<br>
        Estamos aguardando a compensação do seu pagamento no valor de R$ $reais<br>
        Utilize a seguinte conta bancária para efetuar o pagamento: <br><br><br><br>
        <b>BANCO SANTANDER</b><br>
        Agência: 4417<br>
        Conta: 13001011-5<br>
        Cedente: DRB SERVISOS LTDA<br>
        CNPJ: 10.384.752-0001=57<br><br><br>
        <b>BANCO ITAU</b><br>
        Agência: 0548<br>
        Conta: 9337-1<br>
        Cedente: PERRIER BULL PGTS EIRELE-ME<br>
        CNPJ: 22.474.815/0001-45<br><br><br>
        <b>ATENÇÃO:</b><br>
        Envie o anexo do comprovante de pagamento para o e-mail financeiro@cript-fx.com<br>
        Escreva no comprovante de pagamento: <b><span class='compra'>COMPRA BITCOIN IDENTIFICADOR: $compra EM WWW.CRYPT-FX.COM</span></b><br>
        Valor minimo por transação R$ 10,00<br>
        Facilite a localização e identificação do seu pagamento, deposite centavos Adicionais. Exemplos. R$ 112,07 - R$ 20,55 - 19,40 - R$ 22,15<br>
        Por favor, inclua no comprovante de pagamento o endereço do site.<br>
        **********************************************<br><br><br>
        Para validar as condições descritas nesse email seu pagamento deve ser realizado hoje.<br>
        Siga corretamente as instruções desse email para garantizar uma entrega segura de seus bitcoins.<br>
        Data dessa transação: $data<br><br><br>
        -----------------------------------------------<br>
        Informações de contato que recebemos<br><br>
        Email:$email<br>
        Telefone:$telefone<br>
        Wallet: $wallet<br>
        Nome: $nome<br>
        CPF: $cpf<br>
        Obrigado por preferir realizar essa transação com a CryptFX<br>
        Nos colocamos a sua disposição para qualquer dúvida ou consulta que precisar<br>
        Atenciosamente,<br>
        CryptFX<br>
        Telefone: (DD) DDDD-DDDD<br>
        * Por questões de segurança essa transação esta sujeito a validação, que consiste na verificação da veracidade dos dados informados.<br>
        ** Em caso de erro na cotação ou indisponibilidade da quantia de Bitcoin comprada essa transação pode ser cancelada.<br>
    </html>
    ";

}

$headers = 'Content-type: text/html; charset=utf-8' . "\r\n";

if(mail($email, $assunto, $message, $headers)){
  echo "<p class='text-success'>Mensagem Enviada Com Sucesso!</p>";
  echo json_encode(array(

                        "nome"=>$json->nome,
                        "email"=>$json->email,
                        "bitcoin"=>$json->bitcoin

                    ));
}else{
  echo "<p class='text-danger'>Sua Mensagem Não Foi Enviada!</p>";
}

//retorna os dados para o success do Angular
