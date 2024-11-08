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
  ],
  "POST" => [
    "/usuarios" => fn() => load("CadastroDeUsuario", "CadastroUsuario"),
    "/metas" => fn() => load("CadastroDeMetas", "CadastroMeta"),
    "/usuarioLogin" => fn() => load("LoginUsuario", "validaLogin")
  ],
  "PUT" => [
   "/usuarioLogin" => fn() => load("UpdateMeta", "atualizar")
  ],
  "DELETE" => [
    "/metas" => fn() => load("DeletarMeta", "deletar"),
  ]
];
