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
      beforeSend: function()
			{
			  //$("#enviarmsg"+indice).css("display", "none");
        //$("#spin"+indice).css("display", "block");
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
