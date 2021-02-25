<?php

session_start();

$localhost = '';
$user = '';
$pass = '';
$banco = '';

global $pdo;

try{
  $pdo = new PDO("mysql:dbname=".$banco."; host=".$localhost, $user, $pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
  echo "ERRO: ".$e->getMessage();
  exit;
}

?>
