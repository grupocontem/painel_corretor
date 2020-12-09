// Funções de criação, edição e listagem de propostas//

function api_cnpj (){
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
  //console.log(dados);

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
          $('.modal-backdrop').css('display', 'none');
          $('#cadastrar_proposta').css('display', 'none');
          $('#cadastrar_empresa').each (function(){
            this.reset();
          });
          listar_contratos_pj ();
          swal("Perfeito!", "Sua proposta foi cadastrada com sucesso!", "success");
        }
      }
  });
} //Cadastra a proposta no banco de dados da contem //

function listar_contratos_pj (){
  jQuery(document).ready(function(){
  	$('#tabela_pj').empty(); //Limpando a tabela
    //var dados = $('#dados_contratos').serialize();

  	$.ajax({
  		type:'post',
  		dataType: 'json',
  		url: 'functions_pj.php',
      data: {'funcao': 'listar_contratos_pj'},
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

          if(tipo_do_usuario == 'ADMIN'){
            admin = 'block';
          } else {
            admin = 'none';
          }

          if(status == 'EM ABERTO'){
    				$('#tabela_pj').append('<tr>\
            \
            <td><center>'+id+'</center></center></td>\
            <td><center>'+nome_empresa+'</center></td>\
            <td><center>'+operadora+'</center></td>\
            <td><center>'+data+'</center></td>\
            <td><center>'+status+'</center></td>\
            <td style="display: '+admin+'"><center>'+assinatura+'</center></td>\
            <td>\
            <div class="input-group-btn">\
              <div class="btn-group">\
                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-primary"></button>\
                <div tabindex="-1" aria-hidden="true" role="menu" class="dropdown-menu">\
                <a class="dropdown-item editarpj" href="#" data-toggle="modal" data-target="#editarpj_modal" id='+cnpj+' onclick="modal_editar_pj(\'' + cnpj + '\')">Editar Proposta</a>\
                <a class="dropdown-item" href="beneficiarios-pj/?proposta=1125" target="_blank">Beneficiários</a>\
                <a class="dropdown-item" href="#" class="anexo_pj" onclick="abrir_modal_anexo_pj('+id+')" data-toggle="modal" data-target="#anexar_doc_pj" id="">Documentos Empresa</a>\
                <a class="dropdown-item" style="cursor: pointer; background-color: #f2562f;" onclick="gerar_proposta('+id+')">Gerar Proposta</a>\
                <a class="dropdown-item" href="#" onclick="remover_proposta('+id+')">Remover Contrato</a>\
                </div>\
              </div>\
            </div>\
            </td>\
          </tr>');

        } else if (status == 'PROPOSTA GERADA'){
          $('#tabela_pj').append('<tr>\
          \
          <td><center>'+id+'</center></center></td>\
          <td><center>'+nome_empresa+'</center></td>\
          <td><center>'+operadora+'</center></td>\
          <td><center>'+data+'</center></td>\
          <td><center>'+status+'</center></td>\
          <td style="display: '+admin+'"><center>'+assinatura+'</center></td>\
          <td>\
            <div class="dropdown">\
              <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown">\
                <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>\
              </button>\
              <ul class="dropdown-menu">\
                <li><a href="#" class="editarpj" data-toggle="modal" data-target="#editarpj_modal" id='+cnpj+'>Editar Contrato</a></li>\
                <li><a href="beneficiarios-pj/?proposta='+id+'" target="_blank">Beneficiários</a></li>\
                <li><a href="#" style="display: '+admin+'" class="anexo_pj" onclick="abrir_modal_anexo_pj('+id+')" data-toggle="modal" data-target="#anexar_doc_pj" id='+id+'>Documentos Empresa</a></li>\
                <li style="background-color: #f2562f;"><a href="contratopme/?proposta='+id+'" target="_blank" class="editar-dep">Imprimir Proposta</a></li>\
                <li><a href="#" class="anexo_pj" onclick="abrir_modal_finalizar_pj('+id+')" data-toggle="modal" data-target="#modal_finalizar" id='+id+'>Finalizar Proposta</a></li>\
                <li><a href="#" onclick="abrir_modal_pdf('+id+', \'' + nome_empresa + '\')" data-toggle="modal" data-target="#modal_pdfs" style="display: '+admin+';">PDF</a></li>\
                <li><a href="#" onclick="remover_proposta('+id+')">Remover Contrato</a></li>\
              </ul>\
            </div>\
          </td>\
        </tr>');

      } else if (status == 'FINALIZADO') {
        var digital = "digital";
        var fisico = "fisico";

          $('#tabela_pj').append('<tr>\
          \
          <td><center>'+id+'</center></center></td>\
          <td><center>'+nome_empresa+'</center></td>\
          <td><center>'+operadora+'</center></td>\
          <td><center>'+data+'</center></td>\
          <td><center>'+status+'</center></td>\
          <td style="display: '+admin+'"><center>'+assinatura+'</center></td>\
          <td>\
            <div class="dropdown">\
              <button class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown">\
                <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>\
              </button>\
              <ul class="dropdown-menu">\
                <li><a href="beneficiarios-pj/?proposta='+id+'" style="display: '+admin+'" target="_blank">Beneficiários</a></li>\
                <li><a href="#" style="display: '+admin+'" class="anexo_pj" onclick="abrir_modal_anexo_pj('+id+')" data-toggle="modal" data-target="#anexar_doc_pj" id='+id+'>Documentos Empresa</a></li>\
                <li><a href="contratopme/?proposta='+id+'" target="_blank" class="editar-dep">Imprimir Proposta</a></li>\
                <li><a href="#" style="display: '+admin+'" onclick="assinatura('+id+', \'ASSINADO PELO DOCUSIGN\');">Assinado Digitalmente</a></li>\
                <li><a href="#" style="display: '+admin+'" onclick="assinatura('+id+', \'ASSINADO FISICAMENTE\');">Assinado Fisicamente</a></li>\
              </ul>\
            </div>\
          </td>\
        </tr>');
        }
      }
  	  }
  	});
});
} //Lista os contratos pj cadastrados. Os contratos serão listados de acordo com o nivel de acesso do usuário: Admin ou Corretor() //

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
        alert("Error");
      },
      success: function(result)
      {
        $("#doc_anexados").empty();
        for(var i=0; i<result.length; i++){

          $("#doc_anexados").append("<li><a href='https://grupocontem.com.br/wp-content/themes/macro/assets/anexos_titular_pj/anexos_empresa/"+result[i].nome+"'\
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
        $('#enviar_anexo_pj').empty();
        $('#enviar_anexo_pj').html("Processando...");
        $('#enviar_anexo_pj').prop("disabled", true);
			},
      error: function() {
        alert("Error");
        $('#enviar_anexo_pj').empty();
        $('#enviar_anexo_pj').html("Anexar");
        $('#enviar_anexo_pj').prop("disabled", false);
      },
      success: function(result)
      {
        if($.trim(result) == "vazio"){
          swal("Puxa!", "Você não selecionou nenhum arquivo!", "error");
          $('#enviar_anexo_pj').empty();
          $('#enviar_anexo_pj').html("Anexar");
          $('#enviar_anexo_pj').prop("disabled", false);
          $('#file_name').val("");
          $('#file').val("");
        } else if($.trim(result) > 0 && $.trim(result) < 200){
          //listar_anexos_pj($.trim(result));
          $('#enviar_anexo_pj').empty();
          $('#enviar_anexo_pj').html("Anexar");
          $('#enviar_anexo_pj').prop("disabled", false);
          $('#file_name').val("");
          $('#file').val("");
          swal("Puxa!", "Você selecionou um arquivo com formato inválido! ", "error");
        } else {
          listar_anexos_pj($.trim(result));
          $('#enviar_anexo_pj').empty();
          $('#enviar_anexo_pj').html("Anexar");
          $('#enviar_anexo_pj').prop("disabled", false);
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
            swal("Puxa!", "Na proposta devem haver pelo menos dois beneficiários!", {
              icon: "error",
            });
          } else if ($.trim(result) == 'success'){
            listar_contratos_pj ();
            swal("Perfeito!", "Sua proposta foi gerada com sucesso!", {
              icon: "success",
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
}
//Funções de criação, edição e listagem de propostas //






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
      error: function (){

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
              <button type="button" class="btn btn-outline-primary">\
                <i class="fa fa-download"></i>&nbsp; Baixar\
              </button></td>\
            </a>\
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
              <button type="button" class="btn btn-outline-primary">\
                <i class="fa fa-download"></i>&nbsp; Baixar\
              </button></td>\
            </a>\
          </tr>');
      }
    }
  });
}

// Fim das funções de criação e listagem de material de Vendas //
