<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/font-face.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="css/theme.css" rel="stylesheet" media="all">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>Painel do Corretor</title>
  </head>

  <body>

  <center>
    <div class="container">
      <div class="row">
        <div class="col-sm esquerda">
          <h3 style="margin-top: 50%; color: white;"> Bem vindo a Área do corretor </h3><br>
          <p style="color: white;" class="texto">Aqui você pode verificar material de venda, cadastrar o <br> seu contrato, consultar suas vendas e muito mais! </p><br>
          <button type="button" class="btn btn-success"> Solicite seu código </button>
        </div>

        <div class="col-sm direita">
          <img src="images/logo.png" class="logo">
          <br><br>

          <div class="login-form">
            <form method="post" id="login">
                <div class="form-group">
                    <label>Endereço de Email</label>
                    <input class="au-input au-input--full" type="email" name="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <label>Senha</label>
                    <input class="au-input au-input--full" type="password" name="senha" placeholder="XXXXXXXXXXXXX">
                </div>
                <div class="login-checkbox">
                    <label>
                        <input type="checkbox" name="remember">Lembrar-me
                    </label>
                    <label>
                        <a href="#">Esqueceu sua senha?</a>
                    </label>
                </div>
                <button class="au-btn au-btn--block au-btn--green m-b-20" type="button" id="login-button">ENTRAR</button>
            </form>

            <div class="register-link">
                <p>
                  Ainda não possui acesso?
                  <a href="#">Solicite seu código?</a>
                </p>
            </div>
        </div>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

  </body>

  <script>

  jQuery("#login-button").click(function(){
		var data = $("#login").serialize();

		$.ajax({
			type : 'POST',
			url  : 'logar.php',
			data : data,
			dataType: 'json',
      error: function(){
        $("#login-button").html('ENTRAR');
        swal("Ops!", "Você digitou sua senha ou seu email incorretamente!", "warning");
      },
			beforeSend: function()
			{
				$("#login-button").html('<img src="images/carregando.gif" class="carregando">');
			},
			success :  function(response){
				if($.trim(response) == 'true'){
					window.location.href = "inicio.php";
				} else if($.trim(response) == 'vazio'){
          swal("Ops!", "Você deve digitar seu email e sua senha!", "warning");
          $("#login-button").html('ENTRAR');
				}
		   }
		});
	});

  </script>

  <style>

  html, body{
    background-color: #e5e5e5;
    height: 100%;
    width: 100%;
    min-height: 100%;
    font-family: "Poppins", sans-serif;
  }

  .carregando{
    width: 30px;
    margin-top: -3px;
  }

  @media (max-width: 649px) {
    .btn-success{
      width: 200px;
      border-radius: 20px;
      display: none;
    }

    h3 {
      position: relative;
      top: calc(100% - 500px);
      font-size: 23px;
    }

    .texto{
      display: none;
    }

    .logo {
      margin-bottom: 25px;
    }

    .col-sm{
      padding-left: 0px;
      padding-right: 0px;
    }

    .container, .container-md, .container-sm {
      max-width: 100%;
    }

    .esquerda{
      display: none;
    }

    .direita{
      background-color: white;
      height: 100%;
      width: 100%;
      margin-left: 0%;
      position: absolute;
      padding: 50px 50px;
    }

    .container {
       margin-left: 0px;
       margin-right: 0px;
       padding-right: 0px;
       padding-left: 0px;
     }

     .row {
        margin-left: 0px;
        margin-right: 0px;
      }

     .login-form {
       text-align:left;
     }

     .register-link{
       border-top: 1px solid #dcdcdc;
     }
   }

@media (min-width: 650px) and (max-width: 989px) {
  .btn-success{
    width: 200px;
    border-radius: 20px;
    display: none;
  }

  h3{
    position: relative;
    top: calc(100% - 500px);
    font-size: 23px;
  }

  .texto{
    display: none;
  }

  .logo {
    margin-bottom: 25px;
  }

  .col-sm{
    padding-left: 0px;
    padding-right: 0px;
  }

  .container, .container-md, .container-sm {
    max-width: 100%;
  }

  .esquerda{
    display: none;
  }

  .direita{
    background-color: white;
    height: 100%;
    width: 100%;
    margin-left: 0%;
    position: absolute;
    padding: 50px 155px;
  }

  .container {
     margin-left: 0px;
     margin-right: 0px;
     padding-right: 0px;
     padding-left: 0px;
   }

   .row {
      margin-left: 0px;
      margin-right: 0px;
    }

   .login-form {
     text-align:left;
   }

   .register-link{
     border-top: 1px solid #dcdcdc;
   }
 }

@media (min-width: 990px) and (max-width: 1199px) {
  .btn-success {
    width: 200px;
    border-radius: 20px;
    display: none;
  }

  h3{
    position: relative;
    top: calc(100% - 500px);
    font-size: 23px;
  }

  .texto{
    display: none;
  }

  .logo {
    margin-bottom: 25px;
  }

  .col-sm{
    padding-left: 0px;
    padding-right: 0px;
  }

  .container, .container-md, .container-sm {
    max-width: 100%;
  }

  .esquerda{
    background-image: url("images/barra_contem.jpg");
    height: 100%;
    width: 40%;
    position: fixed;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: left;
    position: fixed;
    background-size: 40%;
  }

  .direita{
    background-color: white;
    height: 100%;
    width: 62%;
    margin-left: 40%;
    position: fixed;
    padding: 70px 125px;
  }

  .container {
     margin-left: 0px;
     margin-right: 0px;
     padding-right: 0px;
     padding-left: 0px;
   }

   .login-form {
     text-align:left;
   }

   .register-link{
     border-top: 1px solid #dcdcdc;
   }
 }

  @media (min-width: 1200px) {

  .btn-success{
    width: 200px;
    border-radius: 20px;
  }

  .logo {
    margin-bottom: 25px;
  }

  .col-sm{
    padding-left: 0px;
    padding-right: 0px;
  }

  .container, .container-md, .container-sm {
    max-width: 100%;
  }

  .esquerda{
    background-image: url("images/barra_contem.jpg");
    height: 100%;
    width: 40%;
    position: fixed;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: left;
    position: fixed;
    background-size: 40%;
  }

  .direita{
    background-color: white;
    height: 100%;
    width: 62%;
    margin-left: 40%;
    position: fixed;
    padding: 100px 150px;
  }

  .container {
     margin-left: 0px;
     margin-right: 0px;
     padding-right: 0px;
     padding-left: 0px;
   }

   .login-form {
     text-align:left;
   }

   .register-link{
     border-top: 1px solid #dcdcdc;
   }
 }

  </style>
</html>
