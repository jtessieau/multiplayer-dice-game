<?php
require_once "../vendor/autoload.php";

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    // Home route
    $r->addRoute('GET', '/', 'HomeController@index');
    $r->addRoute('GET', '/gameboard', 'HomeController@gameboard');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        http_response_code(404);
        include '../template/errors/404.html';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        http_response_code(405);
        include '../template/errors/405.html';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // ... call $handler with $vars

        $controllerPath = "\\App\\Controller\\";
        $exec = explode('@', $handler);
        $controller = $controllerPath . $exec[0];
        $method = $exec[1];

        $call = new $controller;
        if (empty($vars)) {
            $call->$method();
        } else {
            $params = implode(',', $vars);
            $call->$method($params);
        }
        break;
}