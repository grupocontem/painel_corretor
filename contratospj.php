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
    <title>Propostas</title>

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
    <div class="page-wrapper">
        <?php include("header.php"); ?>
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- DATA TABLE -->
                                <h3 class="title-5 m-b-35">Propostas Cadastradas</h3>
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                        <div class="rs-select2--light rs-select2--md">
                                            <select class="js-select2" name="property" id="status_select" onChange="listar_contratos_pj();">
                                                <option selected="selected" value="">Status</option>
                                                <option value="Em Aberto">Em Aberto</option>
                                                <option value="Proposta Gerada">Proposta Gerada</option>
                                                <option value="Finalizada">Finalizada</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                        <div class="rs-select2--light rs-select2--sm">
                                            <select class="js-select2" name="time" id="operadora_select" onChange="listar_contratos_pj();">
                                                <option selected="selected" value="">Operadora</option>
                                                <option value="Cemeru">Cemeru</option>
                                                <option value="Lifeday">Lifeday</option>
                                                <option value="Verte">Verte</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                    </div>
                                    <div class="table-data__tool-right">
                                        <button class="au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" data-target="#cadastrar_proposta">
                                            <i class="zmdi zmdi-plus"></i>Incluir Proposta</button>
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
                                                <th>ID</th>
                                                <th>Empresa</th>
                                                <th>Operadora</th>
                                                <th>Data</th>
                                                <th>Status</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tabela_pj">

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

        <!-- Modal cadastrar proposta -->
        <div class="modal fade show" id="cadastrar_proposta" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" style="padding-right: 17px;">
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
                                <a class="nav-link active show" id="pills-home-tab" data-toggle="pill" href="#home" role="tab" aria-controls="pills-home" aria-selected="true">Dados da Empresa</a>
                            </li>
                        </ul>

                        <div class="tab-content pl-3 p-1" id="myTabContent">
                            <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <form action="" method="post" id="cadastrar_empresa">
                                    <!--<input type="hidden" name="codigo_corretor" value="<?php echo $tipo_corretor; ?>">
                                    <input type="hidden" name="id_corretor" value="<?php echo $current_user->ID; ?>">-->
                                    <input type="hidden" name="funcao" value="cadastrar_pj">

                                    <label for="exampleFormControlSelect1"><h5><b>Empresa (CNPJ):</b></h5></label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="cnpj" name="cnpj" onblur="api_cnpj(this.value)" aria-describedby="emailHelp" placeholder="CNPJ" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="razao_social" name="razao_social" aria-describedby="emailHelp" placeholder="Razão Social" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia" aria-describedby="emailHelp" placeholder="Nome Fantasia" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="insc_estadual" name="insc_estadual" aria-describedby="emailHelp" placeholder="Inscrição Estadual">
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="insc_municipal" name="insc_municipal" aria-describedby="emailHelp" placeholder="Inscrição Municipal">
                                            </div>
                                        </div>
                                    </div>

                                    <label for="exampleFormControlSelect1"><h5><b>Sócio / Representante Legal</b></h5></label><br>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="nome_socio" name="nome_socio" aria-describedby="emailHelp" placeholder="Nome Completo" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="cpf_socio" name="cpf_socio" aria-describedby="emailHelp" placeholder="CPF" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control telefone" id="telefone_socio" name="telefone_socio" aria-describedby="emailHelp" placeholder="Telefone" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="email_socio" name="email_socio" aria-describedby="emailHelp" placeholder="Email" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="cargo_socio" name="cargo_socio" aria-describedby="emailHelp" placeholder="Cargo" required>
                                    </div>

                                    <label for="exampleFormControlSelect1"><h5><b>Contato na Empresa: </b></h5></label><br>
                                    <input type="checkbox" onchange="check_contato_empresa()" style="margin-bottom: 20px;"><font color="red"> &nbspMarque para repetir dados acima</font>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="nome-contato-empresa" name="nome_contato_empresa" aria-describedby="emailHelp" placeholder="Nome Completo" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="email-contato-empresa" name="email_contato_empresa" aria-describedby="emailHelp" placeholder="Email de contato" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="cargo-contato-empresa" name="cargo_contato_empresa" aria-describedby="emailHelp" placeholder="Cargo" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control telefone" id="telefone-contato-empresa" name="telefone_contato_empresa" aria-describedby="emailHelp" placeholder="Telefone" required>
                                    </div>

                                    <label for="exampleFormControlSelect1"><h5> <b>Endereço (CNPJ) </b></h5></label><br>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="cep-empresa" name="cep_empresa" aria-describedby="emailHelp" placeholder="CEP" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="logradouro-empresa" name="logradouro_empresa" aria-describedby="emailHelp" placeholder="Logradouro" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="numero-empresa" name="numero_empresa" aria-describedby="emailHelp" placeholder="Número" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-9">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="complemento-empresa" name="complemento_empresa" aria-describedby="emailHelp" placeholder="Complemento">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="cidade-empresa" name="cidade_empresa" aria-describedby="emailHelp" placeholder="Cidade" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="bairro-empresa" name="bairro_empresa" aria-describedby="emailHelp" placeholder="Bairro" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="uf-empresa" name="uf_empresa" aria-describedby="emailHelp" placeholder="UF" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control telefone" id="telefone-empresa" name="telefone_empresa" aria-describedby="emailHelp" placeholder="Telefone (Empresa)">
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control telefone" id="telefone-celular" name="telefone_celular" aria-describedby="emailHelp" placeholder="Telefone (Celular)">
                                    </div>

                                    <label for="exampleFormControlSelect1"><h5> <b> Endereço de cobrança: </b></h5></label><br>
                                    <input type="checkbox" onchange="check_end_cobranca()" style="margin-bottom: 20px;"><font color="red"> &nbspMarque para repetir dados acima</font>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control cep" id="cep-cobranca" onblur="pesquisacep(this.value);" name="cep-cobranca" aria-describedby="emailHelp" placeholder="CEP" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="logradouro_cobranca" name="logradouro_cobranca" aria-describedby="emailHelp" placeholder="Logradouro" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="numero_cobranca" name="numero_cobranca" aria-describedby="emailHelp" placeholder="Número" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-9">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="complemento_cobranca" name="complemento_cobranca" aria-describedby="emailHelp" placeholder="Complemento">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="cidade_cobranca" name="cidade_cobranca" aria-describedby="emailHelp" placeholder="Cidade" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="bairro_cobranca" name="bairro_cobranca" aria-describedby="emailHelp" placeholder="Bairro" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="uf_empresa_cobranca" name="uf_empresa_cobranca" aria-describedby="emailHelp" placeholder="UF" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control telefone" id="telefone-empresa-cobranca" name="telefone_cobranca" aria-describedby="emailHelp" placeholder="Telefone (Cobrança)" required>
                                    </div>

                                    <label for="exampleFormControlSelect1"><b><h5>Informe os dados do plano: </h5></b></label><br>

                                    <div class="form-group">
                                        <select class="form-control" id="operadora" onchange="escolher_operadora(this.value)" name="operadora" required>
                                            <option value="">Selecione a operadora</option>
                                            <option value="LIFEDAY">LIFEDAY</option>
                                            <option value="CEMERU">CEMERU</option>
                                            <option value="VERTE">VERTE</option>
                                        </select>
                                    </div>

                                    <div class="form-group" id="vigencia">
                                        <select class='form-control' id='escolher_vigencia' name="escolher_vigencia" required>
                                            <option value=''>Selecione a vigência</option>\
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="nome_distribuidora" name="distribuidora" aria-describedby="emailHelp" placeholder="Informe o nome da distribuidora" required>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-light button_orange" onclick="cadastrar_proposta()">Cadastrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal editar proposta -->
        <div class="modal fade show" id="editarpj_modal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" style="padding-right: 17px;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="mediumModalLabel">Cadastrar Proposta</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="" method="post" id="pj_editar">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                <a class="nav-link active show" id="pills-home-tab" data-toggle="pill" href="#home" role="tab" aria-controls="pills-home" aria-selected="true">Dados da Empresa</a>
                                </li>
                            </ul>

                            <div class="tab-content pl-3 p-1" id="myTabContent">
                                <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <input type="hidden" name="funcao" value="editar_pj">

                                    <label for="exampleFormControlSelect1"><h5><b>Empresa (CNPJ):</b></h5></label><br>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="cnpj-editar" name="cnpj" aria-describedby="emailHelp" placeholder="CNPJ" required readonly>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="razao_social-editar" name="razao_social" aria-describedby="emailHelp" placeholder="Razão Social" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="nome_fantasia-editar" name="nome_fantasia" aria-describedby="emailHelp" placeholder="Nome Fantasia" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="insc_estadual-editar" name="insc_estadual" aria-describedby="emailHelp" placeholder="Inscrição Estadual">
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="insc_municipal-editar" name="insc_municipal" aria-describedby="emailHelp" placeholder="Inscrição Municipal">
                                            </div>
                                        </div>
                                    </div>

                                    <label for="exampleFormControlSelect1"><h5><b>Sócio / Representante Legal </b></h5></label><br>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="nome_socio-editar" name="nome_socio" aria-describedby="emailHelp" placeholder="Nome Completo" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="cpf_socio-editar" name="cpf_socio" aria-describedby="emailHelp" placeholder="CPF" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control telefone" id="telefone_socio-editar" name="telefone_socio" aria-describedby="emailHelp" placeholder="Telefone" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="email_socio-editar" name="email_socio" aria-describedby="emailHelp" placeholder="Email" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="cargo_socio-editar" name="cargo_socio" aria-describedby="emailHelp" placeholder="Cargo" required>
                                    </div>

                                    <label for="exampleFormControlSelect1"><h5><b>Contato na Empresa: </b></h5></label><br>
                                    <input type="checkbox" id="check_contato_empresa_editar" style="margin-bottom: 20px;"><font color="red"> &nbspMarque para repetir dados acima</font>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="nome-contato-empresa-editar" name="nome_contato_empresa" aria-describedby="emailHelp" placeholder="Nome Completo">
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="email-contato-empresa-editar" name="email_contato_empresa" aria-describedby="emailHelp" placeholder="Email de contato" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="cargo-contato-empresa-editar" name="cargo_contato_empresa" aria-describedby="emailHelp" placeholder="Cargo" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control telefone" id="telefone-contato-empresa-editar" name="telefone_contato_empresa" aria-describedby="emailHelp" placeholder="Telefone" required>
                                    </div>

                                    <label for="exampleFormControlSelect1"><h5> <b>Endereço (CNPJ) </b></h5></label><br>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="cep_empresa-editar" onblur="pesquisacep2(this.value);" name="cep_empresa"  aria-describedby="emailHelp" placeholder="CEP" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="logradouro_empresa-editar" name="logradouro_empresa" aria-describedby="emailHelp" placeholder="Logradouro" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="numero_empresa-editar" name="numero_empresa" aria-describedby="emailHelp" placeholder="Número" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-9">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="complemento_empresa-editar" name="complemento_empresa" aria-describedby="emailHelp" placeholder="Complemento">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="cidade_empresa-editar" name="cidade_empresa" aria-describedby="emailHelp" placeholder="Cidade" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="bairro_empresa-editar" name="bairro_empresa" aria-describedby="emailHelp" placeholder="Bairro" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="uf_empresa-editar" name="uf_empresa" aria-describedby="emailHelp" placeholder="UF" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control telefone" id="telefone_empresa-editar" name="telefone_empresa" aria-describedby="emailHelp" placeholder="Telefone (Empresa)" required>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control telefone" id="telefone_celular-editar" name="telefone_celular" aria-describedby="emailHelp" placeholder="Telefone (Celular)">
                                    </div>

                                    <label for="exampleFormControlSelect1"><h5> <b>Endereço de Cobrança: </b></h5></label><br>
                                    <input type="checkbox" id="check_end_cobranca-editar" style="margin-bottom: 20px;"><font color="red"> &nbspMarque para repetir dados acima</font>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control cep" id="cep_cobranca-editar" onblur="pesquisacep2(this.value);" name="cep-cobranca" aria-describedby="emailHelp" placeholder="CEP" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="logradouro_cobranca-editar" name="logradouro_cobranca" aria-describedby="emailHelp" placeholder="Logradouro">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="numero_cobranca-editar" name="numero_cobranca" aria-describedby="emailHelp" placeholder="Número" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-9">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="complemento_cobranca-editar" name="complemento_cobranca" aria-describedby="emailHelp" placeholder="Complemento">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="cidade_cobranca-editar" name="cidade_cobranca" aria-describedby="emailHelp" placeholder="Cidade" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="bairro_cobranca-editar" name="bairro_cobranca" aria-describedby="emailHelp" placeholder="Bairro" required>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="uf_cobranca-editar" name="uf_empresa_cobranca" aria-describedby="emailHelp" placeholder="UF" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" class="form-control" id="telefone_cobranca-editar" name="telefone_cobranca" aria-describedby="emailHelp" placeholder="Telefone (Cobrança)" required>
                                    </div>

                                    <?php if($tipo_usuario == 'ADMIN') { ?>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="cod_corretor" aria-describedby="emailHelp" placeholder="Código Corretor" readonly>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-light button_orange" onclick="alterar_proposta()">Alterar</button>
                    </div>
                </div>
            </div>
        </div>

        <!--Modal documentos da empresa -->
        <div class="modal fade show" id="anexar_doc_pj" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" style="padding-right: 17px;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="mediumModalLabel">Documentos da Empresa</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form method="post" action="" id="anexar_documentos_form" enctype="multipart/form-data">
                            <ul class="" style="margin-left: 25px;">
                                <h4> Documentos necessários: </h4><br>
                                <li>Contrato social (Se Houver);</li>
                                <li>Requerimento de empresário;</li>
                                <li>Cartão de CNPJ;</li>
                                <li>Comprovante de endereço do estabelecimento;</li>
                                <li>Extrato completo do FGTS (para inclusão dos funcionários);</li>
                            </ul>
                            <br>

                            <div class="form-group">
                                <div class="input-group">
                                    <input id="cc-name" name="cc-name" type="text" class="form-control cc-name valid" data-val="true" readonly>
                                    <label class="input-group-btn" style="height: 15px;">
                                    <span class="btn button_orange">
                                        Escolher&hellip; <input type="file" name="file[]" id="file" style="display: none;" />
                                    </span>
                                    </label>
                                </div>
                                <span class="help-block">

                                <br><br><h4> Documentos anexados: </h4><br>

                                <div class="doc_anexados" id="doc_anexados">

                                </div>

                                <input type="hidden" step="0" id="id_anexo_modal" name="id_anexo">
                                <input type="hidden" value="anexar_documento_pj" name="funcao">
                                <br>
                            </div>
                        </form>
                    </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-light button_orange" id="button_enviar_anexo_pj" onclick="enviar_anexo_pj()">Anexar</button>
              </div>
            </div>
          </div>
        </div>
      </div>

        <!-- Modal finalizar proposta -->
        <div class="modal fade show" id="modal_finalizar" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" style="padding-right: 17px;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="mediumModalLabel">Finalizar Proposta</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="pills-home-tab" data-toggle="pill" href="#enviar_proposta" role="tab" aria-controls="pills-home" aria-selected="true">Enviar Proposta</a>
                            </li>
                        </ul>

                        <div class="tab-content pl-3 p-1" id="myTabContent">
                            <div class="tab-pane fade active show" id="enviar_proposta" role="tabpanel" aria-labelledby="home-tab">
                                <br><b>Para finalizar a proposta você deve anexar a proposta assinada e digitalizada!</b>
                                <form method="post" action="" id="anexar_documentos_form2" enctype="multipart/form-data">
                                    <div class="input-group" style="margin-top: 30px;">
                                        <input type="text" class="form-control" id="file_finalizar" readonly>
                                        <label class="input-group-btn" style="height: 15px;">
                                            <span class="btn btn-primary">
                                                Escolher&hellip; <input type="file" name="file[]" id="file_finalizar" multiple style="display: none;" />
                                            </span>
                                        </label>
                                    </div>
                                    <span class="help-block"><br>
                                </form>
                                <input type="hidden" id="id_proposta_finalizar" name="id_proposta">
                                <input type="hidden" value="finalizar_proposta" name="funcao">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-light button_orange" onclick="finalizar_proposta()" id="finalizar"> Enviar </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal PDF'S -->
        <!--<div class="modal fade show" id="modal_pdfs" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" style="padding-right: 17px;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="mediumModalLabel">PDF do Contrato</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" id="pills-home-tab" data-toggle="pill" href="#pdfs" role="tab" aria-controls="pills-home" aria-selected="true">Arquivos</a>
                            </li>
                        </ul>

                        <div class="tab-content pl-3 p-1" id="myTabContent">
                            <div class="tab-pane fade active show" id="pdfs" role="tabpanel" aria-labelledby="home-tab">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th scope="col">Empresa</th>
                                            <th scope="col">Ações</th>
                                        </tr>
                                    </thead>

                                    <tbody id="dados_empresa">

                                    </tbody>
                                 </table>

                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th scope="col">Beneficiário</th>
                                            <th scope="col">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="dados_benefic">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>-->
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
    <script src="vendor/counter-up/jquery.counterup.min.js"></script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="js/main.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script> listar_contratos_pj(); </script>
    <script> listar_produtos(); </script>
    <script> var tipo_do_usuario = '<?php echo $codigo_corretor ?>'; </script>
    <script> var tipo_user = '<?php echo $tipo_usuario ?>'; </script>
</body>

<script>

jQuery('#cnpj').mask('99.999.999/9999-99');
jQuery('#cpf_socio').mask('999.999.999-99');
jQuery('#telefone_socio').mask('(99) 99999-9999');
jQuery('#telefone-contato-empresa').mask('(99) 99999-9999');
jQuery('#tel_res').mask('(99) 9999-9999');
jQuery('#tel_cel').mask('(99) 99999-9999');
jQuery('#telefone-celular').mask('(99) 99999-9999');
jQuery('#telefone-empresa').mask('(99) 99999-9999');
jQuery('#telefone-empresa-cobranca').mask('(99) 99999-9999');
jQuery('#cep-cobranca').mask('99999-999');

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

.dropdown-submenu {
    position: relative;
}

.multi-level{
    width: 230px;
    transform: translate3d(-187px, 38px, 0px)!important;
}

.dropdown-submenu>.dropdown-menu {
    top: 0;
    left: -200px;
    margin-top: -6px;
    margin-left: -1px;
    -webkit-border-radius: 0 6px 6px 6px;
    -moz-border-radius: 0 6px 6px;
    border-radius: 0 6px 6px 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
    display: block;
}

.dropdown-submenu:hover>a:after {
    border-left-color: #fff;
}

.dropdown-submenu.pull-left {
    float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
    left: 100%;
    margin-left: 10px;
    -webkit-border-radius: 6px 0 6px 6px;
    -moz-border-radius: 6px 0 6px 6px;
    border-radius: 6px 0 6px 6px;
}

.dropdown-toggle::after {
    margin-left: 0;
}

.table-data3 tbody td {
    padding-top: 22px;
}

.table-data3 tbody tr td:last-child {
    padding-top: 15px;
}

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
