<?php

namespace App\Controllers;

use App\Infra\Database;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;

class UpdateMeta {

  public function atualizar($headers, $body)
  {
    $secretKey = 'asdhasbdhguavbsdhjtrabalhodopeperesasdknasjdnasjd';
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
      echo json_encode(['error' => 'Token não fornecido.']);
      return;
    }

    $jwt = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
    
    try {

      $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
      $id_usuario = $decoded->id_usuario;


      $db = new Database();
      $connection = $db->getConnection();

      $id_meta = $body['id_meta'] ?? null;
      $titulo = $body['titulo'] ?? null;
      $descricao = $body['descricao'] ?? null;
      $status = $body['status'] ?? null;

      if (!$id_meta) {
        echo json_encode(['error' => 'ID da meta não fornecido.']);
        return;
      }


      $sql = "SELECT id_meta FROM metas WHERE id_meta = :id_meta AND id_usuario = :id_usuario";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(':id_meta', $id_meta);
      $stmt->bindParam(':id_usuario', $id_usuario);

      $stmt->execute();
      $meta = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$meta) {
        echo json_encode(['error' => 'Meta não encontrada ou não pertence ao usuário.']);
        return;
      }


      $updateSql = "UPDATE metas SET titulo = :titulo, descricao = :descricao, status = :status 
                    WHERE id_meta = :id_meta";
      $updateStmt = $connection->prepare($updateSql);
      $updateStmt->bindParam(':id_meta', $id_meta);
      $updateStmt->bindParam(':titulo', $titulo);
      $updateStmt->bindParam(':descricao', $descricao);
      $updateStmt->bindParam(':status', $status);

      $updateStmt->execute();

      echo json_encode(['message' => 'Meta atualizada com sucesso.']);

    } catch (Exception $e) {
      echo json_encode(['error' => $e->getMessage()]);
    }
  }
}
