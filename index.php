<?php

require __DIR__ . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Max-Age: 86400");
    http_response_code(200);
    exit();
}

$app = require __DIR__ . '/bootstrap/app.php';

$container = $app['container'];
$router = $app['router'];

// Router and routes
$match = $router->match();

if ($match && is_array($match['target'])) {
    $controllerClass = $match['target']['controller'];
    $action = $match['target']['action'];
    $params = $match['params'] ?? [];

    $controller = $container->make($controllerClass);
    if (!method_exists($controller, $action)) {
        header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        echo "Action '$action' not found on controller '$controllerClass'.";
        exit;
    }
    $controller->$action();
} else {
    // No hay match
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    echo "Route not found.";
}