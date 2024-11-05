<?php

namespace App\Controllers;

use App\Infra\Database;

class CadastroDeUsuario{
  public function CadastroUsuario($postVars){
    $db = new Database();
    $connection = $db->getConnection();

    $sql = "INSERT INTO usuarios (usuario, senha) VALUES (:usuario, :senha)";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':usuario', $postVars->usuario);
    $stmt->bindParam(':senha', $postVars->senha);
    
    if ($stmt->execute()) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar usuário.";
    }
  }
}