<?php
require 'phpmailer/PHPMailerAutoload.php';

$nome = $_POST['nome'];
$nascimento = $_POST['nascimento'];
$tel_fixo = $_POST['fixo'];
$tel_cel = $_POST['celular'];
$email = $_POST['email'];
$distribuidora = $_POST['distribuidora'];
$captcha_data = $_POST['g-recaptcha-response'];

$files = $_FILES['file'];
$qtd_anexo = count($files['tmp_name']);

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

$msg = utf8_decode("<br><h3>Dados do corretor: </h3><br> Nome: ".$nome."<br> Nascimento: ".$nascimento."<br>"."Telefone Fixo: ".$tel_fixo.
                   "<br> Telefone Celular: ".$tel_cel."<br> Email: ".$email."<br> Distribuidora: ".$distribuidora."<br>");

$destinatario = "comercial@grupocontem.com.br";

for ($i=0; $i<$qtd_anexo; $i++){
    if($files['name'][$i] == ""){
      $error++;
    } else {
      $mail->AddAttachment($files['tmp_name'][$i], $files['name'][$i]);
    }
}

$mail->AddAddress($destinatario);
$mail->Subject = "Dados do corretor";
$mail->Body = $msg;
$mail->IsHTML(true);

if($nome == ""){
  echo json_encode("nome-invalid");
} else if($nascimento == ""){
  echo json_encode("nascimento-invalid");
} else if($tel_fixo == "") {
  echo json_encode("fixo-invalid");
} else if($tel_cel == "") {
  echo json_encode("cel-invalid");
} else if($email == "") {
  echo json_encode("email-invalid");
} else if($distribuidora == "") {
  echo json_encode("dist-invalid");
} else if($error >= 1) {
  echo json_encode("files-error");
} else if(empty($captcha_data)) {
  echo json_encode("captcha-error");
} else {
  $mail->Send();
  echo json_encode("ok");
}
?>
