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
                <h3 class="title-5 m-b-35">Beneficiários cadastrados</h3>
                <div class="table-data__tool">
                  <div class="table-data__tool-left">
                  </div>
                  <div class="table-data__tool-right">
                    <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#modal_benefic">
                    <i class="zmdi zmdi-plus"></i>Beneficiário</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="row m-t-30">
              <div class="col-md-12">
                <div class="table-responsive m-b-40">
                  <table class="table table-borderless table-data3" style="text-align: center;">
                    <thead>
                      <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Tipo</th>
                        <th>Ações</th>
                      </tr>
                    </thead>
                      <tbody id="tabela">

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


<div class="modal fade show" id="modal_benefic" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" style="padding-right: 17px;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="mediumModalLabel">Cadastrar Proposta</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
        <div class="modal-body">
          <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
              <li class="nav-item">
                  <a class="nav-link active show" id="pills-home-tab" data-toggle="pill" href="#titular" role="tab" aria-controls="pills-home" aria-selected="true">Titular</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#dependente" role="tab" aria-controls="pills-profile" aria-selected="false">Dependentes</a>
              </li>
          </ul>

            <form method="post" id="dados_benefic">

            <div class="tab-content pl-3 p-1" id="myTabContent">
                <div class="tab-pane fade active show" id="titular" role="tabpanel" aria-labelledby="home-titular">

                <div class="form-group">
                    <input type="text" class="form-control cpf-benefic" id="cpf-benefic" placeholder="CPF" name="cpf_benefic" onblur="api_cpf(this.value)" aria-describedby="emailHelp" value="">
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="nome" onkeyup="convertToUppercase(this)" placeholder="Nome completo" name="nome" aria-describedby="emailHelp" value="" readonly>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="nome_mae" onkeyup="convertToUppercase(this)" placeholder="Nome completo da mãe" name="nome_mae" aria-describedby="emailHelp" value="" readonly>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="nascimento" placeholder="Data de nascimento" name="nascimento" aria-describedby="emailHelp" value="" readonly>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-4">
                    <div class="form-group">
                        <select class="form-control" name="sexo" id="sexo" readonly>
                        <option value="">Sexo</option>
                        <option value="MASCULINO">Masculino</option>
                        <option value="FEMININO">Feminino</option>
                        </select>
                    </div>
                    </div>

                    <div class="col-xs-12 col-sm-4">
                    <div class="form-group">
                        <select class="form-control" name="estado_civil" id="estado_civil">
                        <option value="">Estado Civil</option>
                        <option value="SOLTEIRO">Solteiro (a)</option>
                        <option value="CASADO">Casado (a)</option>
                        <option value="SEPARADO">Separado (a)</option>
                        <option value="DIVORCIADO">Divorciado (a)</option>
                        <option value="VIUVO">Viúvo (a)</option>
                        </select>
                    </div>
                    </div>

                    <div class="col-xs-12 col-sm-4">
                    <div class="form-group">
                        <input type="text" class="form-control" id="naturalidade" name="naturalidade" placeholder="Naturalidade" onkeyup="convertToUppercase(this)" aria-describedby="emailHelp" value="">
                    </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="rg" placeholder="RG" name="rg" aria-describedby="emailHelp" value="">
                    </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="orgao" placeholder="Orgão Expedidor" name="orgao" onkeyup="convertToUppercase(this)" aria-describedby="emailHelp" value="">
                    </div>
                    </div>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="cep" onblur="pesquisacep(this.value);" name="cep" placeholder="CEP" aria-describedby="emailHelp" value="">
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="rua" placeholder="Rua" name="rua" aria-describedby="emailHelp" readonly value="">
                </div>

                <div class="row">

                    <div class="col-xs-12 col-sm-3">
                    <div class="form-group">
                        <input type="text" class="form-control" id="numero" name="numero" aria-describedby="emailHelp" placeholder="Número" required value="">
                    </div>
                    </div>

                    <div class="col-xs-12 col-sm-9">
                    <div class="form-group">
                        <input type="text" class="form-control" id="complemento" placeholder="Complemento" name="complemento" onkeyup="convertToUppercase(this)" aria-describedby="emailHelp" value="">
                    </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-4">
                    <div class="form-group">
                        <input type="text" class="form-control" id="cidade" placeholder="Cidade" name="cidade" aria-describedby="emailHelp" readonly value="">
                    </div>
                    </div>

                    <div class="col-xs-12 col-sm-4">
                    <div class="form-group">
                        <input type="text" class="form-control" id="bairro" placeholder="Bairro" name="bairro" aria-describedby="emailHelp" readonly value="">
                    </div>
                    </div>

                    <div class="col-xs-12 col-sm-4">
                    <div class="form-group">
                        <input type="text" class="form-control" id="uf" placeholder="UF" name="uf" aria-describedby="emailHelp" readonly value="">
                    </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="tel_res" placeholder="Telefone Residencial" name="tel_res" aria-describedby="emailHelp" value="">
                    </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                    <div class="form-group">
                        <input type="text" class="form-control" id="tel_cel" placeholder="Telefone Celular" name="tel_cel" aria-describedby="emailHelp" value="">
                    </div>
                    </div>
                </div>

                <div class="form-group">
                    <input type="email" class="form-control" id="email" placeholder="Endereço de email" name="email" aria-describedby="emailHelp" value="">
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" id="sus" placeholder="Cartão nacional do SUS" name="sus" aria-describedby="emailHelp" value="">
                </div>

                <div class="form-group">
                    <select class="form-control" name="produto" id="produto">
                    <option value="">SELECIONE</option>
                    </select>
                </div>

                <input type="hidden" name="proposta" value="<?php echo''.$proposta;?>">
                <input type="hidden" name="qtd_dep" id="qtd_dep" value="">
                <input type="hidden" name="funcao" value="cadastrar_benefic">

                </form>

                </div>

            <div class="tab-pane fade" id="dependente" role="tabpanel" aria-labelledby="home-dependente">
                <div class="dependente-append">

                </div>

                <div class="add_dependente" style="text-align: right; margin-bottom: 15px;">
                    <button type="button" class="btn btn-warning" id="add_dep_button" onclick="add_dep()"> <i class="zmdi zmdi-plus"></i> Dependente </button>
                </div>
            </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-success" id="button_cadastrar" disabled onclick="cadastrar_benefic()">Cadastrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_editar" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" style="padding-right: 17px;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="mediumModalLabel">Editar Proposta</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>

      <div class="modal-body">
        <ul class="nav nav-pills mb-3" id="pills-tab2" role="tablist">
            <li class="nav-item">
                <a class="nav-link active show" id="pills-home-tab2" data-toggle="pill" href="#titular-editar" role="tab" aria-controls="pills-home" aria-selected="true">Titular</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-profile-tab2" data-toggle="pill" href="#dependente-editar" role="tab" aria-controls="pills-profile" aria-selected="false">Dependentes</a>
            </li>
        </ul>

        <form method="post" action="" id="editar-benefic">
        <div class="tab-content pl-3 p-1" id="myTabContent">
          <div class="tab-pane fade active show" id="titular-editar" role="tabpanel" aria-labelledby="home-titular">
            <div class="form-group">
              <input type="text" class="form-control cpf-benefic" id="cpf-benefic_editar" placeholder="CPF" name="cpf_benefic_editar" aria-describedby="emailHelp" value="" readonly>
            </div>

            <div class="form-group">
              <input type="text" class="form-control" id="nome_editar" placeholder="Nome completo" name="nome_editar" aria-describedby="emailHelp" value="" readonly>
            </div>

            <div class="form-group">
              <input type="text" class="form-control" id="nome_mae_editar" placeholder="Nome completo da mãe" name="nome_mae_editar" aria-describedby="emailHelp" value="" readonly>
            </div>

            <div class="form-group">
              <input type="text" class="form-control" id="nascimento_editar" placeholder="Data de nascimento" name="nascimento_editar" aria-describedby="emailHelp" value="" readonly>
            </div>

            <div class="row">
              <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                  <input type="text" class="form-control" id="sexo_editar" placeholder="Sexo" name="sexo_editar" aria-describedby="emailHelp" value="" readonly>
                </div>
              </div>

              <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                  <select class="form-control" name="estado_civil_editar" id="estado_civil_editar">
                    <option value="">Estado Civil</option>
                    <option value="SOLTEIRO">Solteiro (a)</option>
                    <option value="CASADO">Casado (a)</option>
                    <option value="SEPARADO">Separado (a)</option>
                    <option value="DIVORCIADO">Divorciado (a)</option>
                    <option value="VIUVO">Viúvo (a)</option>
                  </select>
                </div>
              </div>

              <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                  <input type="text" class="form-control" id="naturalidade_editar" name="naturalidade_editar" placeholder="Naturalidade" onkeyup="convertToUppercase(this)" aria-describedby="emailHelp" value="">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12 col-sm-6">
                <div class="form-group">
                  <input type="text" class="form-control" id="rg_editar" placeholder="RG" name="rg_editar" aria-describedby="emailHelp" value="">
                </div>
              </div>

              <div class="col-xs-12 col-sm-6">
                <div class="form-group">
                  <input type="text" class="form-control" id="orgao_editar" placeholder="Orgão Expedidor" name="orgao_editar" onkeyup="convertToUppercase(this)" aria-describedby="emailHelp" value="">
                </div>
              </div>
            </div>

            <div class="form-group">
              <input type="text" class="form-control" id="cep_editar" onblur="pesquisacep2(this.value);" name="cep_editar" placeholder="CEP" aria-describedby="emailHelp" value="">
            </div>

            <div class="form-group">
              <input type="text" class="form-control" id="rua_editar" placeholder="Rua" name="rua_editar" aria-describedby="emailHelp" readonly value="">
            </div>

            <div class="row">

              <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                  <input type="text" class="form-control" id="numero_editar" name="numero_editar" aria-describedby="emailHelp" placeholder="Número" required value="">
                </div>
              </div>

              <div class="col-xs-12 col-sm-9">
                <div class="form-group">
                  <input type="text" class="form-control" id="complemento_editar" placeholder="Complemento" name="complemento_editar" onkeyup="convertToUppercase(this)" aria-describedby="emailHelp" value="">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                  <input type="text" class="form-control" id="cidade_editar" placeholder="Cidade" name="cidade_editar" aria-describedby="emailHelp" readonly value="">
                </div>
              </div>

              <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                  <input type="text" class="form-control" id="bairro_editar" placeholder="Bairro" name="bairro_editar" aria-describedby="emailHelp" readonly value="">
                </div>
              </div>

              <div class="col-xs-12 col-sm-4">
                <div class="form-group">
                  <input type="text" class="form-control" id="uf_editar" placeholder="UF" name="uf_editar" aria-describedby="emailHelp" readonly value="">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12 col-sm-6">
                <div class="form-group">
                  <input type="text" class="form-control" id="tel_res_editar" placeholder="Telefone Residencial" name="tel_res_editar" aria-describedby="emailHelp" value="">
                </div>
              </div>

              <div class="col-xs-12 col-sm-6">
                <div class="form-group">
                  <input type="text" class="form-control" id="tel_cel_editar" placeholder="Telefone Celular" name="tel_cel_editar" aria-describedby="emailHelp" value="">
                </div>
              </div>
            </div>

            <div class="form-group">
              <input type="email" class="form-control" id="email_editar" placeholder="Endereço de email" name="email_editar" aria-describedby="emailHelp" value="">
            </div>

            <div class="form-group">
              <input type="text" class="form-control" id="sus_editar" placeholder="Cartão nacional do SUS" name="sus_editar" aria-describedby="emailHelp" value="">
            </div>

            <div class="form-group">
              <select class="form-control" name="produto_editar" id="produto_editar">
                <option value="">SELECIONE</option>
              </select>
            </div>

            <input type="hidden" name="proposta_editar" value="<?php echo''.$proposta ?>">
            <input type="hidden" name="qtd_dep_editar" id="qtd_dep_editar" value="">
            <input type="hidden" name="funcao" value="editar_benefic">
          </div>

          <div class="tab-pane fade" id="dependente-editar" role="tabpanel" aria-labelledby="home-dependente">
            <div class="dependente-append-editar">

            </div>
          </div>
        </div>
      </form>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="button_cadastrar" onclick="alterar_benefic()">Cadastrar</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="anexos" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" style="padding-right: 17px;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="mediumModalLabel">Arquivos do beneficiário</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>

      <div class="modal-body">
        <ul class="nav nav-pills mb-3" id="pills-tab2" role="tablist">
            <li class="nav-item">
                <a class="nav-link active show" id="pills-home-tab2" data-toggle="pill" href="#arquivos" role="tab" aria-controls="pills-home" aria-selected="true">Arquivos</a>
            </li>
        </ul>

        <div class="tab-content pl-3 p-1" id="myTabContent">
          <div class="tab-pane fade active show" id="arquivos" role="tabpanel" aria-labelledby="home-titular">
            <form method="post" action="" id="anexar_documentos_form" enctype="multipart/form-data">
            <h4> Documentos necessários: </h4><br>
              <li>Doc. de identificação oficial com foto;</li>
              <li>CPF;</li>
              <li>Comprovante de residência;</li>
              <li>Carteira de trabalho e previdência social;</li>
              <li>Extrato do FGTS com guia de pagamento quitada;</li>
              <li>Declaração de união estável (dependente);</li>
              <li>Documento de guarda (Tutela/Curateia e/ou Adoção) (dependente);</li>

            <div class="input-group" style="margin-top: 20px;">
              <input type="text" class="form-control" readonly>
                <label class="input-group-btn" style="height: 15px;">
                  <span class="btn btn-primary">
                    Escolher&hellip; <input type="file" name="file[]" id="file" style="display: none;" />
                  </span>
                </label>
              </div>
            <span class="help-block">

            <br><h4> Documentos anexados: </h4><br>
            <div class="doc_anexados" id="doc_anexados"><br>

            </div>

            <input type="hidden" step="0" id="cpf_anexo_modal" name="cpf_anexo">
            <input type="hidden" value="anexar_documento" name="funcao">
          </div>
        </div>
      </form>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-success" id="anexar_doc_benefic" onclick="anexar_documento()"> Enviar </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="dependentes_add" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" style="padding-right: 17px;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="mediumModalLabel">Arquivos do beneficiário</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>

      <div class="modal-body">
        <ul class="nav nav-pills mb-3" id="pills-tab2" role="tablist">
            <li class="nav-item">
                <a class="nav-link active show" id="pills-home-tab2" data-toggle="pill" href="#add_dep" role="tab" aria-controls="pills-home" aria-selected="true">Arquivos</a>
            </li>
        </ul>

        <div class="tab-content pl-3 p-1" id="myTabContent">
          <div class="tab-pane fade active show" id="add_dep" role="tabpanel" aria-labelledby="home-titular">
            <form method="post" action="" id="adicionar_dependente">

              <div class="form-group">
                <input type="text" class="form-control" id="cpf_dep_add" placeholder="CPF" name="cpf_dep_add" aria-describedby="emailHelp" value="">
              </div>

              <div class="form-group">
                <input type="text" class="form-control" id="nome_dep_add" placeholder="Nome completo" name="nome_dep_add" aria-describedby="emailHelp" value="">
              </div>

              <div class="form-group">
                <input type="text" class="form-control" id="nome_mae_dep_add" placeholder="Nome completo da mãe" name="nome_mae_dep_add" aria-describedby="emailHelp" value="">
              </div>

              <div class="form-group">
                <input type="text" class="form-control" id="nascimento_dep_add" placeholder="Data de nascimento" name="nascimento_dep_add" aria-describedby="emailHelp" value="">
              </div>

              <div class="form-group">
                <input type="text" class="form-control" id="dnv_dep_add" placeholder="Declaração de nascido vivo" name="dnv_dep_add" aria-describedby="emailHelp" value="">
              </div>

              <div class="form-group">
                <input type="text" class="form-control" id="sus_dep_add" placeholder="Número do cartão nacional do SUS" name="sus_dep_add" aria-describedby="emailHelp" value="">
              </div>

              <div class='row'>
                <div class='col-xs-12 col-sm-4'>
                  <div class='form-group'>
                    <select class='form-control' name='sexo_dep_add' id='sexo_dep_add'>
                      <option selected value=''>Sexo</option>
                      <option value='MASCULINO'>Masculino</option>
                      <option value='FEMININO'>Feminino</option>
                    </select>
                  </div>
                </div>

                <div class='col-xs-12 col-sm-4'>
                  <div class='form-group'>
                    <select class='form-control' name='estado_civil_dep_add' id='estado_civil_dep_add'>\
                      <option selected value=''>Estado Civil</option>
                      <option value='SOLTEIRO'>Solteiro (a)</option>
                      <option value='CASADO'>Casado (a)</option>
                      <option value='SEPARADO'>Separado (a)</option>
                      <option value='DIVORCIADO'>Divorciado (a)</option>
                      <option value='VIUVO'>Viúvo (a)</option>
                    </select>
                  </div>
                </div>

                <div class='col-xs-12 col-sm-4'>
                  <div class='form-group'>
                    <select class="form-control" name="parentesco_dep_add" id='parentesco_dep_add'>
                      <option value=''>Parentesco</option>
                      <option value='1'>Titular</option>
                      <option value='2'>Agregado(a)</option>
                      <option value='3'>Companheiro(a)</option>
                      <option value='4'>Cônjugue</option>
                      <option value='5'>Filho(a)</option>
                      <option value='6'>Filho(a) Adotivo</option>
                      <option value='7'>Irmão(a)</option>
                      <option value='8'>Mãe</option>
                      <option value='9'>Pai</option>
                      <option value='10'>Neto(a)</option>
                      <option value='11'>Sobrinho(a)</option>
                      <option value='12'>Sogro</option>
                      <option value='13'>Sogra</option>
                      <option value='14'>Enteado(a)</option>
                      <option value='15'>Tutelado</option>
                      <option value='16'>Genro</option>
                      <option value='17'>Nora</option>
                      <option value='18'>Cunhado(a)</option>
                      <option value='19'>Primo(a)</option>
                      <option value='20'>Avô</option>
                      <option value='21'>Avó</option>
                    </select>
                  </div>
                </div>
                <input type="hidden" value="add_dep" name="funcao" id="funcao">
                <input type="hidden" value="" name="cpf_titular" id="cpf_titular">
              </form>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-success" id="cadastrar_dep" onclick="cadastrar_dependente()"> Cadastrar </button>
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

validar_corretor();

function validar_corretor() {
    var codigo_corretor = '<?php echo $codigo_corretor ?>';
    var codigo_proposta = '<?php echo $codigo_proposta ?>';
    var tipo_usuario = '<?php echo $tipo_usuario ?>';
    
    if(codigo_corretor == codigo_proposta || tipo_usuario == 'ADMIN'){
        listar_beneficiarios ();
    } else {
        swal({
            title: "Opa!",
            text: "Você não tem permissão para alterar essa proposta!",
            icon: "error",
            buttons: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                window.location.href="https://painel.grupocontem.com.br/contratospj.php";
            } else {
                window.location.href="https://painel.grupocontem.com.br/contratospj.php";
            }
        });
    }
}

jQuery('#cpf-benefic').mask('999.999.999-99');
jQuery('#cpf_dep_add').mask('999.999.999-99');
jQuery('#cep').mask('99999-999');
jQuery('#tel_res').mask('(99) 9999-9999');
jQuery('#tel_cel').mask('(99) 99999-9999');

function limpa_formulário_cep() {
  //Limpa valores do formulário de cep.
  document.getElementById('rua').value=("");
  document.getElementById('cidade').value=("");
  document.getElementById('bairro').value=("");
  document.getElementById('uf').value=("");
}

function meu_callback(conteudo) {
    if (!("erro" in conteudo)) {
    //Atualiza os campos com os valores.
    document.getElementById('rua').value=(conteudo.logradouro);
    document.getElementById('cidade').value=(conteudo.bairro);
    document.getElementById('bairro').value=(conteudo.localidade);
    document.getElementById('uf').value=(conteudo.uf);
    } else {
    //CEP não Encontrado.
    limpa_formulário_cep();
    alert("CEP não encontrado.");
    }
}

function pesquisacep(valor) {
    var cep = valor.replace(/\D/g, '');

    //Verifica se campo cep possui valor informado.
    if (cep != "") {

    //Expressão regular para validar o CEP.
    var validacep = /^[0-9]{8}$/;

    //Valida o formato do CEP.
    if(validacep.test(cep)) {

        //Preenche os campos com "..." enquanto consulta webservice.
        document.getElementById('rua').value="...";
        document.getElementById('cidade').value="...";
        document.getElementById('bairro').value="...";
        document.getElementById('uf').value="...";

        //Cria um elemento javascript.
        var script = document.createElement('script');

        //Sincroniza com o callback.
        script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

        //Insere script no documento e carrega o conteúdo.
        document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
        //cep sem valor, limpa formulário.
        limpa_formulário_cep();
        }
    };


jQuery(document).ready(function(){
    var operadora = "<?php echo''.$dado['operadora']; ?>";
    var proposta = "<?php echo''.$proposta; ?>";
    var funcao = 'listar_produtos';

    $.ajax({
        type: 'POST',
        url: 'functions.php',
        async: true,
        dataType: 'json',
        data: {'operadora': operadora, 'proposta': proposta, 'funcao': funcao},
        error: function() {
        alert("Error");
        },
        success: function(result)
        {
        $("#produto").empty();
        $("#produto").append("<option value=''>SELECIONE</option>");

        $("#produto_editar").empty();
        $("#produto_editar").append("<option value=''>SELECIONE</option>");

        for(var i=0; i<=result.length; i++){
            var id_plano = result[i].id;
            var plano = result[i].nome_plano;

            $("#produto").append('<option value="'+id_plano+'">'+plano+'</option>');
            $("#produto_editar").append('<option value="'+id_plano+'">'+plano+'</option>');
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

jQuery("#cpf_dep_add").on('blur',function(){
  var cpf = $(this).val();
  var cpf = cpf.replace('.', '');
  var cpf = cpf.replace('.', '');
  var cpf = cpf.replace('-', '');
  //console.log(cpf);

  if(cpf.length > 10){
  $.ajax({
    url: "https://api.cpfcnpj.com.br/6c03bf21c7f1c9448ee7802839bd7609/2/"+cpf+"",
    type: 'POST',
    dataType: 'json',
    async: true,
    data: {id:cpf},
    error: function() {
      /*$('.nome_dep'+dep_cod).val("");
      $('.nascimento_dep'+dep_cod).val("");
      $('.nome_mae_dep'+dep_cod).val("");
      //$(".sexo_dep"+dep_cod).val("");*/
      alert("CPF não encontrado");
    },
    success: function(json)
    {
        var nome = json.nome;
        var nascimento = json.nascimento;
        var mae = json.mae;
        var genero = json.genero;

        $('#nome_dep_add').val(nome.toUpperCase());
        $('#nome_mae_dep_add').val(mae.toUpperCase());
        $('#nascimento_dep_add').val(nascimento);

        if(genero == "M"){
          $("#sexo_dep_add").val("MASCULINO");
        } else if(genero == "F"){
          $("#sexo_dep_add").val("FEMININO");
        } else {

        }

        $('#nome_dep_add').prop('readonly', true);
        $('#nome_mae_dep_add').prop('readonly', true);
        $('#nascimento_dep_add').prop('readonly', true);
        $("#sexo_dep_add").attr("readonly", true);
      }
    });
  }
  return false;
});

function abrir_modal_cad_dep (cpf_titular){
  $('#cpf_titular').val(cpf_titular);
}

</script>


<style>

label{
  margin-top: 15px;
  margin-bottom: 15px;
}

.carregando{
  width: 30px;
  margin-top: -3px;
}

.form-group {
  margin-bottom: 0rem;
}

.form-control {
  margin-top: 15px;
  margin-bottom: 15px;
}

.dropdown-toggle{
  background-color: #f2562f;
  color: white;
}

.dropdown-item:hover{
  background-color: #f2562f;
  color: white;
}

.row{
  margin-top: -15px;
}

.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
    color: #fff;
    background-color: #f2552e;
}

.pl-3, .px-3 {
  padding-left: 0px!important;
}

.p-1 {
    padding: .0rem!important;
}
</style>

</html>
