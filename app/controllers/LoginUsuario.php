<?php
namespace App\Controllers;

use App\Infra\Database;
use \Firebase\JWT\JWT;

class LoginUsuario
{


  public function validaLogin($postVars)
  {
    if (!isset($postVars['email']) || !isset($postVars['senha'])) {
      echo "Dados inválidos.";
      return;
    }
   $secretKey = 'asdhasbdhguavbsdhjtrabalhodopeperesasdknasjdnasjd'; 

    $db = new Database();
    $connection = $db->getConnection();

    $sql = "SELECT id_usuario, senha FROM usuarios WHERE email = :email";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':email', $postVars['email']);
    $stmt->execute();


    if ($stmt->rowCount() === 0) {
      echo "Usuário não encontrado.";
      return;
    }


    $user = $stmt->fetch();

    if (!password_verify($postVars['senha'], $user['senha'])) {
      echo "Senha incorreta.";
      return;
    }

// agr mais 3600 segundos ou seja 1 hora
    $payload = [
      'exp' => time() + 3600, 
      'iat' => time(),
      'id_usuario' => $user['id_usuario'], 
    ];


    $encode = JWT::encode($payload, $secretKey,'HS256');


    echo json_encode($encode);
  }
}
