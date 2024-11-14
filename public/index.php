<?php
require '../vendor/autoload.php';
require "../routes/router.php";

header("Access-Control-Allow-Origin: *");

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
try {
  $uri = parse_url($_SERVER['REQUEST_URI'])["path"];
  $request = $_SERVER['REQUEST_METHOD'];
  if (!isset($routes[$request])) {
    throw new Exception('A rota não existe');
  }
  if (!array_key_exists($uri, $routes[$request])) {
    throw new Exception('A rota não existe');
  }
 $controller = $routes[$request][$uri];
 $controller();
} catch (Exception $e) {
  $e->getMessage();
}