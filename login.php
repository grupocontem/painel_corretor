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
    <title>Hello, world!</title>
  </head>

  <body>

  <center>
    <div class="container">
      <div class="row">
        <div class="col-sm esquerda">
          <h3 style="margin-top: 50%; color: white;"> Bem vindo ao Grupo Contém! </h3><br>
          <p style="color: white;">Lorem Ipsum is simply dummy text <br>of the printing and typesetting industry. </p><br>
          <button type="button" class="btn btn-success"> Cadastrar </button>
        </div>

        <div class="col-sm direita">
          <h3 style="margin-top:0; color: black;"> Área do corretor! </h3>
          <br><br>

          <div class="login-form">
            <form action="" method="post">
                <div class="form-group">
                    <label>Endereço de Email</label>
                    <input class="au-input au-input--full" type="email" name="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <label>Senha de Acesso</label>
                    <input class="au-input au-input--full" type="password" name="password" placeholder="Password">
                </div>
                <div class="login-checkbox">
                    <label>
                        <input type="checkbox" name="remember">Lembrar-me
                    </label>
                    <label>
                        <a href="#">Esqueceu sua senha?</a>
                    </label>
                </div>
                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">ENTRAR</button>
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

  </body>

  <style>

  html, body{
    background-color: #e5e5e5;
    height: 100%;
    width: 100%;
    min-height: 100%;
    font-family: "Poppins", sans-serif;
  }

  .btn-success{
    width: 170px;
    border-radius: 20px;
  }

  .col-sm{
    padding-left: 0px;
    padding-right: 0px;
  }

  .container, .container-md, .container-sm {
    max-width: 100%;
  }

  .esquerda{
    background-image: linear-gradient(120deg, #f78837,#f57133,#f03b29);
    height: 100%;
    width: 40%;
    position: fixed;
  }

  .direita{
    background-color: white;
    height: 100%;
    width: 60%;
    margin-left: 40%;
    position: fixed;
    padding: 150px;
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

  </style>
</html>
