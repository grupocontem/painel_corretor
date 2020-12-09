<?php

include("conexao.php");

$id = $_SESSION['idUser'];
$funcao = $_POST['funcao'];

$consulta = $pdo->query("SELECT * FROM users where id = $id;");

while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $nome = $linha['nome'];
    $tipo_usuario = $linha['tipo_user'];
    $codigo_corretor = $linha['codigo_corretor'];
}


$conexao = mysqli_connect("grupocontem.com.br", "grupocon_conexao", "c0Nt3m#2@1p", "grupocon_vendapj") or die("Sem conexao");
if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());


//Funções relacionadas a criação de proposta//

if($funcao == "escolher_vigencia"){
  $operadora = $_POST['operadora'];

  $qryLista = mysqli_query($conexao, "SELECT * FROM wp_vigencias where operadora = '$operadora' order by vigencia");
    while($resultado = mysqli_fetch_assoc($qryLista)){
      $vetor[] = array_map('utf8_encode', $resultado);
    }
    echo json_encode($vetor);
} // Listar vigencias de acordo com cada operadora. // //Lista as vigencias de acordo com as respectivas operadoras//

if($funcao == "cadastrar_pj") {

  mysqli_set_charset($conexao, "utf8");

  $cnpj = $_POST['cnpj'];
  $razao_social = $_POST['razao_social'];
  $nome_fantasia = $_POST['nome_fantasia'];
  $insc_estadual = $_POST['insc_estadual'];
  $insc_municipal = $_POST['insc_municipal'];

  /*echo''.$cnpj;
  echo'<br>'.$razao_social;
  echo'<br>'.$nome_fantasia;
  echo'<br>'.$insc_estadual;
  echo'<br>'.$insc_municipal;*/


  //Endereco da empresa
  $cep_empresa = $_POST['cep_empresa'];
  $logradouro_empresa = $_POST['logradouro_empresa'];
  $numero_empresa = $_POST['numero_empresa'];
  $complemento_empresa = $_POST['complemento_empresa'];
  $cidade_empresa = $_POST['cidade_empresa'];
  $bairro_empresa = $_POST['bairro_empresa'];
  $uf_empresa = $_POST['uf_empresa'];
  $telefone_empresa = $_POST['telefone_empresa'];
  $telefone_celular = $_POST['telefone_celular'];

  /*echo'<br><br>'.$cep_empresa;
  echo'<br>'.$logradouro_empresa;
  echo'<br>'.$numero_empresa;
  echo'<br>'.$complemento_empresa;
  echo'<br>'.$cidade_empresa;
  echo'<br>'.$bairro_empresa;
  echo'<br>'.$uf_empresa;
  echo'<br>'.$telefone_empresa;
  echo'<br>'.$telefone_celular;*/

  //Socio ou representante legal
  $nome_socio = $_POST['nome_socio'];
  $cpf_socio = $_POST['cpf_socio'];
  $telefone_socio = $_POST['telefone_socio'];
  $email_socio = $_POST['email_socio'];
  $cargo_socio = $_POST['cargo_socio'];

  /*echo'<br><br>'.$nome_socio;
  echo'<br>'.$cpf_socio;
  echo'<br>'.$telefone_socio;
  echo'<br>'.$email_socio;
  echo'<br>'.$cargo_socio;*/

  //contato na empresa
  $nome_contato_empresa = $_POST['nome_contato_empresa'];
  $email_contato_empresa = $_POST['email_contato_empresa'];
  $cargo_contato_empresa = $_POST['cargo_contato_empresa'];
  $telefone_contato_empresa = $_POST['telefone_contato_empresa'];

  /*echo'<br><br>'.$nome_contato_empresa;
  echo'<br>'.$email_contato_empresa;
  echo'<br>'.$cargo_contato_empresa;
  echo'<br>'.$telefone_contato_empresa;*/

  //Endereco Cobranca
  $cep_cobranca = $_POST['cep-cobranca'];
  $logradouro_cobranca = $_POST['logradouro_cobranca'];
  $numero_cobranca = $_POST['numero_cobranca'];
  $complemento_cobranca = $_POST['complemento_cobranca'];
  $cidade_cobranca = $_POST['cidade_cobranca'];
  $bairro_cobranca = $_POST['bairro_cobranca'];
  $uf_empresa_cobranca = $_POST['uf_empresa_cobranca'];
  $telefone_cobranca = $_POST['telefone_cobranca'];

  //Produto
  $operadora = $_POST['operadora'];
  //$escolher_produto = $_POST['escolher_produto'];
  $escolher_vigencia = $_POST['escolher_vigencia'];
  //$codigo_corretor = $_POST['codigo_corretor'];
  $distribuidora = $_POST['distribuidora'];

  /*echo'<br><br>'.$operadora;
  echo'<br>'.$email_contato_empresa;
  echo'<br>'.$distribuidora;*/

  $teste_cnpj = "SELECT * from wp_contratospj where cnpj = '$cnpj'";
  $exec = mysqli_query($conexao, $teste_cnpj);
  $quantidade = mysqli_num_rows($exec);

  if(strlen($cnpj) < 18){
    echo json_encode("cnpj-invalid");
  } else if($quantidade > 0){
    echo json_encode ("cnpj-existe");
  } else if ($razao_social == ""){
    echo json_encode("razao-invalid");
  } else if ($nome_fantasia == ""){
    echo json_encode("fantasia-invalid");

  } else if ($nome_socio == ""){
    echo json_encode("nome_socio-invalid");
  } else if (strlen($cpf_socio) < 14){
    echo json_encode("cpf_socio-invalid");
  } else if (strlen($telefone_socio) < 14){
    echo json_encode("telefone_socio-invalid");
  } else if ($email_socio == ""){
    echo json_encode("email_socio-invalid");
  } else if ($cargo_socio == ""){
    echo json_encode("cargo_socio-invalid");

  } else if ($nome_contato_empresa == ""){
    echo json_encode("nome_contato_empresa-invalid");
  } else if ($email_contato_empresa == ""){
    echo json_encode("email_contato_empresa-invalid");
  } else if ($cargo_contato_empresa == ""){
    echo json_encode("cargo_contato_empresa-invalid");
  } else if (strlen($telefone_contato_empresa) < 14){
    echo json_encode("telefone_contato_empresa-invalid");

  } else if ($cep_empresa == ""){
    echo json_encode("cep_empresa-invalid");
  } else if ($logradouro_empresa == ""){
    echo json_encode("logradouro_empresa-invalid");
  } else if ($numero_empresa == ""){
    echo json_encode("numero_empresa-invalid");
  } else if ($cidade_empresa == ""){
    echo json_encode("cidade_empresa-invalid");
  } else if ($bairro_empresa == ""){
    echo json_encode("bairro_empresa-invalid");
  } else if ($uf_empresa == ""){
    echo json_encode("uf_empresa_empresa-invalid");
  } else if ($telefone_empresa == ""){
    echo json_encode("telefone_empresa-invalid");
  } else if ($telefone_celular == ""){
    echo json_encode("telefone_celular-invalid");

  } else if ($cep_cobranca == ""){
    echo json_encode("cep_cobranca-invalid");
  } else if ($logradouro_cobranca == ""){
    echo json_encode("logradouro_cobranca-invalid");
  } else if ($numero_cobranca == ""){
    echo json_encode("numero_cobranca-invalid");
  } else if ($cidade_cobranca == ""){
    echo json_encode("cidade_cobranca-invalid");
  } else if ($bairro_cobranca == ""){
    echo json_encode("bairro_cobranca-invalid");
  } else if ($uf_empresa_cobranca == ""){
    echo json_encode("uf_empresa_cobranca-invalid");
  } else if ($telefone_cobranca == ""){
    echo json_encode("telefone_cobranca-invalid");

  } else if ($telefone_celular == ""){
    echo json_encode("telefone_celular-invalid");
  } else if ($operadora == ""){
    echo json_encode("operadora-invalid");
  } else if ($escolher_vigencia == ""){
    echo json_encode("vigencia-invalid");
  } else if ($distribuidora == ""){
    echo json_encode("distribuidora-invalid");
  } else {
    $query = "INSERT INTO wp_contratospj 
    (cnpj, razao_social, nome_fantasia, insc_estadual, insc_municipal, 
    cep_empresa, logradouro_empresa, numero_empresa, complemento_empresa, 
    cidade_empresa, bairro_empresa, uf_empresa, telefone_empresa, telefone_celular,
    nome_contato_empresa, email_contato_empresa, cargo_contato_empresa, telefone_contato_empresa, 
    nome_socio, cpf_socio, telefone_socio, email_socio, cargo_socio, cep_cobranca, 
    logradouro_cobranca, numero_cobranca, complemento_cobranca, bairro_cobranca, 
    cidade_cobranca, estado_cobranca, telefone_cobranca, operadora, vigencia, 
    codigo_corretor, status, distribuidora)
    VALUES
    ('$cnpj', '$razao_social', '$nome_fantasia', '$insc_estadual', '$insc_municipal',
     '$cep_empresa', '$logradouro_empresa', '$numero_empresa', '$complemento_empresa', 
     '$cidade_empresa', '$bairro_empresa', '$uf_empresa', '$telefone_empresa', '$telefone_celular',
     '$nome_contato_empresa', '$email_contato_empresa', '$cargo_contato_empresa', '$telefone_contato_empresa',
     '$nome_socio', '$cpf_socio', '$telefone_socio', '$email_socio', '$cargo_socio', '$cep_cobranca', 
     '$logradouro_cobranca', '$numero_cobranca', '$complemento_cobranca', '$bairro_cobranca', 
     '$cidade_cobranca', '$uf_empresa_cobranca', '$telefone_cobranca', '$operadora', '$escolher_vigencia', 
     '$codigo_corretor', 'EM ABERTO', '$distribuidora')";

    $exec = mysqli_query($conexao, $query);

    if($exec){
      $id_proposta = mysqli_insert_id($conexao);
      echo json_encode("success");
    } else {
      echo json_encode("error");
    }
  }
} //Salva a proposta no banco de dados//

if($funcao == "listar_dados_pj"){
  $cnpj = $_POST['cnpj'];

  $qryLista = mysqli_query($conexao, "SELECT * FROM wp_contratospj where cnpj = '$cnpj'");
    while($resultado = mysqli_fetch_assoc($qryLista)){
      $vetor[] = array_map('utf8_encode', $resultado);
    }
    echo json_encode($vetor);
} //

if($funcao == "editar_pj"){

  mysqli_set_charset($conexao, "utf8");

  $cnpj = $_POST['cnpj'];
  $razao_social = $_POST['razao_social'];
  $nome_fantasia = $_POST['nome_fantasia'];
  $insc_estadual = $_POST['insc_estadual'];
  $insc_municipal = $_POST['insc_municipal'];

  //Socio ou representante legal
  $nome_socio = $_POST['nome_socio'];
  $cpf_socio = $_POST['cpf_socio'];
  $telefone_socio = $_POST['telefone_socio'];
  $email_socio = $_POST['email_socio'];
  $cargo_socio = $_POST['cargo_socio'];

  //Endereco da empresa
  $cep_empresa = $_POST['cep_empresa'];
  $logradouro_empresa = $_POST['logradouro_empresa'];
  $numero_empresa = $_POST['numero_empresa'];
  $complemento_empresa = $_POST['complemento_empresa'];
  $cidade_empresa = $_POST['cidade_empresa'];
  $bairro_empresa = $_POST['bairro_empresa'];
  $uf_empresa = $_POST['uf_empresa'];
  $telefone_empresa = $_POST['telefone_empresa'];
  $telefone_celular = $_POST['telefone_celular'];

  //contato na empresa
  $nome_contato_empresa = $_POST['nome_contato_empresa'];
  $email_contato_empresa = $_POST['email_contato_empresa'];
  $cargo_contato_empresa = $_POST['cargo_contato_empresa'];
  $telefone_contato_empresa = $_POST['telefone_contato_empresa'];

  //Endereco Cobranca
  $cep_cobranca = $_POST['cep-cobranca'];
  $logradouro_cobranca = $_POST['logradouro_cobranca'];
  $numero_cobranca = $_POST['numero_cobranca'];
  $complemento_cobranca = $_POST['complemento_cobranca'];
  $cidade_cobranca = $_POST['cidade_cobranca'];
  $bairro_cobranca = $_POST['bairro_cobranca'];
  $uf_empresa_cobranca = $_POST['uf_empresa_cobranca'];
  $telefone_cobranca = $_POST['telefone_cobranca'];

  //Produto
  //$operadora = $_POST['operadora'];
  //$escolher_produto = $_POST['escolher_produto'];
  //$escolher_vigencia = $_POST['escolher_vigencia'];
  //$codigo_corretor = $_POST['id_corretor'];

  $query = "UPDATE wp_contratospj SET insc_estadual = '$insc_estadual', insc_municipal = '$insc_municipal', nome_contato_empresa = '$nome_contato_empresa', email_contato_empresa = '$email_contato_empresa',
  cargo_contato_empresa = '$cargo_contato_empresa', telefone_contato_empresa = '$telefone_contato_empresa', telefone_empresa = '$telefone_empresa',
  telefone_celular = '$telefone_celular', cep_cobranca = '$cep_cobranca', logradouro_cobranca = '$logradouro_cobranca', numero_cobranca = '$numero_cobranca',
  complemento_cobranca = '$complemento_empresa', bairro_cobranca = '$bairro_cobranca', cidade_cobranca = '$cidade_cobranca', estado_cobranca = '$uf_empresa_cobranca',
  telefone_cobranca = '$telefone_cobranca', nome_socio = '$nome_socio', cpf_socio = '$cpf_socio', telefone_socio = '$telefone_socio', email_socio = '$email_socio',
  cargo_socio = '$cargo_socio' WHERE cnpj = '$cnpj'";

  $exec = mysqli_query($conexao, $query);

  if($exec){
    echo json_encode("deubom");
  } else {
    echo json_encode("deuruim");
  }
}

if($funcao == "anexar_documento_pj"){
  $id = $_POST['id_anexo'];
  $erro = 0;

  $target_dir = 'documentos_pj/doc_empresa/';

  if(isset($_FILES['file']['name'])) {

    $total_files = count($_FILES['file']['name']);
    //echo $_FILES['file']['name'];

    for($key = 0; $key < $total_files; $key++) {

      $ext_teste[] = substr($_FILES['file']['name'][$key], -4);

      if($_FILES['file']['name'][$key] == ""){
        echo json_encode ("vazio");
        break;
      } else if($ext_teste[$key] != '.pdf' && $ext_teste[$key] != '.png' && $ext_teste[$key] != '.jpg' && $ext_teste[$key] != '.jpeg' && $ext_teste[$key] != '.jfif') {
        //echo json_encode ("vazio");
        $erro++;
      } else {
        /*if(isset($_FILES['file']['name'][$key])
                                  && $_FILES['file']['size'][$key] > 0) {
          $original_filename = $_FILES['file']['name'][$key];
          $ext = substr($original_filename, -4);
          $novo_nome = md5(time()).$key.$ext;
          $target = $target_dir . basename($novo_nome);
          $tmp  = $_FILES['file']['tmp_name'][$key];
          move_uploaded_file($tmp, $target);
          $nome_original = $_FILES['file']['name'][$key];

          $query_anexo = "INSERT INTO wp_anexos_empresa (nome, nome_original, id_contrato) VALUES ('$novo_nome', '$nome_original', '$id')";
          $exec_anexo = mysqli_query($conexao, $query_anexo);
        }*/

      }
    }
    if($erro > 0){
      echo json_encode($erro);
    } else {
      for($key = 0; $key < $total_files; $key++) {
        if(isset($_FILES['file']['name'][$key])
                                  && $_FILES['file']['size'][$key] > 0) {
          $original_filename = $_FILES['file']['name'][$key];
          $ext = substr($original_filename, -4);
          $novo_nome = md5(time()).$key.$ext;
          $target = $target_dir . basename($novo_nome);
          $tmp  = $_FILES['file']['tmp_name'][$key];
          move_uploaded_file($tmp, $target);
          $nome_original = $_FILES['file']['name'][$key];

          $query_anexo = "INSERT INTO wp_anexos_empresa (nome, nome_original, id_contrato) VALUES ('$novo_nome', '$nome_original', '$id')";
          $exec_anexo = mysqli_query($conexao, $query_anexo);
        }
      }
      for($k = 0; $k < $total_files; $k++) {
        if($_FILES['file']['name'][$k] == ""){
          break;
        } else {
          echo json_encode($id);
          break;
        }
      }
    }
  }
}

if($funcao == "listar_anexos_pj"){
  $id = $_POST['id'];

  $qryLista3 = mysqli_query($conexao, "SELECT * FROM wp_anexos_empresa where id_contrato = '$id'");
    while($resultado3 = mysqli_fetch_assoc($qryLista3)){
      $vetor[] = array_map('utf8_encode', $resultado3);
    }
  echo json_encode($vetor);
}

if($funcao == "gerar_proposta"){
  $id_proposta = $_POST['id_proposta'];

  $count_benefic_query = "SELECT * from wp_beneficiario where proposta = $id_proposta";
  $count_benefic_exec = mysqli_query($conexao, $count_benefic_query);
  $count_benefic = mysqli_num_rows($count_benefic_exec);

  $count_dep_query = "SELECT * from wp_dependente where proposta = $id_proposta";
  $count_dep_exec = mysqli_query($conexao, $count_dep_query);
  $count_dep = mysqli_num_rows($count_dep_exec);

  $total_geral = $count_benefic+$count_dep;

  if($total_geral < 2){
    echo json_encode("qtd_benefic_invalid");
  } else {
    $query = "UPDATE wp_contratospj SET status = 'PROPOSTA GERADA' WHERE id = '$id_proposta'";
    $exec = mysqli_query($conexao, $query);
    echo json_encode("success");
  }
}

if($funcao == "remover_proposta"){
  $id_proposta = $_POST['id_proposta'];

  $delete_proposta_query = "DELETE FROM wp_contratospj where id = $id_proposta";
  $delete_proposta_exec = mysqli_query($conexao, $delete_proposta_query);

  $delete_proposta_benefic_query = "DELETE FROM wp_beneficiario where proposta = $id_proposta";
  $delete_proposta_benefic_exec = mysqli_query($conexao, $delete_proposta_benefic_query);

  $delete_proposta_dep_query = "DELETE FROM wp_dependente where proposta = $id_proposta";
  $delete_proposta_dep_exec = mysqli_query($conexao, $delete_proposta_dep_query);
  echo json_encode("apagado");
}
// Fim das funções de criação de proposta//



// Funções de criação e listagem de material de Vendas //

if($funcao == "anexar_material") {

  $target_dir = 'material_venda/';
  $nome_arquivo = $_POST['nome'];
  $operadora = $_POST['operadora'];
  $tipo = $_POST['tipo'];

  if( isset($_FILES['file']['name'])) {

    $total_files = count($_FILES['file']['name']);

    for($key = 0; $key < $total_files; $key++) {

      if(isset($_FILES['file']['name'][$key])
                                && $_FILES['file']['size'][$key] > 0
                                && $nome_arquivo != ''
                                && $operadora != ''
                                && $tipo != '') {

        $original_filename = $_FILES['file']['name'][$key];
        $ext = substr($original_filename, -4);

        if($ext == '.pdf'){
          $novo_nome =  $_FILES['file']['name'][$key];
          $target = $target_dir . basename($novo_nome);
          $tmp  = $_FILES['file']['tmp_name'][$key];

          $data = [
            'nome' => $nome_arquivo,
            'operadora' => $operadora,
            'tipo' => $tipo,
            'nome_arquivo' => $novo_nome,
          ];

          $data2 = [
            'nome_arquivo' => $novo_nome,
          ];

          $del = $pdo->prepare('SELECT * FROM material_venda where nome_arquivo = :nome_arquivo');
          $del->execute($data2);
          $count = $del->rowCount();

          $sql = "INSERT INTO material_venda (nome, operadora, tipo, nome_arquivo, data) VALUES (:nome, :operadora, :tipo, :nome_arquivo, NOW())";
          $stmt = $pdo->prepare($sql);

          if($count > 0){
            echo json_encode("arquivo_existe");
          } else {
            if(move_uploaded_file($tmp, $target)){
              $stmt->execute($data);
              echo json_encode("success");
            }
          }
        } else {
          echo json_encode("naopdf");
        }
      } else {
        echo json_encode("vazio");
      }
    }
  }
}

if($funcao == "listar_material"){
  $consulta = $pdo->query("SELECT * FROM material_venda;");

  while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $vetor[] = array_map('utf8_encode', $linha);
  }

  echo json_encode($vetor);
}

if($funcao == "listar_material_filtro"){
  $operadora = $_POST['operadora'];
  $tipo = $_POST['tipo'];


  if($operadora != '' && $tipo == ''){
    $consulta = $pdo->query("SELECT * FROM material_venda where operadora = '$operadora'");
  } else if($operadora == '' && $tipo != ''){
    $consulta = $pdo->query("SELECT * FROM material_venda where tipo = '$tipo';");
  } else if($operadora == '' && $tipo == ''){
    $consulta = $pdo->query("SELECT * FROM material_venda;");
  } else if($operadora != '' && $tipo != ''){
    $consulta = $pdo->query("SELECT * FROM material_venda where operadora = '$operadora' and tipo = '$tipo';");
  }

  while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $vetor[] = array_map('utf8_encode', $linha);
  }

  echo json_encode($vetor);
}

// Fim das funções de criação e listagem de material de Vendas //

?>
