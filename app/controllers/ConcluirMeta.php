<?php

namespace App\Controllers;

use App\Infra\Database;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ConcluirMeta
{
  private $secretKey = 'asdhasbdhguavbsdhjtrabalhodopeperesasdknasjdnasjd';

  public function concluirMeta($postVars)
  {
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
      echo "Token não fornecido.";
      return;
    }

    $jwt = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);

    try {
      $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
      $id_usuario = $decoded->id_usuario;
    } catch (Exception $e) {
      echo json_encode($e->getMessage());
      return;
    }

    $id_meta = $postVars['id_meta'];


    $db = new Database();
    $connection = $db->getConnection();


    $sqlMeta = "SELECT id_usuario FROM metas WHERE id_meta = :id_meta";
    $stmtMeta = $connection->prepare($sqlMeta);
    $stmtMeta->bindParam(':id_meta', $id_meta);
    $stmtMeta->execute();
    $meta = $stmtMeta->fetch();

    if (!$meta) {
      echo "Meta não encontrada.";
      return;
    }


    if ($sqlMeta['id_usuario'] !== $id_usuario) {
      echo "Você não tem permissão para editar este progresso.";
      return;
    }

    $sqlUpdate = "UPDATE metas 
                  SET status = 'C' 
                  WHERE id_meta = :id_meta";
    $stmtUpdate = $connection->prepare($sqlUpdate);

    $stmtUpdate->bindParam(':id_meta', $id_meta);

    if ($stmtUpdate->execute()) {
      echo "meta concluida com sucesso!";
    } else {
      echo "Erro ao atualizar meta.";
    }
  }
}