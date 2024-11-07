<?php
namespace App\Controllers;

use App\Infra\Database;
use Exception;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
class CadastroDeMetas {
  

    public function CadastroMeta($postVars) {
     $secretKey = 'asdhasbdhguavbsdhjtrabalhodopeperesasdknasjdnasjd';  
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            echo "Token não fornecido.";
            return;
        }

        $jwt = str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']);
        $algoritioCripto = ['HS256'];

        try {
            $decoded   = JWT::decode($jwt, new Key($secretKey, 'HS256'));
            $id_usuario = $decoded->id_usuario; 
        } catch (Exception $e) {
            echo json_encode($e->getMessage());
            return;
        }

        if (
            !isset($postVars['titulo']) || 
            !isset($postVars['descricao'])
        ) {
            echo "Dados inválidos.";
            return;
        }

        $status = 'P'; 

    
        $db = new Database();
        $connection = $db->getConnection();


        $sql = "INSERT INTO metas (id_usuario, titulo, descricao, status) 
                VALUES (:id_usuario, :titulo, :descricao, :status)";
        $stmt = $connection->prepare($sql);
        

        $stmt->bindParam(':id_usuario', $id_usuario); 
        $stmt->bindParam(':titulo', $postVars['titulo']);
        $stmt->bindParam(':descricao', $postVars['descricao']);
        $stmt->bindParam(':status', $status);


        if ($stmt->execute()) {
            echo "Meta cadastrada com sucesso!";
        } else {
            echo "Erro ao cadastrar meta.";
        }
    }
}
