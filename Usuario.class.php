<?php

class Usuario {
  public function login($email, $senha){
    global $pdo;

    $sql = "SELECT * FROM users where email = :email AND senha = :senha";
    $sql = $pdo->prepare($sql);
    $sql->bindValue("email", $email);
    $sql->bindValue("senha", md5($senha));
    $sql->execute();

    if($sql->rowCount() > 0){
      $dado = $sql->fetch();
      $_SESSION['idUser'] = $dado['id'];
      return true;
    } else {
      return false;
    }
  }
}

class alterar_senha_class {
    public function alterar_senha($email, $senha){
      global $pdo;
  
      $sql = "SELECT * FROM users where email = :email AND senha = :senha";
      $sql = $pdo->prepare($sql);
      $sql->bindValue("email", $email);
      $sql->bindValue("senha", md5($senha));
      $sql->execute();
  
      if($sql->rowCount() > 0){
        return true;
      } else {
        return false;
      }
    }
  }
  
