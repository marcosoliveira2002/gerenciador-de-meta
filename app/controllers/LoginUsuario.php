<?php
namespace App\Controllers;

use App\Infra\Database;
use \Firebase\JWT\JWT;

class LoginUsuario
{
  public function validaLogin($postVars)
  {
    header('Content-Type: application/json'); 

    if (!isset($postVars['email']) || !isset($postVars['senha'])) {
      echo json_encode(['error' => 'Dados inválidos.']);
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
      echo json_encode(['error' => 'Usuário não encontrado.']);
      return;
    }

    $user = $stmt->fetch();

    if (!password_verify($postVars['senha'], $user['senha'])) {
      echo json_encode(['error' => 'Senha incorreta.']);
      return;
    }

    $payload = [
      'exp' => time() + 3600, 
      'iat' => time(),
      'id_usuario' => $user['id_usuario']
    ];

    $encode = JWT::encode($payload, $secretKey, 'HS256');


    echo json_encode(['token' => $encode]);
  }
}
