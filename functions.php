<?php

require 'phpmailer/PHPMailerAutoload.php';

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


// Funcoes da dashboard //

if($funcao == "contratos_cadastrados"){
    $query = mysqli_query($conexao, "SELECT * FROM wp_contratospj where codigo_corretor = '$codigo_corretor'");
    $row = mysqli_num_rows($query);
    echo json_encode($row);
}

if($funcao == "contratos_cadastrados_mensal"){
    $mes = date('m');
    $ano = date('Y');
    $query = mysqli_query($conexao, "SELECT * FROM wp_contratospj where codigo_corretor = '$codigo_corretor' and month(data_contrato) = '$mes' and year(data_contrato) = '$ano'");
    $row = mysqli_num_rows($query);

    echo json_encode($row);
}

//Funções relacionadas a criação de proposta//

if($funcao == "listar_contratos_pj"){
    $operadora = $_POST['operadora'];
    $status = $_POST['status'];

    $data_minima = '2021-01-01 00:00:00';

    if($tipo_usuario == "ADMIN"){
        if($operadora != '' && $status == ''){
            $qryLista = mysqli_query($conexao, "SELECT * FROM wp_contratospj WHERE data_contrato > '$data_minima' and operadora = '$operadora' order by id DESC");
        } else if($operadora == '' && $status != ''){
            $qryLista = mysqli_query($conexao, "SELECT * FROM wp_contratospj WHERE data_contrato > '$data_minima' and status = '$status' order by id DESC");
        } else if($operadora != '' && $status != ''){
            $qryLista = mysqli_query($conexao, "SELECT * FROM wp_contratospj WHERE data_contrato > '$data_minima' and operadora = '$operadora' and status = '$status' order by id DESC");
        } else if($operadora == '' && $status == ''){
            $qryLista = mysqli_query($conexao, "SELECT * FROM wp_contratospj WHERE data_contrato > '$data_minima' order by id DESC");
        }
    } else {
        if($operadora != '' && $status == ''){
            $qryLista = mysqli_query($conexao, "SELECT * FROM wp_contratospj WHERE codigo_corretor = '$codigo_corretor' and data_contrato > '$data_minima' and operadora = '$operadora' order by id DESC");
        } else if($operadora == '' && $status != ''){
            $qryLista = mysqli_query($conexao, "SELECT * FROM wp_contratospj WHERE codigo_corretor = '$codigo_corretor' and data_contrato > '$data_minima' and status = '$status' order by id DESC");
        } else if($operadora != '' && $status != ''){
            $qryLista = mysqli_query($conexao, "SELECT * FROM wp_contratospj WHERE codigo_corretor = '$codigo_corretor' and data_contrato > '$data_minima' and operadora = '$operadora' and status = '$status' order by id DESC");
        } else if($operadora == '' && $status == ''){
            $qryLista = mysqli_query($conexao, "SELECT * FROM wp_contratospj WHERE codigo_corretor = '$codigo_corretor' and data_contrato > '$data_minima' order by id DESC");
        }
    }

    while($resultado = mysqli_fetch_assoc($qryLista)){
        $vetor[] = array_map('utf8_encode', $resultado);
    }

    echo json_encode($vetor);
}

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
          $hash_random = randString(60);

          $novo_nome = $hash_random.$key.$ext;
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

if($funcao == "listar_produtos"){
  $proposta = $_POST['proposta'];
  $operadora = $_POST['operadora'];
  $funcao = $_POST['funcao'];

  $cemeru = mysqli_query($conexao, "SELECT * FROM wp_beneficiario where proposta = '$proposta'");
  $cemeru_exec = mysqli_fetch_array($cemeru);
  $produto = $cemeru_exec['produto'];
  $i = mysqli_num_rows($cemeru);

  if($i==0 && $operadora == "CEMERU"){
    $qryLista = mysqli_query($conexao, "SELECT * FROM wp_produtos where operadora = '$operadora'");
  } else if ($produto == 1 && $operadora == "CEMERU"){
    $qryLista = mysqli_query($conexao, "SELECT * FROM wp_produtos where id = 1");
  } else if ($produto == 2 && $operadora == "CEMERU"){
    $qryLista = mysqli_query($conexao, "SELECT * FROM wp_produtos where id = 2");
  } else {
    $qryLista = mysqli_query($conexao, "SELECT * FROM wp_produtos where operadora = '$operadora'");
  }

  while($resultado = mysqli_fetch_assoc($qryLista)){
    $vetor[] = array_map('utf8_encode', $resultado);
  }

  echo json_encode($vetor);
}

if($funcao == "finalizar_proposta"){
    $id = $_POST['id_proposta'];
    $anexo = $_FILES['file'];
  
    $sql = mysqli_query($conexao, "SELECT * FROM wp_contratospj where id = '$id'");
    $dados = mysqli_fetch_array($sql);
    $email_enviar = $dados['email_socio'];
  
    $mail = new PHPMailer();
    $mail->IsSMTP();
  
    $mail->From = "naoresponda@grupocontem.com.br"; //remitente
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls'; //seguridad
    $mail->Host = "smtp.office365.com"; // servidor smtp
    $mail->Port = 587; //puerto
    $mail->Username ='naoresponda@grupocontem.com.br'; //nombre usuario
    $mail->setFrom('naoresponda@grupocontem.com.br', 'Nao Responda');
    $mail->Password = 'Bav22911'; //contraseña
  
    $msg = utf8_decode("<h3>Proposta: ".$id."</h3><br>CNPJ: ".$dados['cnpj']."<br>"."RAZÃO SOCIAL: ".$dados['razao_social']."<br>"."OPERADORA: ".$dados['operadora']);
  
    $mail->Subject = "Proposta PME";
    $mail->Body = $msg;
    $mail->IsHTML(true);
    $destinatario = "comercial@grupocontem.com.br";
    $mail->AddAddress($destinatario);
  
    for ($i=0; $i<1; $i++){
      $ext_teste = substr($_FILES['file']['name'][$i], -4);
  
      if($_FILES['file']['name'][$i] == ""){
        echo json_encode("menorque1");
      } else if ($ext_teste != '.pdf'){
        echo json_encode("formato-invalid");
      } else {
        $mail->AddAttachment($anexo['tmp_name'][$i], $anexo['name'][$i]);
        $mail->Send();
        $query = "UPDATE wp_contratospj SET status = 'FINALIZADO' WHERE id = '$id'";
        $exec = mysqli_query($conexao, $query);
        echo json_encode("finalizado-success");
      }
    }
}

if($funcao == "dados_benefic_pdf"){
    $id = $_POST['id'];

    $qryLista = mysqli_query($conexao, "SELECT id, nome FROM wp_beneficiario where proposta = '$id'");

    while($resultado = mysqli_fetch_assoc($qryLista)){
        $vetor[] = array_map('utf8_encode', $resultado);
    }

    echo json_encode($vetor);
}
// Fim das funções de criação de proposta//


//Funções relacionadas a inserção de beneficiários da proposta//

if($funcao == "cadastrar_benefic"){
  $cpf = $_POST['cpf_benefic'];
  $nome = $_POST['nome'];
  $nome_mae = $_POST['nome_mae'];
  $nascimento = $_POST['nascimento'];
  $sexo = $_POST['sexo'];
  $estado_civil = $_POST['estado_civil'];
  $naturalidade = $_POST['naturalidade'];
  $rg = $_POST['rg'];

  $orgao = $_POST['orgao'];
  $cep = $_POST['cep'];
  $rua = $_POST['rua'];
  $numero = $_POST['numero'];

  $complemento = $_POST['complemento'];
  $cidade = $_POST['cidade'];
  $bairro = $_POST['bairro'];
  $uf = $_POST['uf'];

  $tel_res = $_POST['tel_res'];
  $tel_cel = $_POST['tel_cel'];
  $email = $_POST['email'];
  $sus = $_POST['sus'];

  $tipo = "titular";
  $produto = $_POST['produto'];
  $proposta = $_POST['proposta'];
  $qtd_dep = $_POST['qtd_dep'];
  $erro = 0;

  /*echo''.$cpf;
  echo'<br>'.$nome;
  echo'<br>'.$nome_mae;
  echo'<br>'.$nascimento;
  echo'<br>'.$sexo;
  echo'<br>'.$estado_civil;
  echo'<br>'.$naturalidade;
  echo'<br>'.$rg;

  echo'<br>'.$orgao;
  echo'<br>'.$cep;
  echo'<br>'.$rua;
  echo'<br>'.$numero;

  echo'<br>'.$complemento;
  echo'<br>'.$cidade;
  echo'<br>'.$bairro;
  echo'<br>'.$uf;

  echo'<br>'.$tel_res;
  echo'<br>'.$tel_cel;
  echo'<br>'.$email;
  echo'<br>'.$sus;

  echo'<br>'.$tipo;
  echo'<br>'.$produto;
  echo'<br>'.$proposta;
  echo'<br>'.$qtd_dep;*/

  $teste_cpf = "SELECT * from wp_beneficiario where cpf = '$cpf'";
  $exec = mysqli_query($conexao, $teste_cpf);
  $quantidade = mysqli_num_rows($exec);

  if($cpf == ""){
    echo "cpf-invalid";
  } else if($quantidade > 0){
    echo "cpf-existe";
  } else if($nome == ""){
    echo "nome-invalid";
  } else if($nome_mae == ""){
    echo "nomemae-invalid";
  } else if($nascimento == ""){
    echo "nascimento-invalid";
  } else if($sexo == ""){
    echo "sexo-invalid";
  } else if($estado_civil == ""){
    echo "estadocivil-invalid";
  } else if($naturalidade == ""){
    echo "naturalidade-invalid";
  } else if($rg == ""){
    echo "rg-invalid";
  } else if($orgao == ""){
    echo "orgao-invalid";
  } else if($cep == ""){
    echo "cep-invalid";
  } else if($rua == ""){
    echo "rua-invalid";
  } else if($numero == ""){
    echo "numero-invalid";
  } else if($cidade == ""){
    echo "cidade-invalid";
  } else if($bairro == ""){
    echo "bairro-invalid";
  } else if($uf == ""){
    echo "uf-invalid";
  } else if($tel_cel == ""){
    echo "tel_cel-invalid";
  } else if($produto == ""){
    echo "produto-invalid";
  } else if($email == ""){
    echo "email-invalid";
  } else {
    $data_invertida = explode("/", $nascimento);
    $data_invertida_final = $data_invertida[2].'-'.$data_invertida[1].'-'.$data_invertida[0];

    $date = new DateTime($data_invertida_final);
    $interval = $date->diff( new DateTime(date('d-m-Y')));
    $idade = $interval->format('%Y');

    if($idade > 0 && $idade <= 18){
      $faixa = 1;
    } else if ($idade >= 19 && $idade <= 23){
      $faixa = 2;
    } else if ($idade >= 24 && $idade <= 28){
      $faixa = 3;
    } else if ($idade >= 29 && $idade <= 33){
      $faixa = 4;
    } else if ($idade >= 34 && $idade <= 38){
      $faixa = 5;
    } else if ($idade >= 39 && $idade <= 43){
      $faixa = 6;
    } else if ($idade >= 44 && $idade <= 48){
      $faixa = 7;
    } else if ($idade >= 49 && $idade <= 53){
      $faixa = 8;
    } else if ($idade >= 54 && $idade <= 58){
      $faixa = 9;
    } else if ($idade >= 59){
      $faixa = 10;
    }

    for($i=1; $i<=$qtd_dep; $i++) {
      $cpf_dep = $_POST['cpf_benefic_dep'.$i];
      $nome_dep = $_POST['nome_dep'.$i];
      $nome_mae_dep = $_POST['nome_mae_dep'.$i];
      $nascimento_dep = $_POST['nascimento_dep'.$i];
      $sexo_dep = $_POST['sexo_dep'.$i];
      $estado_civil_dep = $_POST['estado_civil_dep'.$i];
      $parentesco_dep = $_POST['parentesco_dep'.$i];

      if($cpf_dep == "" || $nome_dep == "" || $nome_mae_dep == "" || $nascimento_dep == "" || $sexo_dep == "" || $estado_civil_dep == "" || $parentesco_dep == ""){
        $erro += 1;
      }
    }
    if($erro > 0){
      echo "dep-invalido";
    } else {
      $query = "INSERT INTO wp_beneficiario (cpf, nome, nome_mae, nascimento, faixa_idade, sexo, estado_civil, naturalidade, rg, orgao, cep, rua, numero, complemento, cidade, bairro, uf, tel_res, tel_cel,
      email, sus, tipo, produto, proposta) VALUES ('$cpf', '$nome', '$nome_mae', '$nascimento', '$faixa', '$sexo', '$estado_civil', '$naturalidade', '$rg', '$orgao', '$cep', '$rua', '$numero', '$complemento',
      '$cidade', '$bairro', '$uf', '$tel_res', '$tel_cel', '$email', '$sus', '$tipo', '$produto', '$proposta')";
      $exec = mysqli_query($conexao, $query);

      if($exec) {
        if($qtd_dep < 1){
          echo'success';
        } else {
          for($i=1; $i<=$qtd_dep; $i++) {
            $cpf_dep = $_POST['cpf_benefic_dep'.$i];
            $nome_dep = $_POST['nome_dep'.$i];
            $nome_mae_dep = $_POST['nome_mae_dep'.$i];
            $nascimento_dep = $_POST['nascimento_dep'.$i];
            $sexo_dep = $_POST['sexo_dep'.$i];
            $estado_civil_dep = $_POST['estado_civil_dep'.$i];
            $parentesco_dep = $_POST['parentesco_dep'.$i];
            $dnv_dep = $_POST['dnv_dep'.$i];
            $sus_dep = $_POST['sus_dep'.$i];

            $data_invertida_dep = explode("/", $nascimento_dep);
            $data_invertida_final_dep = $data_invertida_dep[2].'-'.$data_invertida_dep[1].'-'.$data_invertida_dep[0];

            $date_dep = new DateTime($data_invertida_final_dep);
            $interval_dep = $date_dep->diff( new DateTime(date('d-m-Y')));
            $idade_dep = $interval_dep->format('%Y');

            if($idade_dep > 0 && $idade_dep <= 18){
              $faixa_dep = 1;
            } else if ($idade_dep >= 19 && $idade_dep <= 23){
              $faixa_dep = 2;
            } else if ($idade_dep >= 24 && $idade_dep <= 28){
              $faixa_dep = 3;
            } else if ($idade_dep >= 29 && $idade_dep <= 33){
              $faixa_dep = 4;
            } else if ($idade_dep >= 34 && $idade_dep <= 38){
              $faixa_dep = 5;
            } else if ($idade_dep >= 39 && $idade_dep <= 43){
              $faixa_dep = 6;
            } else if ($idade_dep >= 44 && $idade_dep <= 48){
              $faixa_dep = 7;
            } else if ($idade_dep >= 49 && $idade_dep <= 53){
              $faixa_dep = 8;
            } else if ($idade_dep >= 54 && $idade_dep <= 58){
              $faixa_dep = 9;
            } else if ($idade_dep >= 59){
              $faixa_dep = 10;
            }

            $query_dep = "INSERT INTO wp_dependente (cpf, nome, nome_mae, nascimento, faixa_idade, sexo, estado_civil, parentesco, cns, dnv, cpf_titular, proposta, produto)
            VALUES ('$cpf_dep', '$nome_dep', '$nome_mae_dep', '$nascimento_dep', '$faixa_dep', '$sexo_dep', '$estado_civil_dep', '$parentesco_dep', '$dnv_dep', '$sus_dep', '$cpf', '$proposta', '$produto')";
            $exec_dep = mysqli_query($conexao, $query_dep);
            echo'success';
          }
        }
      } else {
        echo "unsuccessfull";
      }
    }
  }
}

if($funcao == "listar_benefic"){
  $proposta = $_POST['proposta'];

  $qryLista = mysqli_query($conexao, "SELECT * FROM wp_beneficiario where proposta = '$proposta'");
    while($resultado = mysqli_fetch_assoc($qryLista)){
        $vetor[] = array_map('utf8_encode', $resultado);
    }
      echo json_encode($vetor);
}

if($funcao == "listar_benefic_individual"){
  $cpf = $_POST['cpf'];

  $qryLista = mysqli_query($conexao, "SELECT * FROM wp_beneficiario where cpf = '$cpf'");
      while($resultado = mysqli_fetch_assoc($qryLista)){
          $vetor[] = array_map('utf8_encode', $resultado);
      }

  $qryLista2 = mysqli_query($conexao, "SELECT * FROM wp_dependente where cpf_titular = '$cpf'");
      while($resultado2 = mysqli_fetch_assoc($qryLista2)){
          $vetor[] = array_map('utf8_encode', $resultado2);
      }

  echo json_encode($vetor);
}

if($funcao == "editar_benefic"){

  mysqli_set_charset($conexao, "utf8");

  $cpf = $_POST['cpf_benefic_editar'];
  $estado_civil = $_POST['estado_civil_editar'];
  $naturalidade = $_POST['naturalidade_editar'];
  $rg = $_POST['rg_editar'];
  $orgao = $_POST['orgao_editar'];
  $cep = $_POST['cep_editar'];
  $rua = $_POST['rua_editar'];
  $numero = $_POST['numero_editar'];
  $complemento = $_POST['complemento_editar'];
  $cidade = $_POST['cidade_editar'];
  $bairro = $_POST['bairro_editar'];
  $uf = $_POST['uf_editar'];
  $tel_res = $_POST['tel_res_editar'];
  $tel_cel = $_POST['tel_cel_editar'];
  $email = $_POST['email_editar'];
  $sus = $_POST['sus_editar'];
  $proposta = $_POST['proposta_editar'];
  $produto = $_POST['produto_editar'];
  $qtd_dep = $_POST['qtd_dep_editar'];

   if($estado_civil == ""){
    echo json_encode("estadocivil-invalid");
  } else if($naturalidade == ""){
    echo json_encode("naturalidade-invalid");
  } else if($rg == ""){
    echo json_encode("rg-invalid");
  } else if($orgao == ""){
    echo json_encode("orgao-invalid");
  } else if($cep == ""){
    echo json_encode("cep-invalid");
  } else if($rua == ""){
    echo json_encode("rua-invalid");
  } else if($numero == ""){
    echo json_encode("numero-invalid");
  } else if($cidade == ""){
    echo json_encode("cidade-invalid");
  } else if($bairro == ""){
    echo json_encode("bairro-invalid");
  } else if($uf == ""){
    echo json_encode("uf-invalid");
  } else if($tel_res == ""){
    echo json_encode("tel_res-invalid");
  } else if($tel_cel == ""){
    echo json_encode("tel_cel-invalid");
  } else if($produto == ""){
    echo json_encode("produto-invalid");
  } else if($email == ""){
    echo json_encode("email-invalid");
  } else {
    for($i=1; $i<=$qtd_dep; $i++) {
      $cpf_dep = $_POST['cpf_benefic_dep_editar'.$i];
      $nome_dep = $_POST['nome_dep_editar'.$i];
      $nome_mae_dep = $_POST['nome_mae_dep_editar'.$i];
      $nascimento_dep = $_POST['nascimento_dep_editar'.$i];
      $sexo_dep = $_POST['sexo_dep_editar'.$i];
      $estado_civil_dep = $_POST['estado_civil_dep_editar'.$i];
      $parentesco_dep = $_POST['parentesco_dep_editar'.$i];

      if(strlen($cpf_dep) < 14 || $nome_dep == '' || $nome_mae_dep == '' || strlen($nascimento_dep) < 10 || $sexo_dep == '' || $parentesco_dep == '' || $estado_civil_dep == ''){
        $erro += 1;
      }
    }
    if($erro > 0){
      echo json_encode("dep-invalid");
    } else {
      $query = "UPDATE wp_beneficiario set estado_civil = '$estado_civil', naturalidade = '$naturalidade', rg = '$rg', orgao = '$orgao',
      cep = '$cep', rua = '$rua', numero = '$numero', complemento = '$complemento', cidade = '$cidade', bairro = '$bairro',
      uf = '$uf', tel_res = '$tel_res', tel_cel = '$tel_cel', email = '$email', sus = '$sus', produto = '$produto' where cpf = '$cpf'";
      $exec = mysqli_query($conexao, $query);

      if($exec) {
        for($i=1; $i<=$qtd_dep; $i++) {
          $cpf_dep = $_POST['cpf_benefic_dep_editar'.$i];
          $nome_dep = $_POST['nome_dep_editar'.$i];
          $nome_mae_dep = $_POST['nome_mae_dep_editar'.$i];
          $nascimento_dep = $_POST['nascimento_dep_editar'.$i];
          $sexo_dep = $_POST['sexo_dep_editar'.$i];
          $estado_civil_dep = $_POST['estado_civil_dep_editar'.$i];
          $parentesco_dep = $_POST['parentesco_dep_editar'.$i];

          $query_dep = "UPDATE wp_dependente set cpf = '$cpf_dep', nome = '$nome_dep', nome_mae = '$nome_mae_dep', nascimento = '$nascimento_dep',
          sexo = '$sexo_dep', estado_civil = '$estado_civil_dep', parentesco = '$parentesco_dep' where cpf = '$cpf_dep'";
          $exec_dep = mysqli_query($conexao, $query_dep);
        }
         echo json_encode("success");
       } else {
         echo json_encode("unsuccessfull");
       }
    }
  }
}

if($funcao == "anexar_documento"){
  $cpf = $_POST['cpf_anexo'];

  $target_dir = 'documentos_pj/doc_benefic/';

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
          $hash_random = randString(60);

          $novo_nome = $hash_random.$key.$ext;
          $target = $target_dir . basename($novo_nome);
          $tmp  = $_FILES['file']['tmp_name'][$key];
          move_uploaded_file($tmp, $target);
          $nome_original = $_FILES['file']['name'][$key];

          $query_anexo = "INSERT INTO wp_anexos (nome, nome_original, cpf_titular) VALUES ('$novo_nome', '$nome_original','$cpf')";
          $exec_anexo = mysqli_query($conexao, $query_anexo);
        }
      }
      for($k = 0; $k < $total_files; $k++) {
        if($_FILES['file']['name'][$k] == ""){
          break;
        } else {
          echo json_encode($cpf);
          break;
        }
      }
    }
  }
}

if($funcao == "listar_anexos"){
  $cpf = $_POST['cpf'];
  $cpf_explode = str_split($cpf);
  $cpf_final = $cpf_explode[0].$cpf_explode[1].$cpf_explode[2].'.'.$cpf_explode[3].$cpf_explode[4].
  $cpf_explode[5].'.'.$cpf_explode[6].$cpf_explode[7].$cpf_explode[8].'-'.$cpf_explode[9].$cpf_explode[10];

  $qryLista3 = mysqli_query($conexao, "SELECT * FROM wp_anexos where cpf_titular = '$cpf'");
    while($resultado3 = mysqli_fetch_assoc($qryLista3)){
      $vetor[] = array_map('utf8_encode', $resultado3);
    }
  echo json_encode($vetor);
}

if($funcao == "add_dep"){
  $cpf_dep_add = $_POST['cpf_dep_add'];
  $nome_dep_add = $_POST['nome_dep_add'];
  $nome_mae_dep_add = $_POST['nome_mae_dep_add'];
  $nascimento_dep_add = $_POST['nascimento_dep_add'];
  $dnv_dep_add = $_POST['dnv_dep_add'];
  $sus_dep_add = $_POST['sus_dep_add'];
  $sexo_dep_add = $_POST['sexo_dep_add'];
  $estado_civil_dep_add = $_POST['estado_civil_dep_add'];
  $parentesco_dep_add = $_POST['parentesco_dep_add'];
  $cpf_titular = $_POST['cpf_titular'];

  $contar_dependentes = mysqli_query($conexao, "SELECT * FROM wp_dependente where cpf = '$cpf_dep_add'");
  $exec_contar_dep = mysqli_num_rows($contar_dependentes);

  if(strlen($cpf_dep_add) < 14){
    echo json_encode("cpf-invalid");

  } else if($exec_contar_dep > 0) {
    echo json_encode("cpf-existe");

  } else if($nome_dep_add == ""){
    echo json_encode("nome-invalid");

  } else if($nome_mae_dep_add == ""){
    echo json_encode("nome-mae-invalid");

  } else if($nascimento_dep_add == ""){
    echo json_encode("nascimento-invalid");

  } else if($sexo_dep_add == ""){
    echo json_encode("sexo-invalid");

  } else if($estado_civil_dep_add == ""){
    echo json_encode("estado-civil-invalid");

  } else if($parentesco_dep_add == ""){
    echo json_encode("parentesco-invalid");
  } else {
    $data_invertida_dep = explode("/", $nascimento_dep_add);
    $data_invertida_final_dep = $data_invertida_dep[2].'-'.$data_invertida_dep[1].'-'.$data_invertida_dep[0];

    /*$cpf_explode = str_split($cpf_titular);
    $cpf_final = $cpf_explode[0].$cpf_explode[1].$cpf_explode[2].'.'.$cpf_explode[3].$cpf_explode[4].
    $cpf_explode[5].'.'.$cpf_explode[6].$cpf_explode[7].$cpf_explode[8].'-'.$cpf_explode[9].$cpf_explode[10];*/

    $date_dep = new DateTime($data_invertida_final_dep);
    $interval_dep = $date_dep->diff(new DateTime(date('d-m-Y')));
    $idade_dep = $interval_dep->format('%Y');

    if($idade_dep > 0 && $idade_dep <= 18){
      $faixa_dep = 1;
    } else if ($idade_dep >= 19 && $idade_dep <= 23){
      $faixa_dep = 2;
    } else if ($idade_dep >= 24 && $idade_dep <= 28){
      $faixa_dep = 3;
    } else if ($idade_dep >= 29 && $idade_dep <= 33){
      $faixa_dep = 4;
    } else if ($idade_dep >= 34 && $idade_dep <= 38){
      $faixa_dep = 5;
    } else if ($idade_dep >= 39 && $idade_dep <= 43){
      $faixa_dep = 6;
    } else if ($idade_dep >= 44 && $idade_dep <= 48){
      $faixa_dep = 7;
    } else if ($idade_dep >= 49 && $idade_dep <= 53){
      $faixa_dep = 8;
    } else if ($idade_dep >= 54 && $idade_dep <= 58){
      $faixa_dep = 9;
    } else if ($idade_dep >= 59){
      $faixa_dep = 10;
    }

    $query_dados_benefic = mysqli_query($conexao, "SELECT * FROM wp_beneficiario where cpf = '$cpf_titular'");
    $exec_dados_benefic = mysqli_fetch_array($query_dados_benefic);
    $proposta = $exec_dados_benefic['proposta'];
    $produto = $exec_dados_benefic['produto'];

    $query_dep = "INSERT INTO wp_dependente (cpf, nome, nome_mae, nascimento, faixa_idade, sexo, estado_civil, parentesco, cns, dnv, cpf_titular, proposta, produto)
    VALUES ('$cpf_dep_add', '$nome_dep_add', '$nome_mae_dep_add', '$nascimento_dep_add', '$faixa_dep', '$sexo_dep_add',
      '$estado_civil_dep_add', '$parentesco_dep_add',
      '$sus_dep_add', '$dnv_dep_add', '$cpf_titular', '$proposta', '$produto')";
    $exec_dep = mysqli_query($conexao, $query_dep);

      echo json_encode("success");

  }
}

if($funcao == "remover_benefic"){
  $cpf = $_POST['cpf'];

  $delete_benefic_benefic_query = "DELETE FROM wp_beneficiario where cpf = '$cpf'";
  $delete_benefic_benefic_exec = mysqli_query($conexao, $delete_benefic_benefic_query);

  $delete_benefic_dep_query = "DELETE FROM wp_dependente where cpf_titular = '$cpf'";
  $delete_benefic_dep_exec = mysqli_query($conexao, $delete_benefic_dep_query);
  echo json_encode("apagado_benefic");
}
//Fim das funções relacionadas a inserção de beneficiários da proposta//



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

if($funcao == "remover_material"){
  $id = $_POST['id'];

  $data = ['id' => $id,];

  $deletar =$pdo->prepare("DELETE FROM material_venda WHERE id=:id limit 1");
  $deletar->execute($data);

  echo json_encode("deletado");
}

// Fim das funções de criação e listagem de material de Vendas //

// Funções do corretor //

if($funcao == "alterar_foto"){
  //$id = $_POST['id_corretor'];
  //$erro = 0;

  $target_dir = 'documentos_pj/foto_corretor/';

  if(isset($_FILES['file']['name'])) {

    $total_files = count($_FILES['file']['name']);

    for($key = 0; $key < $total_files; $key++) {

      $ext_teste[] = pathinfo($_FILES['file']['name'][$key], PATHINFO_EXTENSION);
      //echo''.$ext_teste[$key];

      if($_FILES['file']['name'][$key] == ""){
        echo json_encode ("vazio");
      } else if($ext_teste[$key] != 'png' && $ext_teste[$key] != 'PNG' && $ext_teste[$key] != 'jpg' && $ext_teste[$key] != 'JPG' && $ext_teste[$key] != 'jpeg' && $ext_teste[$key] != 'JPEG' && $ext_teste[$key] != 'jfif') {
        echo json_encode ("img-errada");
      } else {
        for($key = 0; $key < $total_files; $key++) {
          if(isset($_FILES['file']['name'][$key])
                                    && $_FILES['file']['size'][$key] > 0) {
  
            $original_filename = $_FILES['file']['name'][$key];
            $ext = pathinfo($_FILES['file']['name'][$key], PATHINFO_EXTENSION);
            $hash_random = randString(60);
  
            $novo_nome = $hash_random.$key.'.'.$ext;
            $target = $target_dir . basename($novo_nome);
            $tmp  = $_FILES['file']['tmp_name'][$key];
            move_uploaded_file($tmp, $target);
            $nome_original = $_FILES['file']['name'][$key];

            $data = [
              'foto' => $novo_nome,
              'id' => $id
            ];

            $altera_foto = $pdo->prepare('UPDATE users SET foto = :foto WHERE id = :id limit 1');
            $altera_foto->execute($data);
            echo json_encode("alterado");
            //$query_anexo = "INSERT INTO wp_anexos_empresa (nome, nome_original, id_contrato) VALUES ('$novo_nome', '$nome_original', '$id')";
            //$exec_anexo = mysqli_query($conexao, $query_anexo);
          }
        }
      }
    }
  }
}

if($funcao == "alterar_senha"){
  require 'Usuario.class.php';

  $u = new alterar_senha_class();
  $email = $_POST['email'];
  $senha_atual = $_POST['senha_atual'];
  $nova_senha = $_POST['nova_senha'];
  $confirmar_senha = $_POST['confirmar_senha'];
  
  if(empty($senha_atual) || empty($nova_senha) || empty($confirmar_senha)){
    echo json_encode("campos_vazios");
  } else if (strlen($nova_senha) < 8 || strlen($nova_senha) > 16){
    echo json_encode("caracter_invalido");
  } else if($nova_senha != $confirmar_senha){
    echo json_encode("senhas_diferentes");
  } else if ($u->alterar_senha($email, $senha_atual) == false){
    echo json_encode("senha_incorreta");
  } else {
    //atualizar_senha($confirmar_senha);

    try {
      $data2 = [
        'senha_nova' => md5($confirmar_senha),
        'email' => $email
      ];

      $altera_nova_senha = $pdo->prepare('UPDATE users SET senha = :senha_nova WHERE email = :email limit 1');
      $altera_nova_senha->execute($data2);
      echo json_encode('senha_alterada');

    } catch(PDOException $e) {
      echo 'Error: ' . $e->getMessage();
    }
  }
  

  /*echo''.$email;
  echo'<br>'.$senha_atual;
  echo'<br>'.$nova_senha;
  echo'<br>'.$confirmar_senha;*/

}

// Fim das funções do corretor //

// Funções do administrador //

if($funcao == "publicar_comunicado"){
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];

    if(empty($titulo)){
       echo json_encode("titulo-vazio"); 
    } else if(empty($conteudo)){
        echo json_encode("conteudo-vazio"); 
    } else{
        $data = [
            'titulo' => $titulo,
            'conteudo' => $conteudo
        ];

        try {
            $stmt = $pdo->prepare('INSERT INTO comunicados (titulo, conteudo, data_hora) VALUES (:titulo, :conteudo, NOW())');
            $stmt->execute($data);
        
            echo $stmt->rowCount();
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}

if($funcao == "listar_comunicados"){
    $statement = $pdo->prepare("SELECT * FROM comunicados order by data_hora DESC");
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);
}

if($funcao == "editar_comunicado"){
    $id_editar = $_POST['id_editar'];
    $titulo_editar = $_POST['titulo_editar'];
    $conteudo_editar = $_POST['conteudo_editar'];

    if(empty($titulo_editar)){
        echo json_encode("titulo_vazio");
    } else if(empty($conteudo_editar)){
        echo json_encode("conteudo_vazio");
    } else {
        try {
            $data2 = [
              'id' => $id_editar,
              'titulo' => $titulo_editar,
              'conteudo' => $conteudo_editar
            ];
      
            $alterar_comunicado = $pdo->prepare('UPDATE comunicados SET titulo = :titulo, conteudo = :conteudo WHERE id = :id limit 1');
            $alterar_comunicado->execute($data2);
            echo json_encode('com_alterado');
      
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}



function randString($size){
  $basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

   $return= "";

   for($count= 0; $size > $count; $count++)
   {
       $return.= $basic[rand(0, strlen($basic) - 1)];
   }
   return $return;
}


?>

