<?php
namespace App\Controllers;

use App\Infra\Database;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;

class RelatorioDeMetasDoUsuario
{
  public function listarMetas($headers)
  {

    $secretKey = 'asdhasbdhguavbsdhjtrabalhodopeperesasdknasjdnasjd';
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
      echo "Token não fornecido.";
      return;
    }

    $jwt = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
    $algoritioCripto = ['HS256'];

    try {
      $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
      $id_usuario = $decoded->id_usuario;


      $db = new Database();
      $connection = $db->getConnection();

      $sql = "SELECT id_meta, titulo, descricao, status, data_criacao, data_conclusao 
              FROM metas WHERE id_usuario = :id_usuario";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(':id_usuario', $id_usuario);

      $stmt->execute();
      $metas = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if ($metas) {
        echo json_encode($metas);
      } else {
        echo json_encode(['message' => 'Nenhuma meta encontrada para este usuário.']);
      }

    } catch (Exception $e) {
      echo json_encode(['error' => $e->getMessage()]);
    }
  }
}
