<?php

namespace App\Controllers;

use App\Infra\Database;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;

class DeletarMeta {
  public function deletar($postVars) {
    $secretKey = 'asdhasbdhguavbsdhjtrabalhodopeperesasdknasjdnasjd';

    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
      echo json_encode(['error' => 'Token não fornecido.']);
      return;
    }

    $jwt = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
    
    try {
      $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
      $id_usuario = $decoded->id_usuario;
      if (!isset($postVars['id_meta'])) {
        echo json_encode(['error' => 'ID da meta não fornecido.']);
        return;
      }

      $id_meta = $postVars['id_meta'];


      $db = new Database();
      $connection = $db->getConnection();


      $sql = "SELECT id_usuario FROM metas WHERE id_meta = :id_meta";
      $stmt = $connection->prepare($sql);
      $stmt->bindParam(':id_meta', $id_meta);
      $stmt->execute();
      $meta = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$meta) {
        echo json_encode(['error' => 'Meta não encontrada.']);
        return;
      }
      if ($meta['id_usuario'] !== $id_usuario) {
        echo json_encode(['error' => 'Você não tem permissão para excluir esta meta.']);
        return;
      }

      $sqlDelete = "DELETE FROM metas WHERE id_meta = :id_meta";
      $stmtDelete = $connection->prepare($sqlDelete);
      $stmtDelete->bindParam(':id_meta', $id_meta);

      if ($stmtDelete->execute()) {
        echo json_encode(['message' => 'Meta excluída com sucesso.']);
      } else {
        echo json_encode(['error' => 'Erro ao excluir a meta.']);
      }

    } catch (Exception $e) {
      echo json_encode(['error' => 'Erro ao decodificar o token: ' . $e->getMessage()]);
    }
  }
}
