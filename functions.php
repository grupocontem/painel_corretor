<?php

include("conexao.php");

$id = $_SESSION['idUser'];
$funcao = $_POST['funcao'];


if($funcao == "anexar_material") {

  $target_dir = 'material_venda/';
  $nome_arquivo = $_POST['nome'];
  $operadora = $_POST['operadora'];
  $tipo = $_POST['tipo'];

  /*echo''.$_POST['nome'];
  echo'<br>'.$_POST['operadora'];
  echo'<br>'.$_POST['tipo'];
  echo'<br>'.$_FILES['file']['tmp_name'];*/

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

?>
