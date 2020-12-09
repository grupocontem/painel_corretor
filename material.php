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
    <title>Material de Venda</title>

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
    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">
    <script type="text/javascript" src="js/functions.js"> </script>

</head>

<body class="animsition">
    <div class="page-wrapper">
        <?php include("header.php"); ?>
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                      <div class="row">
                            <div class="col-md-12">
                                <div class="overview-wrap">
                                    <h2 class="title-1">Material de Venda</h2>
                                </div>
                            </div>
                        </div>
                      <div class="row">
                          <div class="col-md-12">
                              <!-- DATA TABLE -->
                              <h3 class="title-5 m-b-35"></h3>
                              <div class="table-data__tool">
                                  <div class="table-data__tool-left">
                                      <div class="rs-select2--light rs-select2--md">
                                          <select class="js-select2" name="property" id="operadora_select" onchange="listar_material_filtro()">
                                            <option selected="selected" value="">Operadora</option>
                                            <option value="Ideal Saude">Ideal Saúde</option>
                                            <option value="Odonto Empresas">Odonto Empresas</option>
                                            <option value="Unimed Norte Fluminense">Unimed Norte Fluminense</option>
                                            <option value="Unimed Norte Capixaba">Unimed Norte Capixaba</option>
                                            <option value="Lifeday">Lifeday</option>
                                            <option value="Verte">Verte</option>
                                            <option value="Cemeru">Cemeru</option>
                                            <option value="Onix">Ônix Saúde</option>
                                            <option value="New Leader">New Leader</option>
                                          </select>
                                          <div class="dropDownSelect2"></div>
                                      </div>
                                      <div class="rs-select2--light rs-select2--sm">
                                          <select class="js-select2" name="time" id="tipo" onchange="listar_material_filtro()">
                                              <option selected="selected" value="">Tipo</option>
                                              <option value="Aditivo">Aditivo</option>
                                              <option value="Campanha Vigente">Campanhas Vigentes</option>
                                              <option value="Tabela de Venda">Tabela de Vendas</option>
                                          </select>
                                          <div class="dropDownSelect2"></div>
                                      </div>
                                  </div>

                                  <div class="table-data__tool-right">
                                      <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#modal_material">
                                          <i class="zmdi zmdi-plus"></i>Anexar
                                      </button>
                                  </div>
                              </div>
                              <div class="table-responsive m-b-40">
                                <table class="table table-borderless table-data3" style="text-align: center;">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Operadora</th>
                                            <th>Tipo</th>
                                            <th>Data</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabela_materiais">

                                    </tbody>
                                </table>
                            </div>
                          </div>
                      </div>
                          <div class="row">
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

  <div class="modal fade" id="modal_material" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Anexar material de venda</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form method="POST" id="anexar_material" enctype="multipart/form-data">
              <p>
                <div class="form-group">
                  <label for="cc-name" class="control-label mb-1">Nome do Arquivo</label>
                  <input id="cc-name" name="nome" type="text" class="form-control cc-name valid" data-val="true">
                </div>

                <div class="form-group">
                  <label for="cc-name" class="control-label mb-1">Operadora</label>
                  <select name="operadora" id="select" class="form-control">
                    <option value="">Selecione</option>
                    <option value="Ideal Saude">Ideal Saúde</option>
                    <option value="Odonto Empresas">Odonto Empresas</option>
                    <option value="Unimed Norte Fluminense">Unimed Norte Fluminense</option>
                    <option value="Unimed Norte Capixaba">Unimed Norte Capixaba</option>
                    <option value="Lifeday">Lifeday</option>
                    <option value="Verte">Verte</option>
                    <option value="Cemeru">Cemeru</option>
                    <option value="Onix">Ônix Saúde</option>
                    <option value="New Leader">New Leader</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="cc-name" class="control-label mb-1">Tipo</label>
                  <select name="tipo" id="select" class="form-control">
                    <option value="">Selecione</option>
                    <option value="Aditivo">Aditivo</option>
                    <option value="Campanha Vigente">Campanha Vigente</option>
                    <option value="Tabela de Venda">Tabela de Venda</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="cc-name" class="control-label mb-1">Arquivo</label>
                  <div class="input-group">
                    <input id="cc-name" name="cc-name" type="text" class="form-control cc-name valid" data-val="true" readonly>
                    <label class="input-group-btn" style="height: 15px;">
                      <span class="btn btn-primary">
                        Escolher&hellip; <input type="file" name="file[]" id="file" style="display: none;" />
                      </span>
                    </label>
                  </div>
                  <span class="help-block">
                </p>
                <input type="hidden" name="funcao" value="anexar_material">
              </form>
            </div>
          </div>

          <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" onclick="anexar_material()">Confirmar</button>
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


    <!-- Main JS-->
    <script src="js/main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    <script> listar_material(); </script>
</body>

<style>

html, body{
  width: 100%;
  height:100%;
}

.section__content--p30 {
  padding: 0px;
}

@media (min-width: 992px){
  .modal-lg {
    max-width: 600px;
  }
}
</style>

</html>
<!-- end document-->
