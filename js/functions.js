// Funções da dashboard //

function contratos_cadastrados (){
    var funcao = 'contratos_cadastrados';
  
    $.ajax({
        type: 'POST',
        url: 'functions.php',
        async: true,
        dataType: 'json',
        data: {'funcao': funcao},
        error: function() {
            swal("Error!", "Verifique sua conexão!", "error");
        },
        success: function(result){
            $('.qtd_contratos').html($.trim(result));
        }
    });
}

function contratos_cadastrados_mensal (){
    var funcao = 'contratos_cadastrados_mensal';
  
    $.ajax({
        type: 'POST',
        url: 'functions.php',
        async: true,
        dataType: 'json',
        data: {'funcao': funcao},
        error: function() {
            swal("Error!", "Verifique sua conexão!", "error");
        },
        success: function(result){
            $('.qtd_contratos_mensal').html($.trim(result));
        }
    });
}

// Funções de criação, edição e listagem de propostas//

function convertToUppercase(el) {
  if(!el || !el.value) return;
  el.value = el.value.toUpperCase();
} // Deixa o campo com todas as letras em maiúsculo //

function api_cnpj() {
  var cnpj = $('#cnpj').val();
  var cnpj = cnpj.replace('.', '');
  var cnpj = cnpj.replace('.', '');
  var cnpj = cnpj.replace('-', '');
  var cnpj = cnpj.replace('/', '');

  if(cnpj.length > 13) {
    $.ajax({
      url: "https://api.cpfcnpj.com.br/6c03bf21c7f1c9448ee7802839bd7609/6/"+cnpj+"",
      type: 'POST',
      dataType: 'json',
      async: true,
      data: {id:cnpj},
      beforeSend: function() {
        $('#razao_social').val("Aguarde...");
        $('#nome_fantasia').val("Aguarde...");
        $('#cep-empresa').val("Aguarde...");
        $('#logradouro-empresa').val("Aguarde...");
        $('#numero-empresa').val("Aguarde...");
        $('#cidade-empresa').val("Aguarde...");
        $('#bairro-empresa').val("Aguarde...");
        $('#complemento-empresa').val("Aguarde...");
        $('#uf-empresa').val("Aguarde...");
        $('#nome_socio').val("Aguarde...");
        $('#email_socio').val("Aguarde...");
      },
      error: function() {
        swal("Puxa!", "Não encontramos nenhuma empresa com esse CNPJ", "error");
        $('#razao_social').val("");
        $('#nome_fantasia').val("");
        $('#cep-empresa').val("");
        $('#logradouro-empresa').val("");
        $('#numero-empresa').val("");
        $('#cidade-empresa').val("");
        $('#bairro-empresa').val("");
        $('#complemento-empresa').val("");
        $('#uf-empresa').val("");
        $('#nome_socio').val("");
        $('#email_socio').val("");

        $('#razao_social').attr('readonly', false);
        $('#nome_fantasia').attr('readonly', false);
        $('#cep-empresa').attr('readonly', false);
        $('#logradouro-empresa').attr('readonly', false);
        $('#numero-empresa').attr('readonly', false);
        $('#cidade-empresa').attr('readonly', false);
        $('#bairro-empresa').attr('readonly', false);
        $('#complemento-empresa').attr('readonly', false);
        $('#uf-empresa').attr('readonly', false);
      },
      success: function(json) {
        var razao_social = json.razao;
        $('#razao_social').val(razao_social);

        var nome_fantasia = json.fantasia;
        $('#nome_fantasia').val(nome_fantasia);

        var bairro = json.matrizEndereco.bairro;
        var cep = json.matrizEndereco.cep;
        var cidade = json.matrizEndereco.cidade;
        var complemento = json.matrizEndereco.complemento;
        var logradouro = json.matrizEndereco.logradouro;
        var numero = json.matrizEndereco.numero;
        var uf = json.matrizEndereco.uf;
        var nome_resp = json.responsavel;
        var email_resp = json.email;

        $('#cep-empresa').val(cep);
        $('#logradouro-empresa').val(logradouro);
        $('#numero-empresa').val(numero);
        $('#cidade-empresa').val(cidade);
        $('#bairro-empresa').val(bairro);
        $('#complemento-empresa').val(complemento);
        $('#uf-empresa').val(uf);
        $('#nome_socio').val(nome_resp);
        $('#email_socio').val(email_resp);
        $('#razao_social').attr('readonly', true);
      }
    });
  }
} // Puxa os dados da empresa cadastradas na receita federal

function check_contato_empresa (){
  $('#nome-contato-empresa').val($('#nome_socio').val());
  $('#email-contato-empresa').val($('#email_socio').val());
  $('#telefone-contato-empresa').val($('#telefone_socio').val());
  $('#cargo-contato-empresa').val($('#cargo_socio').val());
} // Repete os dados da sessão "Sócio / Representante" legal da empresa na seção "Contato na Empresa";

function check_end_cobranca (){
  $('#cep-cobranca').val($('#cep-empresa').val());
  $('#logradouro_cobranca').val($('#logradouro-empresa').val());
  $('#numero_cobranca').val($('#numero-empresa').val());
  $('#complemento_cobranca').val($('#complemento-empresa').val());
  $('#cidade_cobranca').val($('#cidade-empresa').val());
  $('#bairro_cobranca').val($('#bairro-empresa').val());
  $('#uf_empresa_cobranca').val($('#uf-empresa').val());
  $('#telefone-empresa-cobranca').val($('#telefone-celular').val());
} // Repete os dados da sessão "Endereço (CNPJ)" na seção "Endereço de Cobrança";

function escolher_operadora(operadora){
  var funcao = "escolher_vigencia";

  $.ajax({
    type: 'POST',
    url: 'functions.php',
    async: true,
    dataType: 'json',
    data: {'operadora': operadora, 'funcao': funcao},
    error: function() {
      swal("Erro!", "Verifique sua conexão!", "error");
    },
    success: function(result)
    {
      $("#escolher_vigencia").empty();
      $("#escolher_vigencia").append("<option value=''>Selecione a vigência</option>");

      for(i=0; i<=result.length; i++){
        var id = result[i].id;
        var vigencia = result[i].vigencia;

        $("#escolher_vigencia").append("<option value="+id+">"+vigencia+"</option>");
      }
    }
  });
} //Lista as vigencias de acordo com as respectivas operadoras//

function cadastrar_proposta(){
  var dados = $('#cadastrar_empresa').serialize();

  $.ajax({
      type: 'POST',
      dataType: 'json',
      url: 'functions.php',
      async: true,
      data: dados,
      beforeSend: function()
      {
        $('#cadastrar_pj').empty();
        $('#cadastrar_pj').html("Processando...");
        $('#cadastrar_pj').prop("disabled", true);
      },
      error: function() {
        alert("Error");
        $('#cadastrar_pj').empty();
        $('#cadastrar_pj').html("Cadastrar");
        $('#cadastrar_pj').prop("disabled", false);
      },
      success: function(result)
      {
        $('#cadastrar_pj').empty();
        $('#cadastrar_pj').html("Cadastrar");
        $('#cadastrar_pj').prop("disabled", false);

        if($.trim(result) == 'cnpj-invalid'){
          swal("Ops!", "CNPJ inválido!", "warning");

        } else if($.trim(result) == 'cnpj-existe'){
          swal("Ops!", "Já existe uma proposta com esse CNPJ!", "warning");

        } else if ($.trim(result) == 'razao-invalid'){
          swal("Ops!", "Razão Social da empresa inválida!", "warning");

        } else if ($.trim(result) == 'fantasia-invalid'){
          swal("Ops!", "Nome social da empresa inválido!", "warning");

        } else if ($.trim(result) == 'nome_socio-invalid'){
          swal("Ops!", "Nome do sócio / Representante legal da empresa inválido", "warning");

        } else if ($.trim(result) == 'cpf_socio-invalid'){
          swal("Ops!", "CPF sócio / Representante legal da empresa inválido!", "warning");

        } else if ($.trim(result) == 'telefone_socio-invalid'){
          swal("Ops!", "Telefone do sócio / Representante legal da empresa inválido!", "warning");

        } else if ($.trim(result) == 'email_socio-invalid'){
          swal("Ops!", "Email do sócio / Representante legal da empresa inválido!", "warning");

        } else if ($.trim(result) == 'cargo_socio-invalid'){
          swal("Ops!", "Cargo do sócio / Representante legal da empresa inválido!", "warning");



        } else if ($.trim(result) == 'nome_contato_empresa-invalid'){
          swal("Ops!", "Nome do contato na empresa inválido!", "warning");

        } else if ($.trim(result) == 'email_contato_empresa-invalid'){
          swal("Ops!", "Email do contato na empresa inválido!", "warning");

        } else if ($.trim(result) == 'cargo_contato_empresa-invalid'){
          swal("Ops!", "Cargo do contato na empresa inválido!", "warning");

        } else if ($.trim(result) == 'telefone_contato_empresa-invalid'){
          swal("Ops!", "Telefone do contato na empresa inválido!", "warning");


        } else if ($.trim(result) == 'cep_empresa-invalid'){
          swal("Ops!", "CEP do endereço da empresa inválido", "warning");

        } else if ($.trim(result) == 'logradouro_empresa-invalid'){
          swal("Ops!", "Logradouro da empresa inválido", "warning");

        } else if ($.trim(result) == 'numero_empresa-invalid'){
          swal("Ops!", "Número da empresa inválido", "warning");

        } else if ($.trim(result) == 'cidade_empresa-invalid'){
          swal("Ops!", "Cidade da empresa inválida", "warning");

        } else if ($.trim(result) == 'bairro_empresa-invalid'){
          swal("Ops!", "Bairro da empresa inválido", "warning");

        } else if ($.trim(result) == 'uf_empresa_empresa-invalid'){
          swal("Ops!", "UF da empresa inválido", "warning");

        } else if ($.trim(result) == 'telefone_empresa-invalid'){
          swal("Ops!", "Telefone da empresa inválido", "warning");

        } else if ($.trim(result) == 'telefone_celular-invalid'){
          swal("Ops!", "Telefone celular da empresa inválido", "warning");



        } else if ($.trim(result) == 'cep_cobranca-invalid'){
          swal("Ops!", "Cep do endereço de cobrança inválido", "warning");

        } else if ($.trim(result) == 'logradouro_cobranca-invalid'){
          swal("Ops!", "Logradouro do endereço de cobrança inválido", "warning");

        } else if ($.trim(result) == 'numero_cobranca-invalid'){
          swal("Ops!", "Número do endereço de cobrança inválido", "warning");

        } else if ($.trim(result) == 'cidade_cobranca-invalid'){
          swal("Ops!", "Cidade do endereço de cobrança inválido", "warning");

        } else if ($.trim(result) == 'bairro_cobranca-invalid'){
          swal("Ops!", "Bairro do endereço de cobrança inválido", "warning");

        } else if ($.trim(result) == 'uf_empresa_cobranca-invalid'){
          swal("Ops!", "UF do endereço de cobrança inválido", "warning");

        } else if ($.trim(result) == 'telefone_cobranca-invalid'){
          swal("Ops!", "Telefone do endereço de cobrança inválido", "warning");



        } else if ($.trim(result) == 'operadora-invalid'){
          swal("Ops!", "Por favor, escolha uma operadora", "warning");

        } else if ($.trim(result) == 'vigencia-invalid'){
          swal("Ops!", "Escolha a data de vigência", "warning");

        } else if ($.trim(result) == 'distribuidora-invalid'){
          swal("Ops!", "Informe o nome da distribuidora", "warning");

        } else if($.trim(result) == 'success') {
          location.reload();
          listar_contratos_pj ();
          swal("Perfeito!", "Sua proposta foi cadastrada com sucesso!", "success");
        }
      }
  });
} //Cadastra a proposta no banco de dados da contem //

function listar_contratos_pj (){
  $('#tabela_pj').empty();
  var status_select = $('#status_select option:selected').val();
  var operadora_select = $('#operadora_select option:selected').val();

  $.ajax({
    type:'post',
    dataType: 'json',
    url: 'functions.php',
    data: {'funcao': 'listar_contratos_pj', 'status': status_select, 'operadora': operadora_select},
    success: function(dados){
      for(var i=0; i<dados.length; i++){
        var id = dados[i].id;
        var nome_empresa = dados[i].nome_fantasia;
        var operadora = dados[i].operadora;
        var data = dados[i].data_contrato;
        var cnpj = dados[i].cnpj;
        var status = dados[i].status;
        var assinatura = dados[i].assinatura;
        var admin = '';

        if(tipo_user == 'ADMIN'){
          admin = 'block';
        } else {
          admin = 'none';
        }

        if(status == 'EM ABERTO'){
          $('#tabela_pj').append('\
          <tr>\
            <td style="padding-top: 20px;">'+id+'</center></td>\
            <td>'+nome_empresa+'</td>\
            <td>'+operadora+'</td>\
            <td>'+data+'</td>\
            <td>'+status+'</td>\
            <td class="button-td">\
              <div class="input-group-btn">\
                <div class="btn-group">\
                  <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn"></button>\
                  <div tabindex="-1" aria-hidden="true" role="menu" class="dropdown-menu">\
                    <a class="dropdown-item editarpj" href="#" data-toggle="modal" data-target="#editarpj_modal" id='+cnpj+' onclick="modal_editar_pj(\'' + cnpj + '\')">Editar Proposta</a>\
                    <a class="dropdown-item" href="beneficiarios.php?proposta='+id+'" target="_blank">Beneficiários</a>\
                    <a class="dropdown-item" href="#" class="anexo_pj" onclick="abrir_modal_anexo_pj('+id+')" data-toggle="modal" data-target="#anexar_doc_pj" id="">Documentos Empresa</a>\
                    <a class="dropdown-item" style="cursor: pointer; background-color: #f2562f; color: white;" onclick="gerar_proposta('+id+')">Gerar Proposta</a>\
                    <a class="dropdown-item" href="#" onclick="remover_proposta('+id+')">Remover Contrato</a>\
                  </div>\
                </div>\
              </div>\
            </td>\
          </tr>');

        } else if (status == 'PROPOSTA GERADA'){
          $('#tabela_pj').append('\
          <tr>\
            <td>'+id+'</td>\
            <td>'+nome_empresa+'</td>\
            <td>'+operadora+'</td>\
            <td>'+data+'</td>\
            <td>'+status+'</td>\
            <td>\
              <div class="dropdown">\
                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">\
                <span class="caret"></span>\
                </button>\
                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">\
                  <li><a class="dropdown-item editarpj" href="#" data-toggle="modal" data-target="#editarpj_modal" id='+cnpj+' onclick="modal_editar_pj(\'' + cnpj + '\')">Editar Proposta</a></li>\
                  <li><a class="dropdown-item" href="beneficiarios.php?proposta='+id+'" target="_blank">Beneficiários</a></li>\
                  <li><a class="dropdown-item" href="#" style="" class="anexo_pj" onclick="abrir_modal_anexo_pj('+id+')" data-toggle="modal" data-target="#anexar_doc_pj" id='+id+'>Documentos Empresa</a></li>\
                  <li><a class="dropdown-item" href="contrato.php/?proposta='+id+'" target="_blank" class="editar-dep">Imprimir Proposta</a></li>\
                  <li><a class="dropdown-item" href="#" class="anexo_pj" onclick="abrir_modal_finalizar_pj('+id+')" data-toggle="modal" data-target="#modal_finalizar" id='+id+'>Finalizar Proposta</a></li>\
                  <li class="dropdown-submenu" style="display: '+admin+'">\
                    <a tabindex="-1" href="#" class="dropdown-item"><i class="fas fa-caret-left"></i>&nbsp&nbsp&nbspArquivos</a>\
                    <ul class="dropdown-menu" id="ul'+i+'">\
                      <li><a tabindex="-1" href="pdf_empresa.php/?proposta='+id+'" target="_blank" class="dropdown-item">Dados da empresa</a></li>\
                      <div class="dropdown-divider"></div>\
                      '+preencher_benefic_dropdown(id, i)+'\
                    </ul>\
                  </li>\
                  <li><a class="dropdown-item" href="#" onclick="remover_proposta('+id+')">Remover Contrato</a></li>\
                </ul>\
              </div>\
            </td>\
          </tr>');
          
        } else if (status == 'FINALIZADO') {
          var digital = "digital";
          var fisico = "fisico";

          $('#tabela_pj').append('\
          <tr>\
            <td>'+id+'</center></td>\
            <td>'+nome_empresa+'</td>\
            <td>'+operadora+'</td>\
            <td>'+data+'</td>\
            <td>'+status+'</td>\
            <td>\
              <div class="input-group-btn">\
                <div class="btn-group">\
                  <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn"></button>\
                  <div tabindex="-1" aria-hidden="true" role="menu" class="dropdown-menu">\
                    <a class="dropdown-item" href="contrato.php/?proposta='+id+'" target="_blank" class="editar-dep">Imprimir Proposta</a>\
                  </div>\
                </div>\
              </div>\
            </td>\
          </tr>');
          }
        }
        
        $('#tabela_pj').append('\
        <tr>\
          <td class="" style="border-bottom: 1px solid white; height: 300px;"></td>\
          <td class="" style="border-bottom: 1px solid white;"></td>\
          <td class="" style="border-bottom: 1px solid white;"></td>\
          <td class="" style="border-bottom: 1px solid white;"></td>\
          <td class="" style="border-bottom: 1px solid white;"></td>\
          <td class="" style="border-bottom: 1px solid white;"></td>\
        </tr>');
      } 
  });
} //Lista os contratos pj cadastrados. Os contratos serão listados de acordo com o nivel de acesso do usuário: Admin ou Corretor() //

function preencher_benefic_dropdown (id, indice){
  //console.log(id+' '+indice);
  
  $.ajax({
    type:'post', //Definimos o método HTTP usado
    dataType: 'json',	//Definimos o tipo de retorno
    url: 'functions.php',
    data: {'funcao': 'dados_benefic_pdf', 'id':id},
    success: function(dados){
      for(var i=0; i<dados.length; i++){
        var id_benefic = dados[i].id;
        var nome = dados[i].nome;
        var indice_benefic = i+1;

        $('#ul'+indice).append('\
          <li><a tabindex="-1" href="https://painel.grupocontem.com.br/pdf_benefic.php/?proposta='+id+'&id='+id_benefic+'" target="_blank" class="dropdown-item">Beneficiário '+indice_benefic+'</a></li>\
        ');
      }
    }
  });
  return '';
} // lista os nomes dos beneficiários no campo de ações na listagem de contratos pj //

function limpa_formulário_cep() {
  //Limpa valores do formulário de cep.
  document.getElementById('logradouro_cobranca').value=("");
  document.getElementById('cidade_cobranca').value=("");
  document.getElementById('bairro_cobranca').value=("");
  document.getElementById('uf_empresa_cobranca').value=("");
}// Puxa dados de endereço pela API dos correios //

function meu_callback(conteudo) {
if (!("erro" in conteudo)) {
  //Atualiza os campos com os valores.
  document.getElementById('logradouro_cobranca').value=(conteudo.logradouro);
  document.getElementById('cidade_cobranca').value=(conteudo.localidade);
  document.getElementById('bairro_cobranca').value=(conteudo.bairro);
  document.getElementById('uf_empresa_cobranca').value=(conteudo.uf);
} //end if.
else {
  //CEP não Encontrado.
  limpa_formulário_cep();
  alert("CEP não encontrado.");
}
}// Puxa dados de endereço pela API dos correios //

function pesquisacep(valor) {

//Nova variável "cep" somente com dígitos.
var cep = valor.replace(/\D/g, '');

//Verifica se campo cep possui valor informado.
if (cep != "") {

  //Expressão regular para validar o CEP.
  var validacep = /^[0-9]{8}$/;

  //Valida o formato do CEP.
  if(validacep.test(cep)) {

      //Preenche os campos com "..." enquanto consulta webservice.
      document.getElementById('logradouro_cobranca').value="...";
      document.getElementById('cidade_cobranca').value="...";
      document.getElementById('bairro_cobranca').value="...";
      document.getElementById('uf_empresa_cobranca').value="...";

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
};// Puxa dados de endereço pela API dos correios //

function modal_editar_pj (cnpj){
  $('#cnpj-editar').val(cnpj);
  var funcao = 'listar_dados_pj';

  $.ajax({
    type: 'POST',
    url: 'functions.php',
    async: true,
    dataType: 'json',
    data: {'cnpj': cnpj, 'funcao': funcao},
    error: function() {
      alert("Error");
    },
    success: function(result)
    {
        var i=0;
        var razao_social = result[i].razao_social;
        var nome_fantasia = result[i].nome_fantasia;
        var insc_municipal = result[i].insc_municipal;
        var insc_estadual = result[i].insc_estadual;

        var nome_contato_empresa = result[i].nome_contato_empresa;
        var email_contato_empresa = result[i].email_contato_empresa;
        var cargo_contato_empresa = result[i].cargo_contato_empresa;
        var telefone_contato_empresa = result[i].telefone_contato_empresa;

        var nome_socio = result[i].nome_socio;
        var cpf_socio = result[i].cpf_socio;
        var telefone_socio = result[i].telefone_socio;
        var email_socio = result[i].email_socio;
        var cargo_socio = result[i].cargo_socio;

        var cep_empresa = result[i].cep_empresa;
        var logradouro_empresa = result[i].logradouro_empresa;
        var	numero_empresa = result[i].numero_empresa;
        var complemento_empresa = result[i].complemento_empresa;
        var cidade_empresa = result[i].cidade_empresa;
        var bairro_empresa = result[i].bairro_empresa;
        var uf_empresa = result[i].uf_empresa;
        var telefone_empresa = result[i].telefone_empresa;
        var telefone_celular = result[i].telefone_celular;

        var cep_cobranca = result[i].cep_cobranca;
        var	logradouro_cobranca = result[i].logradouro_cobranca;
        var numero_cobranca = result[i].numero_cobranca;
        var complemento_cobranca = result[i].complemento_cobranca;
        var cidade_cobranca = result[i].cidade_cobranca;
        var bairro_cobranca = result[i].bairro_cobranca;
        var estado_cobranca = result[i].estado_cobranca;
        var telefone_cobranca = result[i].telefone_cobranca;
        var codigo_corretor = result[i].codigo_corretor;

        $('#razao_social-editar').val(razao_social);
        $('#nome_fantasia-editar').val(nome_fantasia);
        $('#insc_municipal-editar').val(insc_municipal);
        $('#insc_estadual-editar').val(insc_estadual);

        $('#nome_socio-editar').val(nome_socio);
        $('#cpf_socio-editar').val(cpf_socio);
        $('#telefone_socio-editar').val(telefone_socio);
        $('#email_socio-editar').val(email_socio);
        $('#cargo_socio-editar').val(cargo_socio);

        $('#nome-contato-empresa-editar').val(nome_contato_empresa);
        $('#email-contato-empresa-editar').val(email_contato_empresa);
        $('#cargo-contato-empresa-editar').val(cargo_contato_empresa);
        $('#telefone-contato-empresa-editar').val(telefone_contato_empresa);

        $('#cep_empresa-editar').val(cep_empresa);
        $('#logradouro_empresa-editar').val(logradouro_empresa);
        $('#numero_empresa-editar').val(numero_empresa);
        $('#complemento_empresa-editar').val(complemento_empresa);
        $('#cidade_empresa-editar').val(cidade_empresa);
        $('#bairro_empresa-editar').val(bairro_empresa);
        $('#uf_empresa-editar').val(uf_empresa);
        $('#telefone_empresa-editar').val(telefone_empresa);
        $('#telefone_celular-editar').val(telefone_celular);

        $('#cep_cobranca-editar').val(cep_cobranca);
        $('#logradouro_cobranca-editar').val(logradouro_cobranca);
        $('#numero_cobranca-editar').val(numero_cobranca);
        $('#complemento_cobranca-editar').val(complemento_cobranca);
        $('#cidade_cobranca-editar').val(cidade_cobranca);
        $('#bairro_cobranca-editar').val(bairro_cobranca);
        $('#uf_cobranca-editar').val(estado_cobranca);
        $('#telefone_cobranca-editar').val(telefone_cobranca);
        $('#cod_corretor').val(codigo_corretor);
      }
    });
} // Lista os dados da empresa no modal para edição//

function alterar_proposta(){
  var dados2 = $('#pj_editar').serialize();

  $.ajax({
      type: 'POST',
      dataType: 'json',
      url: 'functions.php',
      async: true,
      data: dados2,
      error: function() {
        alert("Error");
      },
      success: function(result)
      {
        if($.trim(result) == 'deubom') {

          swal("Perfeito!", "O contrato foi alterado com sucesso!", "success")
          .then((value) => {
            location.reload();
          });

          //$('.modal-backdrop').css('display', 'none');
          //$('#editarpj_modal').css('display', 'none');

          $('#pj_editar').each (function(){
            this.reset();
          });
        } else if($.trim(result) == 'deuruim') {
          alert("Erro! Verifique os campos novamente!");
        } else{

        }
      }
  });
}// Edita os dados da empresa cadastrada! //

function abrir_modal_anexo_pj (id_proposta){
  jQuery('#id_anexo_modal').val(id_proposta);
  listar_anexos_pj(id_proposta);
}//Setar ID no input hidden do modal de anexos//

function listar_anexos_pj (id_return){
  var funcao = 'listar_anexos_pj';

  $.ajax({
      type: 'POST',
      url: 'functions.php',
      async: true,
      dataType: 'json',
      data: {'id': id_return, 'funcao': funcao},
      error: function() {

      },
      success: function(result)
      {
        $("#doc_anexados").empty();
        for(var i=0; i<result.length; i++){

          $("#doc_anexados").append("<li><a href='documentos_pj/doc_empresa/"+result[i].nome+"'\
          target='_blank'> "+result[i].nome_original+" </a></li>");
        }
      }
    });
}//Listar documentos relacionados a empresa no modal de anexos//

function enviar_anexo_pj(){
  var myForm2 = document.getElementById('anexar_documentos_form');
  formData = new FormData(myForm2);
  console.log(formData);

  $.ajax({
      type: 'POST',
      dataType: 'json',
      url: 'functions.php',
      async: true,
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function()
			{
        $("#button_enviar_anexo_pj").html('<img src="images/carregando.gif" class="carregando">');
			},
      error: function() {
        $("#button_enviar_anexo_pj").html('Anexar');
      },
      success: function(result)
      {
        if($.trim(result) == "vazio"){
          swal("Puxa!", "Você não selecionou nenhum arquivo!", "error");
          $("#button_enviar_anexo_pj").html('Anexar');
          $('#file_name').val("");
          $('#file').val("");
        } else if($.trim(result) > 0 && $.trim(result) < 200){
          //listar_anexos_pj($.trim(result));
          $("#button_enviar_anexo_pj").html('Anexar');
          $('#file_name').val("");
          $('#file').val("");
          swal("Puxa!", "Você selecionou um arquivo com formato inválido! ", "error");
        } else {
          listar_anexos_pj($.trim(result));
          $("#button_enviar_anexo_pj").html('Anexar');
          $('#file_name').val("");
          $('#file').val("");
        }
      }
  });
}//Enviar o arquivo para a pasta de documentos pj e guardar registro no banco de dados//

function gerar_proposta (id){
  var id_proposta = id;
  var funcao = "gerar_proposta";

  swal({
    title: "Atenção!",
    text: "Você tem certeza que deseja gerar a proposta?",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      $.ajax({
        type: 'POST',
        url: 'functions.php',
        async: true,
        dataType: 'json',
        data: {'id_proposta': id_proposta, 'funcao': funcao},
        error: function() {
          alert("Error");
        },
        success: function(result)
        {
          if($.trim(result) == 'qtd_benefic_invalid'){
            swal({
              title: "Puxa!",
              text: "Na proposta devem haver pelo menos dois beneficiários!",
              icon: "error",
              buttons: true,
            });
          } else if ($.trim(result) == 'success'){
            swal({
              title: "Perfeito!",
              text: "Sua proposta foi gerada com sucesso!",
              icon: "success",
              buttons: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                location.reload();
              } else {
                location.reload();
              }
            });
          }
        }
      });
    } else {

    }
  });
}// Esta função altera o status de 'EM ABERTO' para 'PROPOSTA GERADA' e libera a função de imprimir proposta//

function remover_proposta(id){
  var id_proposta = id;
  var funcao = "remover_proposta";

    swal({
      title: "Atenção!",
      text: "Você deseja realmente remover essa proposta? Após apagada, a proposta não poderá mais ser recuperada!",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {

        $.ajax({
          type: 'POST',
          url: 'functions.php',
          async: true,
          dataType: 'json',
          data: {'id_proposta': id_proposta, 'funcao': funcao},
          error: function() {
            alert("Error");
          },
          success: function(result)
          {
            if($.trim(result) == 'apagado'){
              swal("Perfeito!", "Sua proposta foi removida com sucesso!", {
                icon: "success",
              });
              location.reload();
            }
          }
        });
      }
    });
} // Essa função remove por completo a proposta incluindo seus beneficiários e dependentes. //

function finalizar_proposta (){
    var myForm2 = document.getElementById('anexar_documentos_form2');
    formData = new FormData(myForm2);

    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: 'functions.php',
      async: true,
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: function()
	  {
        $("#finalizar").html('<img src="images/carregando.gif" class="carregando">');
	  },
      error: function() {
        $("#finalizar").html('Anexar');
        swal("Error!", "Verifique sua conexão com a internet", "error");
      },
      success: function(result){
        $("#finalizar").html('Anexar');
        if($.trim(result) == 'menorque1'){
          swal("Puxa!", "Você não selecionou nenhum arquivo!", "error");
          //alert("Para finalizar, anexe a proposta e as condições gerais devidamente assinadas e preenchidas!");
        } else if($.trim(result) == 'formato-invalid'){
          swal("Erro!", "O arquivo deve ser do tipo .pdf", "error");
        } else if($.trim(result) == 'finalizado-success'){
          swal({
            title: "Perfeito!",
            text: "Sua proposta finalizada com sucesso!",
            icon: "success",
            buttons: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              location.reload();
            } else {
              location.reload();
            }
          });
        }
      }
  });
} // Função altera o status da proposta para finalizada. Para finalizar a proposta é necessário anexaxr a proposta assinada digitalmente ou escaneada!

function abrir_modal_finalizar_pj (id_proposta){
    jQuery('#id_proposta_finalizar').val(id_proposta);
    jQuery('#file_finalizar').val("");
} // Essa função preenche o campo 'cpf' no modal de finalizar proposta.

// Fim das funções de criação, edição e listagem de propostas //


//Funções de criação, edição e listagem de beneficiários//

function listar_beneficiarios () {
  var funcao = 'listar_benefic';

	$.ajax({
		type:'post',
		dataType: 'json',
    url: 'functions.php',
    async: true,
    data: {'funcao': funcao, 'proposta': proposta_cod},
		success: function(dados){
			for(var i=0; i<dados.length; i++){
				var cpf = dados[i].cpf;
        var nome = dados[i].nome;
        var nascimento = dados[i].nascimento;
        var tipo = dados[i].tipo;

        var cpf2 = cpf;
        var cpf2 = cpf2.replace('.', '');
        var cpf2 = cpf2.replace('.', '');
        var cpf2 = cpf2.replace('-', '');

        var teste = cpf2.toString();

  			$('#tabela').append('<tr>\
          <td class="">'+nome+'</td>\
          <td class="">'+cpf+'</td>\
          <td class="">'+tipo.toUpperCase()+'</td>\
          <td class="">\
          <div class="input-group-btn">\
            <div class="btn-group">\
              <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn"></button>\
              <div tabindex="-1" aria-hidden="true" role="menu" class="dropdown-menu">\
                <a class="dropdown-item" href="#" class="editar-benefic" data-toggle="modal" data-target="#modal_editar" id="'+cpf+'" onclick="abrir_modal_editar_benefic(\'' + cpf + '\')">Editar</a>\
                <a class="dropdown-item" href="#" data-toggle="modal" class='+cpf2+' data-target="#anexos" onclick="abrir_modal_anexo(\'' + cpf + '\')">Arquivos</a>\
                <a class="dropdown-item" href="#" data-toggle="modal" class='+cpf2+' data-target="#dependentes_add" onclick="abrir_modal_cad_dep(\'' + cpf + '\')"> Adicionar Dependentes</a>\
                <a class="dropdown-item" href="#" onclick="remover_beneficiario(\'' + cpf + '\')">Remover</a>\
              </div>\
            </div>\
          </div>\
        </td>\
      </tr>');
        //listar_dep(cpf, i);
      }
      if(i<2) {
      $('#tabela').append('<tr>\
        <td class="" style="border-bottom: 1px solid white;"></td>\
        <td class="" style="border-bottom: 1px solid white;"></td>\
        <td class="" style="border-bottom: 1px solid white;"></td>\
        <td class="" style="border-bottom: 1px solid white;"></td>\
        </tr>\
        <tr>\
          <td class="" style="border-bottom: 1px solid white;"></td>\
          <td class="" style="border-bottom: 1px solid white;"></td>\
          <td class="" style="border-bottom: 1px solid white;"></td>\
          <td class="" style="border-bottom: 1px solid white;"></td>\
        </tr>');
      }
		}
	});
} // Busca os beneficiários através do número da proposta//

function abrir_modal_editar_benefic (cpf){
  $(".dependente-append-editar").empty();
  var funcao = "listar_benefic_individual";

    $.ajax({
      type: 'POST',
      url: 'functions.php',
      async: true,
      dataType: 'json',
      data: {'cpf': cpf, 'funcao': funcao},
      error: function() {
        alert("Error");
      },
      success: function(result)
      {
        for(var i=0; i<1; i++){
          var cpf_titular = result[i].cpf;
          var nome = result[i].nome;
          var nome_mae = result[i].nome_mae;
          var nascimento = result[i].nascimento;
          var sexo = result[i].sexo;
          var estado_civil = result[i].estado_civil;
          var naturalidade = result[i].naturalidade;
          var rg = result[i].rg;
          var orgao = result[i].orgao;
          var cep = result[i].cep;
          var rua = result[i].rua;
          var numero = result[i].numero;
          var complemento = result[i].complemento;
          var cidade = result[i].cidade;
          var bairro = result[i].bairro;
          var uf = result[i].uf;
          var tel_res = result[i].tel_res;
          var tel_cel = result[i].tel_cel;
          var email = result[i].email;
          var sus = result[i].sus;
          var produto = result[i].produto;

          $('#cpf-benefic_editar').val(cpf_titular);
          $('#nome_editar').val(nome);
          $('#nome_mae_editar').val(nome_mae);
          $('#nascimento_editar').val(nascimento);
          $('#sexo_editar').val(sexo);
          $('#estado_civil_editar').val(estado_civil);
          $('#naturalidade_editar').val(naturalidade);
          $('#rg_editar').val(rg);
          $('#orgao_editar').val(orgao);
          $('#cep_editar').val(cep);
          $('#rua_editar').val(rua);
          $('#numero_editar').val(numero);
          $('#complemento_editar').val(complemento);
          $('#cidade_editar').val(cidade);
          $('#bairro_editar').val(bairro);
          $('#uf_editar').val(uf);

          $('#tel_res_editar').val(tel_res);
          $('#tel_cel_editar').val(tel_cel);
          $('#email_editar').val(email);
          $('#sus_editar').val(sus);
          $('#produto_editar').val(produto);
        }

        for(var x=1; x<=result.length; x++){
          var cpf_dep = result[x].cpf;
          var nome_dep = result[x].nome;
          var nome_mae_dep = result[x].nome_mae;
          var nascimento_dep = result[x].nascimento;
          var sexo_dep = result[x].sexo;
          var estado_civil_dep = result[x].estado_civil;
          var parentesco_dep = result[x].parentesco;

          $(".dependente-append-editar").append( "\
          <h4> Dependente "+x+" </h4>\
            <div class='form-group'>\
              <input type='text' class='form-control cpf-dep' id='"+x+"' placeholder='CPF' name='cpf_benefic_dep_editar"+x+"' aria-describedby='emailHelp' value="+cpf_dep+">\
            </div>\
        \
            <div class='form-group'>\
              <input type='text' class='form-control nome_dep"+x+"' id='"+x+"' placeholder='Nome completo' name='nome_dep_editar"+x+"' aria-describedby='emailHelp' value='"+nome_dep+"'>\
            </div>\
        \
            <div class='form-group'>\
              <input type='text' class='form-control nome_mae_dep"+x+"' id='"+x+"' placeholder='Nome completo da mãe' name='nome_mae_dep_editar"+x+"' aria-describedby='emailHelp' value='"+nome_mae_dep+"'>\
            </div>\
        \
            <div class='form-group'>\
              <input type='text' class='form-control nascimento_dep"+x+"' id='"+x+"' placeholder='Data de nascimento' name='nascimento_dep_editar"+x+"' aria-describedby='emailHelp' value='"+nascimento_dep+"'>\
            </div>\
        \
            <div class='row'>\
              <div class='col-xs-12 col-sm-4'>\
                <div class='form-group'>\
                  <select class='form-control sexo-dep"+x+"' id='"+x+"' name='sexo_dep_editar"+x+"' id='"+x+"'>\
                    <option value=''>Sexo</option>\
                    <option value='MASCULINO'>Masculino</option>\
                    <option value='FEMININO'>Feminino</option>\
                  </select>\
                </div>\
              </div>\
        \
              <div class='col-xs-12 col-sm-4'>\
                <div class='form-group'>\
                  <select class='form-control estado_civil-dep"+x+"' name='estado_civil_dep_editar"+x+"' id='"+x+"'>\
                    <option value=''>Estado Civil</option>\
                    <option value='SOLTEIRO'>Solteiro (a)</option>\
                    <option value='CASADO'>Casado (a)</option>\
                    <option value='SEPARADO'>Separado (a)</option>\
                    <option value='DIVORCIADO'>Divorciado (a)</option>\
                    <option value='VIUVO'>Viúvo (a)</option>\
                  </select>\
                </div>\
              </div>\
        \
              <div class='col-xs-12 col-sm-4'>\
                <div class='form-group'>\
                  <select class='form-control parentesco-dep"+x+"' name='parentesco_dep_editar"+x+"' id='"+x+"'>\
                  <option value=''>Parentesco</option>\
                  <option value='1'>Titular</option>\
                  <option value='2'>Agregado(a)</option>\
                  <option value='3'>Companheiro(a)</option>\
                  <option value='4'>Cônjugue</option>\
                  <option value='5'>Filho(a)</option>\
                  <option value='6'>Filho(a) Adotivo</option>\
                  <option value='7'>Irmão(a)</option>\
                  <option value='8'>Mãe</option>\
                  <option value='9'>Pai</option>\
                  <option value='10'>Neto(a)</option>\
                  <option value='11'>Sobrinho(a)</option>\
                  <option value='12'>Sogro</option>\
                  <option value='13'>Sogra</option>\
                  <option value='14'>Enteado(a)</option>\
                  <option value='15'>Genro</option>\
                  <option value='16'>Nora</option>\
                  <option value='17'>Cunhado</option>\
                  <option value='18'>Primo(a)</option>\
                  <option value='19'>Avô</option>\
                  <option value='20'>Avó</option>\
                  </select>\
                </div>\
                <input type='hidden' name='qtd_dep_editar' value='"+x+"'>\
              </div>");

              $(".estado_civil-dep"+x).val(estado_civil_dep);
              $(".parentesco-dep"+x).val(parentesco_dep);
              $(".sexo-dep"+x).val(sexo_dep);
              listar_anexos(cpf);
            }
          }
        });
} // Essa função preenche o campo cpf no modal de editar beneficiário, que será utilizado para buscar no banco de dados as informações do beneficiário//

var contador = 0;
function add_dep (){
  contador++;
  $('#qtd_dep').val(contador);

  $(".dependente-append").append( "\
  <h4> Dependente "+contador+" </h4>\
    <div class='form-group'>\
      <input type='text' class='form-control cpf-dep' id='"+contador+"' placeholder='CPF' name='cpf_benefic_dep"+contador+"' aria-describedby='emailHelp' value=''>\
    </div>\
\
    <div class='form-group'>\
      <input type='text' class='form-control nome_dep"+contador+"' id='"+contador+"' placeholder='Nome completo' name='nome_dep"+contador+"' aria-describedby='emailHelp' value=''>\
    </div>\
\
    <div class='form-group'>\
      <input type='text' class='form-control nome_mae_dep"+contador+"' id='"+contador+"' placeholder='Nome completo da mãe' name='nome_mae_dep"+contador+"' aria-describedby='emailHelp' value=''>\
    </div>\
\
    <div class='form-group'>\
      <input type='text' class='form-control nascimento_dep"+contador+"' id='"+contador+"' placeholder='Data de nascimento' name='nascimento_dep"+contador+"' aria-describedby='emailHelp' value=''>\
    </div>\
\
    <div class='form-group'>\
      <input type='text' class='form-control dnv_dep"+contador+"' id='"+contador+"' placeholder='Declaração de nascido vivo' name='dnv_dep"+contador+"' aria-describedby='emailHelp'>\
    </div>\
\
    <div class='form-group'>\
      <input type='text' class='form-control sus_dep"+contador+"' id='"+contador+"' placeholder='Cartão nacional do SUS' name='sus_dep"+contador+"' aria-describedby='emailHelp'>\
    </div>\
\
    <div class='row'>\
      <div class='col-xs-12 col-sm-4'>\
        <div class='form-group'>\
          <select class='form-control sexo_dep"+contador+"' name='sexo_dep"+contador+"' id='"+contador+" sexo_dep"+contador+"'>\
            <option selected value=''>Selecione</option>\
            <option value='MASCULINO'>Masculino</option>\
            <option value='FEMININO'>Feminino</option>\
          </select>\
        </div>\
      </div>\
\
      <div class='col-xs-12 col-sm-4'>\
        <div class='form-group'>\
          <select class='form-control estado_civil-dep"+contador+"' name='estado_civil_dep"+contador+"' id='"+contador+"'>\
            <option selected>Estado Civil</option>\
            <option value='SOLTEIRO'>Solteiro (a)</option>\
            <option value='CASADO'>Casado (a)</option>\
            <option value='SEPARADO'>Separado (a)</option>\
            <option value='DIVORCIADO'>Divorciado (a)</option>\
            <option value='VIUVO'>Viúvo (a)</option>\
          </select>\
        </div>\
      </div>\
\
      <div class='col-xs-12 col-sm-4'>\
        <div class='form-group'>\
          <select class='form-control parentesco-dep"+contador+"' name='parentesco_dep"+contador+"' id='"+contador+"'>\
              <option value=''>Parentesco</option>\
              <option value='1'>Titular</option>\
              <option value='2'>Agregado(a)</option>\
              <option value='3'>Companheiro(a)</option>\
              <option value='4'>Cônjugue</option>\
              <option value='5'>Filho(a)</option>\
              <option value='6'>Filho(a) Adotivo</option>\
              <option value='7'>Irmão(a)</option>\
              <option value='8'>Mãe</option>\
              <option value='9'>Pai</option>\
              <option value='10'>Neto(a)</option>\
              <option value='11'>Sobrinho(a)</option>\
              <option value='12'>Sogro</option>\
              <option value='13'>Sogra</option>\
              <option value='14'>Enteado(a)</option>\
              <option value='15'>Genro</option>\
              <option value='16'>Nora</option>\
              <option value='17'>Cunhado</option>\
              <option value='18'>Primo(a)</option>\
              <option value='19'>Avô</option>\
              <option value='20'>Avó</option>\
          </select>\
        </div>\
      </div>");

    jQuery('.cpf-dep').mask('999.999.999-99');

    jQuery(".cpf-dep").on('blur',function(){
      var dep_cod = $(this).attr("id");
      var dep_cod_val = $(this).val();
      var dep_cod_val = dep_cod_val.replace('.', '');
      var dep_cod_val = dep_cod_val.replace('.', '');
      var dep_cod_val = dep_cod_val.replace('-', '');

      $.ajax({
        url: "https://api.cpfcnpj.com.br/6c03bf21c7f1c9448ee7802839bd7609/2/"+dep_cod_val+"",
        type: 'POST',
        dataType: 'json',
        async: true,
        data: {id:dep_cod_val},
        error: function() {
          $('.nome_dep'+dep_cod).val("");
          $('.nascimento_dep'+dep_cod).val("");
          $('.nome_mae_dep'+dep_cod).val("");
          //$(".sexo_dep"+dep_cod).val("");
          alert("CPF não encontrado");
        },
        success: function(json)
        {
            var nome = json.nome;
            var nascimento = json.nascimento;
            var mae = json.mae;
            var genero = json.genero;

            $('.nome_dep'+dep_cod).val(nome.toUpperCase());
            $('.nascimento_dep'+dep_cod).val(nascimento);
            $('.nome_mae_dep'+dep_cod).val(mae.toUpperCase());

            if(genero == "M"){
              $(".sexo_dep"+dep_cod).val("MASCULINO");
            } else if(genero == "F"){
              $(".sexo_dep"+dep_cod).val("FEMININO");
            } else {

            }

            $('.nome_dep'+dep_cod).prop('readonly', true);
            $('.nascimento_dep'+dep_cod).prop('readonly', true);
            $('.nome_mae_dep'+dep_cod).prop('readonly', true);
            //$('#sexo_dep'+dep_cod).prop("readonly", true);
            console.log(dep_cod);
          }
        });
      return false;
    });
}// Função cuja cada clique adiciona mais um formulário para inserção de dependentes.

function api_cpf(cpf){
  var cpf = cpf.replace('.', '');
  var cpf = cpf.replace('.', '');
  var cpf = cpf.replace('-', '');

  if(cpf.length > 10){
    $.ajax({
      url: "https://api.cpfcnpj.com.br/6c03bf21c7f1c9448ee7802839bd7609/2/"+cpf+"",
      type: 'POST',
      dataType: 'json',
      async: true,
      data: {id:cpf},
      beforeSend: function(json)
      {
        $('#nome').val("Carregando...");
        $('#nascimento').val("Carregando...");
        $('#nome_mae').val("Carregando...");
        //$('#sexo').val("Carregando...");
      },
      error: function(jqXHR, textStatus) {
        if(textStatus === 'timeout'){
          $('#nome').removeAttr("readonly");
          $('#nascimento').removeAttr("readonly");
          $('#nome_mae').removeAttr("readonly");
          $('#sexo').removeAttr("readonly");

          $('#nome').val("");
          $('#nascimento').val("");
          $('#nome_mae').val("");
          $('#sexo').val("");
          $('#button_cadastrar').attr("disabled", false);
        } else {
          $('#nome').removeAttr("readonly");
          $('#nascimento').removeAttr("readonly");
          $('#nome_mae').removeAttr("readonly");
          $('#sexo').removeAttr("readonly");

          $('#nome').val("");
          $('#nascimento').val("");
          $('#nome_mae').val("");
          $('#sexo').val("");
          $('#button_cadastrar').attr("disabled", false);
        }
      },
      timeout:7000,
      success: function(json)
      {
          var nome = json.nome;
          var nascimento = json.nascimento;
          var mae = json.mae;
          var genero = json.genero;

          $('#nome').val(nome.toUpperCase());
          $('#nascimento').val(nascimento);
          $('#nome_mae').val(mae.toUpperCase());

          if(json.genero == "M"){
            $("#sexo").val("MASCULINO");
          } else if(json.genero == "F"){
            $("#sexo").val("FEMININO");
          } else {

          }
          $('#button_cadastrar').attr("disabled", false);
        }
      });
    }
}// Puxa os dados do beneficiario cadastrados na receita federal //

function cadastrar_benefic (){
  var myForm = document.getElementById('dados_benefic');
  formData = new FormData(myForm);
  //console.log(formData);

  $.ajax({
      type: 'POST',
      url: 'functions.php',
      async: true,
      data: formData,
      processData: false,
      contentType: false,
      error: function() {

      },
      success: function(result)
      {
        if($.trim(result) == 'success'){
          swal({
              title: "Perfeito!",
              text: "Beneficiário cadastrado com sucesso!",
              icon: "success",
              buttons: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                location.reload();
              }
            });
          //listar_beneficiarios ();
          contador = 0;
          //$('#myModal').modal('hide');
          $('#dados_benefic').each (function(){
            this.reset();
            $('.dependente-append').empty();
          });
          //atualizaPlanos();

        } else if($.trim(result) == 'cpf-invalid'){
          swal("Ops!", "CPF inválido", "warning");

        } else if($.trim(result) == 'cpf-existe'){
          swal("Ops!", "Esse CPF já está cadastrado em nosso sistema!", "warning");

        } else if($.trim(result) == 'nome-invalid'){
          swal("Ops!", "Nome inválido", "warning");

        } else if($.trim(result) == 'nomemae-invalid'){
          swal("Ops!", "Nome da mãe inválido", "warning");

        } else if($.trim(result) == 'nascimento-invalid'){
          swal("Ops!", "Data de nascimento inválido", "warning");

        } else if($.trim(result) == 'sexo-invalid'){
          swal("Ops!", "Sexo inválido", "warning");

        } else if($.trim(result) == 'estadocivil-invalid'){
          swal("Ops!", "Estado Civil inválido", "warning");

        } else if($.trim(result) == 'naturalidade-invalid'){
          swal("Ops!", "Naturalidade inválido", "warning");

        } else if($.trim(result) == 'rg-invalid'){
          swal("Ops!", "RG inválido", "warning");

        } else if($.trim(result) == 'orgao-invalid'){
          swal("Ops!", "Orgão inválido", "warning");

        } else if($.trim(result) == 'cep-invalid'){
          swal("Ops!", "CEP inválido", "warning");

        } else if($.trim(result) == 'rua-invalid'){
          swal("Ops!", "Rua inválido", "warning");

        } else if($.trim(result) == 'numero-invalid'){
          swal("Ops!", "Número inválido", "warning");

        } else if($.trim(result) == 'cidade-invalid'){
          swal("Ops!", "Cidade inválida", "warning");

        } else if($.trim(result) == 'bairro-invalid'){
          alert("");
          swal("Ops!", "Bairro inválido", "warning");

        } else if($.trim(result) == 'uf-invalid'){
          alert("");
          swal("Ops!", "UF inválido", "warning");

        } /*else if($.trim(result) == 'tel_res-invalid'){
          alert("Telefone residencial inválido");

        } */else if($.trim(result) == 'tel_cel-invalid'){
          swal("Ops!", "Telefone celular inválido", "warning");

        } else if($.trim(result) == 'email-invalid'){
          swal("Ops!", "Email inválido", "warning");

        } else if($.trim(result) == 'produto-invalid'){
          swal("Ops!", "Produto inválido", "warning");

        } else if($.trim(result) == 'dep-invalido'){
          swal("Ops!", "Erro! Por favor, verifique os dados dos dependentes!", "warning");
        }
     }
  });
} // Cadastro do beneficiário e seus dependentes(se o corretor optar por inseri-los) //

function alterar_benefic (){
  var myForm_editar = document.getElementById('editar-benefic');
  formData_editar = new FormData(myForm_editar);
  //console.log(formData_editar);

  $.ajax({
      type: 'POST',
      dataType: 'json',
      url: 'functions.php',
      async: true,
      data: formData_editar,
      processData: false,
      contentType: false,
      error: function() {
        swal("Ah não!", "Houve um erro. Verifique sua conexão com a internet", "error");
      },
      success: function(result)
      {
        if($.trim(result) == 'estadocivil-invalid') {
          swal("Ops!", "Estado civil inválido!", "warning");
        } else if($.trim(result) == 'naturalidade-invalid'){
          swal("Ops!", "Naturalidade inválido!", "warning");
        } else if($.trim(result) == 'rg-invalid'){
          swal("Ops!", "RG inválido!", "warning");
        } else if($.trim(result) == 'orgao-invalid'){
          swal("Ops!", "Orgão expedidor inválido!", "warning");
        } else if($.trim(result) == 'cep-invalid'){
          swal("Ops!", "CEP inválido!", "warning");
        } else if($.trim(result) == 'rua-invalid'){
          swal("Ops!", "Rua inválida!", "warning");
        } else if($.trim(result) == 'numero-invalid'){
          swal("Ops!", "Número inválido!", "warning");
        } else if($.trim(result) == 'email-invalid'){
          swal("Ops!", "Email inválido!", "warning");
        } else if($.trim(result) == 'tel_res-invalid'){
          swal("Ops!", "Telefone residencial inválido!", "warning");
        } else if($.trim(result) == 'tel_cel-invalid'){
          swal("Ops!", "Telefone celular inválido!", "warning");
        } else if($.trim(result) == 'produto-invalid'){
          swal("Ops!", "Selecione um produto!", "warning");
        } else if($.trim(result) == 'dep-invalid'){
          swal("Ops!", "Verifique os dados dos dependentes!", "warning");
        } else {
          swal({
            title: "Perfeito!",
            text: "Beneficiário foi atualizado com sucesso!",
            icon: "success",
            buttons: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              location.reload();
            }
          });
        }
      }
  });
} // Altera os dados do beneficiário e dos dependentes(Se existir) //

function remover_beneficiario (cpf){
  var funcao = 'remover_benefic';

  swal({
    title: "Atenção!",
    text: "Você deseja realmente remover essa proposta? Após apagada, a proposta não poderá mais ser recuperada!",
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
  .then((willDelete) => {
    if (willDelete) {
      $.ajax({
          type: 'POST',
          url: 'functions.php',
          async: true,
          dataType: 'json',
          data: {'cpf': cpf, 'funcao': funcao},
          error: function() {
            swal("Error!", "Verifique sua conexão com a internet!", "error");
          },
          success: function(result) {
            if($.trim(result) == 'apagado_benefic'){
              swal("Perfeito!", "O beneficiário foi removido com sucesso!", "success");
              $('#tabela').empty(); //Limpando a tabela
            	listar_beneficiarios();
            }
          }
      });
    }
  });
} // Remove todos os funcionarios e seus dependentes//

function abrir_modal_anexo (cpf){
  jQuery('#cpf_anexo_modal').val(cpf);
  listar_anexos(cpf);
} // Essa função define qual cpf os arquivos serão vinculados//

function anexar_documento (){
  var anexo_benefic = document.getElementById('anexar_documentos_form');
  form_benefic = new FormData(anexo_benefic);

  $.ajax({
      type: 'POST',
      dataType: 'json',
      url: 'functions.php',
      async: true,
      data: form_benefic,
      processData: false,
      contentType: false,
      beforeSend: function()
			{
        $("#anexar_doc_benefic").html('<img src="images/carregando.gif" class="carregando">');
			},
      error: function() {
        $("#anexar_doc_benefic").html('Anexar');
      },
      success: function(result)
      {
        if($.trim(result) == "vazio"){
          swal("Puxa!", "Você não selecionou nenhum arquivo!", "error");
          $("#anexar_doc_benefic").html('Anexar');
          $('#file_name').val("");
          $('#file').val("");
        } else if($.trim(result) > 0 && $.trim(result) < 200){
          //listar_anexos_pj($.trim(result));
          $("#anexar_doc_benefic").html('Anexar');
          $('#file_name').val("");
          $('#file').val("");
          swal("Puxa!", "Você selecionou um arquivo com formato inválido! ", "error");
        } else {
          listar_anexos($.trim(result));
          $("#anexar_doc_benefic").html('Anexar');
          $('#file_name').val("");
          $('#file').val("");
        }
      }
  });
} // Envia os documentos anexados na pasta (documentos_pj/doc_beneficiario) e registra no tabela wp_anexos

function listar_anexos (cpf_return){
  var funcao = 'listar_anexos';

  $.ajax({
      type: 'POST',
      url: 'functions.php',
      async: true,
      dataType: 'json',
      data: {'cpf': cpf_return, 'funcao': funcao},
      error: function() {
        
      },
      success: function(result)
      {
        $("#doc_anexados").empty();
        for(var i=0; i<result.length; i++){

          $("#doc_anexados").append("<li><a href='documentos_pj/doc_benefic/"+result[i].nome+"'\
          target='_blank'> "+result[i].nome_original+" </a></li>");
        }
      }
    });
} // Lista todos os arquivos anexados, filtrado pelo cpf informado pelo corretor//

function cadastrar_dependente (){
  var dados = $('#adicionar_dependente').serialize();

  $.ajax({
    type: 'POST',
    url: 'functions.php',
    async: true,
    data: dados,
    dataType: 'json',
    error: function() {

    },
    success: function(result) {
      if($.trim(result) == "cpf-invalid"){
        swal("Puxa!", "CPF inválido ", "warning");
      } else if ($.trim(result) == "cpf-existe"){
        swal("Puxa!", "Esse CPF já está cadastrado em outra proposta!", "error");
      } else if ($.trim(result) == "nome-invalid"){
        swal("Puxa!", "Nome inválido!", "warning");
      } else if ($.trim(result) == "nome-mae-invalid"){
        swal("Puxa!", "Nome da mãe inválido!", "warning");
      } else if ($.trim(result) == "nascimento-invalid"){
        swal("Puxa!", "Data de nascimento inválida", "warning");
      } else if ($.trim(result) == "sexo-invalid"){
        swal("Puxa!", "Sexo inválido!", "warning");
      } else if ($.trim(result) == "estado-civil-invalid"){
        swal("Puxa!", "Estado civil inválido", "warning");
      } else if ($.trim(result) == "parentesco-invalid"){
        swal("Puxa!", "Parentesco inválido", "warning");
      } else if ($.trim(result) == "success"){
        swal({
          title: "Perfeito!",
          text: "Dependente cadastrado com sucesso!",
          icon: "success",
          buttons: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            location.reload();
          }
        });
      }
    }
  });
} // Função exclusiva para inclusão de dependente. Aviso: O depedente pode ser cadastrado na funcão 'cadastrar_benefic()'. Essa função é apenas para quando o corretor esquecer de incluir o dependente no momento de cadastrar o beneficiário.

// Funções de criação e listagem de material de Vendas //

function anexar_material(){
  jQuery(document).ready(function(){
    var myForm = document.getElementById('anexar_material');
    formData = new FormData(myForm);

    $.ajax({
        type:'post',
        dataType: 'json',
        url: 'functions.php',
        async: true,
        data: formData,
        processData: false,
        contentType: false,
        error: function ()
        {

        },
        beforeSend: function()
        {
            //spinner.addClass('active');
        },
        success: function(result){
            if($.trim(result) == "arquivo_existe"){
                swal("Ops!", "Esse arquivo já está cadastrado!", "warning");
            } else if($.trim(result) == "naopdf"){
                swal("Ops!", "O arquivo deve ser em formato .pdf!", "warning");
            } else if($.trim(result) == "vazio"){
                swal("Ops!", "Preencha todos os campos!", "warning");
            } else if($.trim(result) == "success") {
                swal("Ótimo!", "O arquivo foi cadastrado com sucesso!", "success");
                myForm.reset();
                $('.modal-backdrop').css('display', 'none');
                $('#modal_material').modal('hide');
                listar_material();
            }
        },
        });
    });
}

function listar_material (){
  var funcao = 'listar_material';

  $.ajax({
    type: 'POST',
    url: 'functions.php',
    async: true,
    dataType: 'json',
    data: {'funcao': funcao},
    beforeSend: function()
    {

    },
    error: function() {
      alert("Error");
    },
    success: function(result)
    {
      $('#tabela_materiais').empty();

      for(var i=0; i<result.length; i++){
        var id = result[i].id;
        var nome = result[i].nome;
        var operadora = result[i].operadora;
        var tipo = result[i].tipo;
        var nome_arquivo = result[i].nome_arquivo;

        $('#tabela_materiais').append('\
        <tr>\
            <td>'+nome+'</td>\
            <td>'+operadora+'</td>\
            <td>'+tipo+'</td>\
            <td>\
                <a href="material_venda/'+nome_arquivo+'" target="_blank">\
                    <button type="button" class="btn orange-outline">\
                        <i class="fa fa-download"></i>&nbsp; Baixar\
                    </button>\
                </a>\
                <button type="button" class="btn btn-outline-danger" onclick="remover_material('+id+')">\
                    <i class="fa fa-times"></i>&nbsp; Excluir\
                </button>\
            </td>\
        </tr>');
      }
    }
  });
}

function listar_material_filtro (operadora){
  var funcao = 'listar_material_filtro';
  var operadora = $('#operadora_select option:selected').val();
  var tipo = $('#tipo option:selected').val();

  $.ajax({
    type: 'POST',
    url: 'functions.php',
    async: true,
    dataType: 'json',
    data: {'funcao': funcao, 'operadora': operadora, 'tipo': tipo},
    error: function() {
      $('#tabela_materiais').empty();

      $('#tabela_materiais').append('\
        <tr>\
          <td colspan="4" style="text-align: center">Não foi encontrado nenhum arquivo!</td>\
        </tr>');
    },
    beforeSend: function()
    {
      $('#tabela_materiais').empty();

      $('#tabela_materiais').append('\
        <tr>\
          <td colspan="4" style="text-align: center">Aguarde...</td>\
        </tr>');
    },
    success: function(result)
    {
      $('#tabela_materiais').empty();

      for(var i=0; i<result.length; i++){
        var id = result[i].id;
        var nome = result[i].nome;
        var operadora = result[i].operadora;
        var tipo = result[i].tipo;
        var nome_arquivo = result[i].nome_arquivo;
        var admin = '';

        if(tipo_user == 'ADMIN'){
          admin = 'block';
        } else {
          admin = 'none';
        }

        $('#tabela_materiais').append('\
        <tr>\
            <td>'+nome+'</td>\
            <td>'+operadora+'</td>\
            <td>'+tipo+'</td>\
            <td>\
                <a href="material_venda/'+nome_arquivo+'" target="_blank">\
                    <button type="button" class="btn orange-outline">\
                        <i class="fa fa-download"></i>&nbsp; Baixar\
                    </button>\
                </a>\
                <button type="button" class="btn btn-outline-danger" onclick="remover_material('+id+')">\
                    <i class="fa fa-times"></i>&nbsp; Excluir\
                </button>\
            </td>\
        </tr>');
      }
    }
  });
}

function remover_material(id){
    var funcao = "remover_material";

    swal({
      title: "Atenção!",
      text: "Você tem certeza que deseja remover esse arquivo?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                type: 'POST',
                url: 'functions.php',
                async: true,
                dataType: 'json',
                data: {'id': id, 'funcao': funcao},
                error: function() {
                    alert("Error");
                },
                success: function(result)
                {
                    if($.trim(result) == 'deletado'){
                        swal("Perfeito!", "Material de venda removido com sucesso!", "success")
                        .then((value) => {
                            location.reload();
                        });
                    }
                }
            });
        }
    });
}
// Fim das funções de criação e listagem de material de Vendas //

// Funções do corretor //

function alterar_foto (){
    jQuery(document).ready(function(){
        var myForm = document.getElementById('alterar_foto');
        formData = new FormData(myForm);
    
        $.ajax({
            type:'post',
            dataType: 'json',
            url: 'functions.php',
            async: true,
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function()
            {
                $("#alterar-button").html('<img src="images/carregando.gif" class="carregando">');
            },
            error: function (){
                swal("Error!", "Verifique sua conexão!", "error");
                $("#alterar-button").html('Alterar Foto');
            },
            success: function(result)
            {
                $("#alterar-button").html('Alterar Foto');
                if($.trim(result) == "img-errada"){
                    swal("Opa!", "Você selecionou um arquivo com formato inválido!", "warning");
                } else if($.trim(result) == "vazio"){
                    swal("Opa!", "Você não selecionou nenhum arquivo!", "warning");
                } else {
                    location.reload();
                }
            },
        });
    });
}

function alterar_senha (){
  var dados = $('#form_alterar_senha').serialize();

  $.ajax({
    type: 'POST',
    dataType: 'json',
    url: 'functions.php',
    async: true,
    data: dados,
    beforeSend: function(){
      $("#btn_altera_senha").html('<img src="images/carregando.gif" class="carregando">');
    },
    error: function() {
      swal("Error!", "Verifique sua conexão!", "error");
    },
    success: function(result) {
      $("#btn_altera_senha").html('Alterar Senha');

      if($.trim(result) == "campos_vazios"){
        swal("Ops!", "Você não pode deixar nenhum campo vazio!", "warning");
      } else if($.trim(result) == "caracter_invalido"){
        swal("Ops!", "Sua senha deve ter entre 8 e 16 caracteres!", "warning");
      } else if($.trim(result) == "senhas_diferentes"){
        swal("Ops!", "As senhas não condizem!", "warning");
      } else if($.trim(result) == "senha_incorreta"){
        swal("Ops!", "Senha atual incorreta!", "warning");
      } else {
        swal("Perfeito!", "Senha alterada com sucesso!", "success")
          .then((value) => {
            location.reload();
          });
      }
    }
  });
}

// Fim das funções do corretor //

// Funções do administrador //

function publicar_comunicado(){
  var dados = $('#comunicado_form').serialize();
  
  $.ajax({
    type: 'POST',
    dataType: 'json',
    url: 'functions.php',
    async: true,
    data: dados,
    beforeSend: function(){
      $("#button_publicar").html('Publicando...');
    },
    error: function() {
      swal("Error!", "Verifique sua conexão!", "error");
    },
    success: function(result) {
      $("#button_publicar").html('Publicar');

      if($.trim(result) == "titulo-vazio"){
        swal("Ops!", "Você não pode deixar o titulo vazio!", "warning");
      } else if($.trim(result) == "conteudo-vazio"){
        swal("Ops!", "Você não pode deixar o conteúdo do comunicado vazio!", "warning");
      } else {
        swal("Perfeito!", "Comunicado publicado com sucesso!", "success")
          .then((value) => {
            location.reload();
          });
      }
    }
  });
}

function listar_comunicados (){
  var funcao = 'listar_comunicados';

  $.ajax({
    type: 'POST',
    url: 'functions.php',
    async: true,
    dataType: 'json',
    data: {'funcao': funcao},
    beforeSend: function()
    {

    },
    error: function() {
      swal("Error!", "Verifique sua conexão!", "error");
    },
    success: function(result)
    {
      for(var i=0; i<result.length; i++){
        var id = result[i].id;
        var conteudo = result[i].conteudo;
        var data_hora = result[i].data_hora;
        var titulo = result[i].titulo;

        var data = data_hora.substring(0,10);
        var hora = data_hora.substring(11,20);

        var dia = data.substring(8,10);
        var mes = data.substring(5,7);
        var ano = data.substring(0,4);

        var admin = '';

        if(tipo_user == 'ADMIN'){
          admin = 'inline-block';
        } else {
          admin = 'none';
        }

        switch (mes) {
          case '01':
              nomeMes = "JAN";
              break;
          case '02':
              nomeMes = "FEV";
              break;
          case '03':
              nomeMes = "MAR";
              break;
          case '04':
              nomeMes = "ABR";
              break;
          case '05':
              nomeMes = "MAI";
              break;
          case '06':
              nomeMes = "JUN";
              break;
          case '07':
              nomeMes = "JUL";
              break;
          case '08':
              nomeMes = "AGO";
              break;
          case '09':
              nomeMes = "SET";
              break;
          case '10':
              nomeMes = "OUT";
              break;
          case '11':
              nomeMes = "NOV";
              break;
          case '12':
              nomeMes = "DEZ";
              break;
          default:
              nomeMes = "Mês inexistente";
        }

        $('.comunicados').append('\
          <div class="texto-card" id="'+id+'">\
            '+conteudo+' <a href="#" data-toggle="modal" data-target="#editar_comunicado" onclick="preencher_edicao_comunicado('+id+', \'' + titulo + '\', \'' + conteudo + '\')" style="display: '+admin+'"><small>Alterar</small></a>\
          </div>\
          \
          <div class="data-card">\
              <b>'+dia+'</b><br>'+nomeMes+'\
          </div>');
      }
    }
  });
}

function preencher_edicao_comunicado(id, titulo, conteudo){
    $('#id_editar').val(id);
    $('#titulo_editar').val(titulo);
    $('#conteudo_editar').val(conteudo);
}

function editar_comunicado (){
    var dados = $('#comunicado_form_editar').serialize();

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: 'functions.php',
        async: true,
        data: dados,
        beforeSend: function(){
            $("#button_editar").html('Editando...');
        },
        error: function() {
            swal("Error!", "Verifique sua conexão!", "error");
        },
        success: function(result) {
            $("#button_editar").html('Editar');
    
            if($.trim(result) == "titulo_vazio"){
                swal("Ops!", "Você não pode deixar o titulo vazio!", "warning");
            } else if($.trim(result) == "conteudo_vazio"){
                swal("Ops!", "Você não pode deixar o conteúdo do comunicado vazio!", "warning");
            } else {
                swal("Perfeito!", "Comunicado editado com sucesso!", "success")
                .then((value) => {
                    location.reload();
                });
            }
        }
    });
}
