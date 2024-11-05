<?php

function load(string $controller, string $action)
{
  try {
    $controllerNamespace = "app\\controllers\\{$controller}";

    if (!class_exists($controllerNamespace)) {
      throw new Exception('Rota não encontrada');
    }

    $controllerInstance = new $controllerNamespace;

    if (!method_exists($controllerInstance, $action)) {
      throw new Exception('Metodo não existe');
    }

    $jsonInput = file_get_contents('php://input');
    $postVars = json_decode($jsonInput, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      throw new Exception('JSON inválido');
    }


    $controllerInstance->$action((object)$postVars);

  } catch (Exception $e) {
    echo $e->getMessage();
  }
}


$routes = [
  "GET" => [
    "/usuarios" => fn() => load("LoginUsuario", "validaLogin")
  ],
  "POST" => [
    "/usuarios" => fn() => load("CadastroDeUsuario", "CadastroUsuario")
  ],
  "PUT" => [

  ],
];