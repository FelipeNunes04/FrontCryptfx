var comprarBitcoins = angular.module("comprarBitcoins", ["firebase"]);



comprarBitcoins.controller("precoCtrl",
 function($scope, $http, Scopes){
 	Scopes.store('precoCtrl', $scope);
	$scope.app = "CryptFX";
	$scope.precoBitcoin = [];
	
	var carregarPrecos = function () {
		$http.get("https://www.bitcointoyou.com/api/ticker.aspx").success(function(data, status){
			$scope.precoBitcoin = data;
		});
	};
	carregarPrecos();
});


comprarBitcoins.controller("contatoCtrl",
 function($scope, $http){
	
	$scope.enviaEmail = function () {
		nome = $scope.contato.nome;
		email = $scope.contato.email;
		assunto = $scope.contato.assunto;
		mensagem = $scope.contato.mensagem;

		$http({
          url: 'contatoMail.php',
          method: 'POST',
          data: {
            'nome': nome,
            'email': email,
            'assunto': assunto,
            'mensagem':mensagem,            
            
          },
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
            
          }
          
	        }).
	        success(function (data) {
	            $scope.success = true;
	            

				$scope.contato.email = '';
				$scope.contato.nome = '';
				$scope.contato.assunto = '';
				$scope.contato.mensagem = '';

				$scope.formularioContato.$setPristine();
				
				alert("Obrigado pela mensagem, entraremos em contato o mais breve possível.");
	            
	        }).
	        error(function (data) {
	            $scope.error = true;
	            
	        });

	};
});


comprarBitcoins.controller("comprarBitcoinsCtrl",["$scope","$firebaseArray","Scopes","$http",
 function($scope, $firebaseArray, Scopes,$http){
 	
 	Scopes.store('comprarBitcoinsCtrl', $scope);

 	var ref = new Firebase("https://cryptfx-ef8a6.firebaseio.com");
 	var compras = ref.child("compras");
	$scope.compras =  $firebaseArray(compras);
	$scope.comprar = "Comprar";

	function toFixed(number){
	    number = parseFloat(number);
	    if(number % 1 != 0){
	        return parseFloat(number.toFixed(18));
	    }else{
	        return number;
	    }
	} 

	function pegaData() {
		var date = new Date();
		var month = (date.getMonth()+1) > 9 ? (date.getMonth()+1) : "0" + (date.getMonth()+1);
		var day = (date.getDate()) > 9 ? (date.getDate()) : "0" + (date.getDate());
		var hours = (date.getHours()) > 9 ? (date.getHours()) : "0" + (date.getHours());
		var minutes = (date.getMinutes()) > 9 ? (date.getMinutes()) : "0" + (date.getMinutes());
		var seconds = (date.getSeconds()) > 9 ? (date.getSeconds()) : "0" + (date.getSeconds());

		var dateString = 
		    day + "/" + 
		    month + "/" + 
		    date.getFullYear() + " - " + 
		    hours + ":" + 
		    minutes + ":" + 
		    seconds;
		return dateString;
	}
	
	$scope.adicionarCompra = function () {
		reais = $scope.reais;
		bitcoins = ($scope.precoBitcoin.ticker.sell / $scope.bitcoins)*1.025;
		bitcoins = toFixed(bitcoins); 
		tel = $scope.telefone;
		email = $scope.email;
		wallet = $scope.wallet;
		nome = $scope.nome;
		cpf = $scope.cpf;
		data = pegaData();

		$scope.compras.$add({
			nome: nome,
			cpf: cpf,
			bitcoin: bitcoin,
			telefone: tel,
			reais: reais,
			email: email,
			wallet: wallet,
			data: data,
		}).then(function(ref) {
		  $scope.compra = ref.key();
		
		  $http({
          url: 'mail.php',
          method: 'POST',
          data: {
            'nome': nome,
            'cpf': cpf, 
            'email': email,
            'bitcoin': bitcoin,
            'compra': $scope.compra,
            'reais': reais,
            'wallet': wallet,
            'tel': tel,
            'data':data,

            
          },
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
            
          }
          
	        }).
	        success(function (data) {
	            $scope.success = true;
	            //exemplo de retorno: alert(data['email']);
	            $scope.bitcoins = '';
				$scope.telefone= '';
				$scope.email = '';
				$scope.wallet = '';
				$scope.nome = '';
				$scope.cpf = '';
				$scope.termo = false;

				$scope.contatoForm.$setPristine();
				
				alert("Sucesso! Acesse seu e-mail e confira os dados para realizar o pagamento");
	            
	        }).
	        error(function (data) {
	            $scope.error = true;
	            
	        });

		});		
	}
}]);

comprarBitcoins.factory('Scopes', function ($rootScope) {
    var mem = {};

    return {
        store: function (key, value) {
            $rootScope.$emit('scope.stored', key);
            mem[key] = value;
        },
        get: function (key) {
            return mem[key];
        }
    };
});

