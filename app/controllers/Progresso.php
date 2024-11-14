<?php
namespace App\Controllers;

use App\Infra\Database;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class Progresso
{
  private $secretKey = 'asdhasbdhguavbsdhjtrabalhodopeperesasdknasjdnasjd';

  public function cadastrarProgresso($postVars)
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

    if (!isset($postVars['id_meta']) || !isset($postVars['descricao_progresso'])) {
      echo "Dados inválidos.";
      return;
    }

    $id_meta = $postVars['id_meta'];
    $descricao_progresso = $postVars['descricao_progresso'];

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

    if ($meta['id_usuario'] !== $id_usuario) {
      echo "Você não tem permissão para adicionar progresso a esta meta.";
      return;
    }


    $sqlInsert = "INSERT INTO progresso (id_meta, descricao_progresso, data_atualizacao) 
                      VALUES (:id_meta, :descricao_progresso, CURRENT_TIMESTAMP)";
    $stmtInsert = $connection->prepare($sqlInsert);
    $stmtInsert->bindParam(':id_meta', $id_meta);
    $stmtInsert->bindParam(':descricao_progresso', $descricao_progresso);

    if ($stmtInsert->execute()) {
      echo "Progresso cadastrado com sucesso!";
    } else {
      echo "Erro ao cadastrar o progresso.";
    }
  }

  public function consultaPorMeta($postVars)
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

    if (!isset($postVars['id_meta'])) {
      echo "ID da meta não fornecido.";
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

    if ($meta['id_usuario'] !== $id_usuario) {
      echo "Você não tem permissão para visualizar o progresso desta meta.";
      return;
    }


    $sqlProgresso = "SELECT * FROM progresso WHERE id_meta = :id_meta ORDER BY data_progresso DESC";
    $stmtProgresso = $connection->prepare($sqlProgresso);
    $stmtProgresso->bindParam(':id_meta', $id_meta);
    $stmtProgresso->execute();
    $progressos = $stmtProgresso->fetchAll();

    if ($progressos) {
      echo json_encode($progressos);
    } else {
      echo "Nenhum progresso encontrado para esta meta.";
    }
  }
  public function editarProgresso($postVars)
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

    if (
      !isset($postVars['id_progresso']) ||
      !isset($postVars['descricao_progresso'])
    ) {
      echo "Dados inválidos.";
      return;
    }

    $id_progresso = $postVars['id_progresso'];
    $descricao_progresso = $postVars['descricao_progresso'];

    $db = new Database();
    $connection = $db->getConnection();

    $sqlProgresso = "SELECT p.id_meta, m.id_usuario 
                     FROM progresso p 
                     JOIN metas m ON p.id_meta = m.id_meta 
                     WHERE p.id_progresso = :id_progresso";
    $stmtProgresso = $connection->prepare($sqlProgresso);
    $stmtProgresso->bindParam(':id_progresso', $id_progresso);
    $stmtProgresso->execute();
    $progresso = $stmtProgresso->fetch();

    if (!$progresso) {
      echo "Progresso não encontrado.";
      return;
    }

    if ($progresso['id_usuario'] !== $id_usuario) {
      echo "Você não tem permissão para editar este progresso.";
      return;
    }

    $sqlUpdate = "UPDATE progresso 
                  SET descricao_progresso = :descricao_progresso, data_atualizacao = CURRENT_TIMESTAMP 
                  WHERE id_progresso = :id_progresso";
    $stmtUpdate = $connection->prepare($sqlUpdate);
    $stmtUpdate->bindParam(':descricao_progresso', $descricao_progresso);
    $stmtUpdate->bindParam(':id_progresso', $id_progresso);

    if ($stmtUpdate->execute()) {
      echo "Progresso atualizado com sucesso!";
    } else {
      echo "Erro ao atualizar o progresso.";
    }
  }
  public function deletarProgresso($postVars)
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

    if (!isset($postVars['id_progresso'])) {
      echo "ID do progresso não fornecido.";
      return;
    }

    $id_progresso = $postVars['id_progresso'];

    $db = new Database();
    $connection = $db->getConnection();

    $sqlProgresso = "SELECT p.id_meta, m.id_usuario 
                       FROM progresso p 
                       JOIN metas m ON p.id_meta = m.id_meta 
                       WHERE p.id_progresso = :id_progresso";
    $stmtProgresso = $connection->prepare($sqlProgresso);
    $stmtProgresso->bindParam(':id_progresso', $id_progresso);
    $stmtProgresso->execute();
    $progresso = $stmtProgresso->fetch();

    if (!$progresso) {
      echo "Progresso não encontrado.";
      return;
    }

    if ($progresso['id_usuario'] !== $id_usuario) {
      echo "Você não tem permissão para deletar este progresso.";
      return;
    }

    $sqlDelete = "DELETE FROM progresso WHERE id_progresso = :id_progresso";
    $stmtDelete = $connection->prepare($sqlDelete);
    $stmtDelete->bindParam(':id_progresso', $id_progresso);

    if ($stmtDelete->execute()) {
      echo "Progresso deletado com sucesso!";
    } else {
      echo "Erro ao deletar o progresso.";
    }
  }

}
