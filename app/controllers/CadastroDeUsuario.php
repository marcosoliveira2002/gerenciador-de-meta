<?php
namespace App\Controllers;

use App\Infra\Database;

class CadastroDeUsuario
{
  public function CadastroUsuario($postVars)
  {
    // if (class_exists('App\Infra\Database')) {
    //   echo "Database carregada com sucesso!";
    // } else {
    //   echo "Erro ao carregar Database.";
    //   exit;
    // }

    if (
      !$postVars ||
      !isset($postVars['nome']) ||
      !isset($postVars['email']) ||
      !isset($postVars['senha'])
    ) {
      echo "Dados inválidos.";
      return;
    }

    // echo "Dados recebidos: ";
    // print_r($postVars);  
    // echo "<br>"; exit;

    $db = new Database();
    $connection = $db->getConnection();
    $id_usuario = bin2hex(random_bytes(16));
    $senhaHash = password_hash($postVars['senha'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (id_usuario, nome, email, senha) VALUES (:id_usuario, :nome, :email, :senha)";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->bindParam(':nome', $postVars['nome']);
    $stmt->bindParam(':email', $postVars['email']);
    $stmt->bindParam(':senha', $senhaHash);

    if ($stmt->execute()) {
      echo "Usuário cadastrado com sucesso!";
    } else {
      echo "Erro ao cadastrar usuário.";
    }
  }
}
