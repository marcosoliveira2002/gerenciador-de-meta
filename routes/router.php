<?php

function load(string $controller, string $action)
{
  try {

    $controllerNamespace = "App\\Controllers\\{$controller}";

    if (!class_exists($controllerNamespace)) {
      throw new Exception('Rota não encontrada');
    }

    $controllerInstance = new $controllerNamespace;

    if (!method_exists($controllerInstance, $action)) {
      throw new Exception('Método não existe');
    }

    $jsonInput = file_get_contents('php://input');
    $postVars = json_decode($jsonInput, true);


    if (json_last_error() !== JSON_ERROR_NONE && $_SERVER['REQUEST_METHOD'] === 'POST') {
      throw new Exception('JSON inválido');
    }

    $params = $_SERVER['REQUEST_METHOD'] === 'GET' ? $_GET : $postVars;


    $controllerInstance->$action($params);

  } catch (Exception $e) {
    echo $e->getMessage();
  }
}


$routes = [
  "GET" => [
    "/metas" => fn() => load("RelatorioDeMetasDoUsuario", "listarMetas"),
    "/progresso" => fn() => load("Progresso", "consultaPorMeta"),
  ],
  "POST" => [
    "/usuarios" => fn() => load("CadastroDeUsuario", "CadastroUsuario"),
    "/metas" => fn() => load("CadastroDeMetas", "CadastroMeta"),
    "/usuarioLogin" => fn() => load("LoginUsuario", "validaLogin"),
    "/progresso" => fn() => load("Progresso", "cadastrarProgresso")
  ],
  "PUT" => [
   "/updateMeta" => fn() => load("UpdateMeta", "atualizar"),
   "/progresso" => fn() => load("Progresso", "editarProgresso"),
   "/concluirMeta" => fn() => load("ConcluirMeta", "concluirMeta"),
  ],
  "DELETE" => [
    "/metas" => fn() => load("DeletarMeta", "deletar"),
    "/progresso" => fn() => load("Progresso", "deletarProgresso"),
  ]
];
