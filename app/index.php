<?php

use App\SubRoutes\Routes;
require 'vendor/autoload.php';

$router = new Routes();

$controller;

$router->add('/produtos', function () use ($controller) {
    $controller->list();
 });