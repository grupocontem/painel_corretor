<?php

  include('conexao.php');

  if(isset($_SESSION['idUser'])){
    header("Location: inicio.php");
    exit;
  }
?>

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
          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#cadastrar_corretor"> Solicite seu código </button>
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
                <a href="#" data-toggle="modal" data-target="#cadastrar_corretor">Solicite seu código?</a>
              </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="cadastrar_corretor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Solicitar Código</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">

          <form method="POST" id="solicitar_codigo">

          <div class="form-group">
            <input type="text" class="form-control" id="nome" name="nome" aria-describedby="emailHelp" placeholder="Nome Completo" required value="">
          </div>

          <div class="form-group">
            <input type="date" class="form-control" id="" name="nascimento" aria-describedby="emailHelp" placeholder="Data de Nascimento" required value="">
          </div>

          <div class="form-group">
            <input type="text" class="form-control" id="fixo" name="fixo" aria-describedby="emailHelp" placeholder="Telefone Fixo" required value="">
          </div>

          <div class="form-group">
            <input type="text" class="form-control" id="celular" name="celular" aria-describedby="emailHelp" placeholder="Telefone Celular" required value="">
          </div>

          <div class="form-group">
            <input type="text" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Email" required value="">
          </div>

          <div class="form-group">
            <input type="text" class="form-control" id="distribuidora" name="distribuidora" aria-describedby="emailHelp" placeholder="Distribuidora" required value="">
          </div>

          <label> Para concluir sua solicitação você deve anexar o seu RG, CPF, Comprovante de residência e a carta de autorização da
          distribuidora. </label><br><br>

          <div class="input-group" style="margin-top: 0px;">
            <input type="text" class="form-control" id="file_finalizar" placeholder="RG" readonly>
              <label class="input-group-btn">
                <span class="btn btn-light button_orange">
                  Escolher&hellip; <input type="file" name="file[]" id="file_finalizar" style="display: none;" />
                </span>
              </label>
            </div>
          <span class="help-block"><br>

          <div class="input-group" style="margin-top: 0px;">
            <input type="text" class="form-control" id="file_finalizar" placeholder="CPF" readonly>
              <label class="input-group-btn">
                <span class="btn btn-light button_orange">
                  Escolher&hellip; <input type="file" name="file[]" id="file_finalizar" style="display: none;" />
                </span>
              </label>
            </div>
          <span class="help-block"><br>

            <div class="input-group" style="margin-top: 0px;">
              <input type="text" class="form-control" id="file_finalizar" placeholder="Comprovante de residência" readonly>
                <label class="input-group-btn">
                  <span class="btn btn-light button_orange">
                    Escolher&hellip; <input type="file" name="file[]" id="file_finalizar" style="display: none;" />
                  </span>
                </label>
              </div>
            <span class="help-block"><br>

            <div class="input-group" style="margin-top: 0px;">
              <input type="text" class="form-control" id="file_finalizar" placeholder="Carta de autorização da distribuidora" readonly>
                <label class="input-group-btn">
                  <span class="btn btn-light button_orange">
                    Escolher&hellip; <input type="file" name="file[]" id="file_finalizar" style="display: none;" />
                  </span>
                </label>
              </div>

            <span class="help-block"><br>
            <div class="g-recaptcha" data-sitekey="6LeIjtwZAAAAAEzTwo3uFBsbTm0R8L_v0_VxoBH0"></div>

            <input type="hidden" name="funcao" value="cadastrar_corretor">
          </form>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-light button_orange" onclick="cadastrar()">Cadastrar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>

  </body>

  <script>

  jQuery('#fixo').mask('(99) 99999-9999');
  jQuery('#celular').mask('(99) 99999-9999');

  function cadastrar (){
    var myForm = document.getElementById('solicitar_codigo');
    formData = new FormData(myForm);

    //$('#cadastrar_solicitacao').attr("disabled", true);

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: 'solicitar_codigo.php',
        async: true,
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){

        },
        error: function() {
          swal("Ops!", "Ocorreu um erro inesperado!", {icon: "error",});
          $('#cadastrar_solicitacao').attr("disabled", true);
        },
        success: function(result)
        {
          $('#cadastrar_solicitacao').attr("disabled", false);
          if($.trim(result) == 'nome-invalid'){
              swal("Ops!", "Nome não preenchido!", {icon: "error",});
          } else if($.trim(result) == 'nascimento-invalid'){
              swal("Ops!", "Data de nascimento não preenchida!", {icon: "error",});
          } else if($.trim(result) == 'fixo-invalid'){
              swal("Ops!", "Telefone Fixo não preenchido!", {icon: "error",});
          } else if($.trim(result) == 'cel-invalid'){
              swal("Ops!", "Celular não preenchido!", {icon: "error",});
          } else if($.trim(result) == 'email-invalid'){
              swal("Ops!", "Email não preenchido!", {icon: "error",});
          } else if($.trim(result) == 'dist-invalid'){
              swal("Ops!", "Distribuidora não preenchida!", {icon: "error",});
          } else if($.trim(result) == 'files-error'){
              swal("Ops!", "Anexe todos os documentos exigidos!", {icon: "error",});
          } else if($.trim(result) == 'captcha-error'){
              swal("Ops!", "Marque o captcha exigido!", {icon: "error",});
          } else if($.trim(result) == 'ok') {
              swal("Perfeito!", "Seus dados foram enviados com sucesso!", "success")
              .then((value) => {
                location.reload();
              });
          }
        }
    });
  }

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
				  window.location.href = "https://painel.grupocontem.com.br/inicio.php";
				} else if($.trim(response) == 'vazio'){
          swal("Ops!", "Você deve digitar seu email e sua senha!", "warning");
          $("#login-button").html('ENTRAR');
				}
		   }
		});
	});

  jQuery(function() {

    // We can attach the `fileselect` event to all file inputs on the page
    jQuery(document).on('change', ':file', function() {
      var input = $(this),
          numFiles = input.get(0).files ? input.get(0).files.length : 1,
          label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
      input.trigger('fileselect', [numFiles, label]);
    });

    // We can watch for our custom `fileselect` event like this
    jQuery(document).ready( function() {
        $(':file').on('fileselect', function(event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }

        });
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

  .button_orange{
    background-color: #f2562f;
    border-color: #f2562f;
    color: white;
  }

  .button_orange:hover{
    background-color: #f93200;
    border-color: #f2562f;
    color: white;
  }

  .button_orange:focus{
    background-color: #f93200;
    border-color: #f2562f;
    color: white;
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
