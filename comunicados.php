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
    <title>Comunicados</title>

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

    <link href="css/theme.css" rel="stylesheet" media="all">

</head>

<body class="animsition">
    <?php include("header.php"); ?>
        <div class="main-content">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="overview-wrap">
                                <h2 class="title-1">Comunicados</h2>
                                <button class="au-btn au-btn-icon au-btn--blue" data-toggle="modal" data-target="#publicar_comunicado">Publicar</button>
                            </div>
                        </div>

                        <br><br><br>
                        
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <strong class="card-title">Ultimos 12 meses
                                        <small>
                                            <span class="badge badge-success float-right mt-1">Success</span>
                                        </small>
                                    </strong>
                                </div>
                                
                                <div class="comunicados">
                                    
                                </div>
                            </div>
                        </div>      
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="publicar_comunicado" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" style="padding-right: 17px;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="mediumModalLabel">Publicar Comunicado</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form method="POST" id="comunicado_form">
                        <div class="form-group">
                            <input type="text" class="form-control" name="titulo" aria-describedby="emailHelp" placeholder="Título" required>
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" rows="5" cols="33" name="conteudo" placeholder="Escreva aqui o conteúdo do comunicado..."></textarea>                    
                        </div>
                        <input type="hidden" name="funcao" value="publicar_comunicado">
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-light button_orange" id="button_publicar" onclick="publicar_comunicado()">Publicar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade show" id="editar_comunicado" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" style="padding-right: 17px;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="mediumModalLabel">Editar Comunicado</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form method="POST" id="comunicado_form_editar">
                        <div class="form-group">
                            <input type="text" class="form-control" id="titulo_editar" name="titulo_editar" aria-describedby="emailHelp" placeholder="Título" required>
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" rows="5" cols="33" id="conteudo_editar" name="conteudo_editar" placeholder="Escreva aqui o conteúdo do comunicado..."></textarea>                    
                        </div>
                        <input type="hidden" id="id_editar" name="id_editar" value="">
                        <input type="hidden" name="funcao" value="editar_comunicado">
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-light button_orange" id="button_editar" onclick="editar_comunicado()">Editar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/slick/slick.min.js">
    </script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script> var tipo_user = '<?php echo $tipo_usuario ?>'; </script>

    <!-- Main JS-->
    <script src="js/main.js"></script>
    <script>listar_comunicados();</script>

</body>

<style>

.au-btn--blue {
    background: #4272d7;
    width: 22%;
}

.texto-card{
    width: 85%; 
    position: relative; 
    float: left;
    padding: 25px;
}

.data-card{
    width: 15%; 
    position: relative; 
    float: right;
    text-align: center;
    padding-bottom: auto;
    margin-top: 10px;
}

@media (max-width: 767px){
    .au-btn--blue {
        width: 100%;
    }

    .title-1{
        margin-top: 25px;
        margin-bottom: 25px;
    }
}

</style>

</html>
<!-- end document-->
