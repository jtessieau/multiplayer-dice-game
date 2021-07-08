<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Controller\Game;

require '../../vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Game()
        )
    ),
    8080
);

$server->run();