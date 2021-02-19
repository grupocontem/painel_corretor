<?php

include('conexao.php');

$id_usuario = $_SESSION['idUser'];
$consulta = $pdo->query("SELECT * FROM users where id = $id_usuario;");

while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $nome = $linha['nome'];
    $tipo_usuario = $linha['tipo_user'];
    $codigo_corretor = $linha['codigo_corretor'];
}

//require 'phpmailer/PHPMailerAutoload.php';

$conexao = mysqli_connect("grupocontem.com.br", "grupocon_conexao", "c0Nt3m#2@1p", "grupocon_vendapj") or die("Sem conexao");
if (mysqli_connect_errno()) trigger_error(mysqli_connect_error());


