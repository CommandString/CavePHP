<?php

use CavePHP\Server\Logger\Level;
use CavePHP\Server\Server;

require_once __DIR__ . '/vendor/autoload.php';

$server = new Server();

$server->on(Server::READY, function () use ($server) {
    $server->logger->log(Level::INFO, 'Server is ready!');
});

$server->start();
