<?php

if (isset($_GET['proposta']))
    $proposta = $_GET['proposta'];
else
    $proposta = null;


$conexao = mysqli_connect("grupocontem.com.br", "grupocon_conexao", "c0Nt3m#2@1p", "grupocon_vendapj") or die("Sem conexao");
mysqli_set_charset($conexao, "utf8");

$sql = mysqli_query($conexao, "SELECT * FROM wp_contratospj where id = '$proposta'");
$dado = mysqli_fetch_array($sql);
$codigo_proposta = $dado['codigo_corretor'];

?>

<!DOCTYPE html>

<html lang="en">
<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Beneficiários</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
    <script type="text/javascript" src="js/functions.js"> </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

</head>

<body class="animsition">
    <div class="page-wrapper">
        <?php include("header.php"); ?>
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- DATA TABLE -->
                                <h3 class="title-5 m-b-35">Perfil do Corretor</h3>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="account2">
                                            <div class="image img-cir img-120">
                                                <img src="documentos_pj/foto_corretor/<?php echo''.$foto; ?>" alt="John Doe" />
                                            </div>
                                            <h4 class="name"><?php echo''.$nome; ?></h4>
                                            <small> Corretor(a) </small>
                                        </div>

                                        <form method="post" id="alterar_foto"> 
                                            <div class="form-group">
                                                <label for="company" class="form-control-label">Alterar Foto</label>
                                                <div class="input-group">
                                                    <input id="cc-name" name="cc-name" type="text" class="form-control cc-name valid" data-val="true" readonly="">
                                                    <label class="input-group-btn" style="height: 15px;">
                                                        <span class="btn btn-light button_orange">
                                                            Escolher… <input type="file" name="file[]" id="file" style="display: none;">
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>

                                            <input type="hidden" name="funcao" value="alterar_foto">

                                            <div>
                                                <button id="alterar-button" type="button" class="btn btn-lg btn-info btn-block" onclick="alterar_foto();">
                                                   Alterar Foto
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body card-block">
                                        <div class="form-group">
                                            <label for="company" class=" form-control-label">Nome</label>
                                            <input type="text" id="company" class="form-control" value="<?php echo''.$nome; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="vat" class=" form-control-label">Email</label>
                                            <input type="text" id="vat" class="form-control" value="<?php echo''.$email; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="street" class=" form-control-label">CPF</label>
                                            <input type="text" id="street" class="form-control" value="<?php echo''.$cpf_final; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="street" class=" form-control-label">Telefone</label>
                                            <input type="text" id="street" class="form-control" value="<?php echo''.$tel_final; ?>" readonly>
                                        </div>
                                        <!--<div class="form-group">
                                            <label for="street" class=" form-control-label">Distribuidora</label>
                                            <input type="text" id="street" class="form-control" value="<?php echo''.$distribuidora; ?>" readonly>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body card-block">
                                        <form method="POST" id="form_alterar_senha">
                                            <div class="form-group">
                                                <label for="company" class=" form-control-label">Senha Atual</label>
                                                <input type="password" id="company" name="senha_atual" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="vat" class=" form-control-label">Nova Senha</label>
                                                <input type="password" id="vat" name="nova_senha" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="vat" class=" form-control-label">Confirmar nova senha</label>
                                                <input type="password" id="vat" name="confirmar_senha" class="form-control">
                                            </div>

                                            <input type="hidden" name="email" value="<?php echo''.$email; ?>">
                                            <input type="hidden" name="funcao" value="alterar_senha">
                                            
                                            <button type="button" class="btn btn-success btn-lg btn-block" id="btn_altera_senha" onclick="alterar_senha();">Alterar Senha</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div class="row" style="margin-top: 100px;">
                            <div class="col-md-12">
                                <div class="copyright">
                                    <p>Copyright © 2018 Colorlib. All rights reserved. Template by <a href="https://colorlib.com">Colorlib</a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS -->
    <script src="vendor/slick/slick.min.js"></script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js"> </script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="js/main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script> var proposta_cod = '<?php echo''.$proposta; ?>'; </script>
</body>

<script>

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

.carregando{
    width: 30px;
    margin-top: -3px;
  }

</style>

</html>
