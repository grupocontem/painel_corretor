<?php

session_start();

$localhost = 'grupocontem.com.br';
$user = 'grupocon_conexao';
$pass = 'c0Nt3m#2@1p';
$banco = 'grupocon_contem';

global $pdo;

try{
  $pdo = new PDO("mysql:dbname=".$banco."; host=".$localhost, $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
  echo "ERRO: ".$e->getMessage();
  exit;
}

?>
