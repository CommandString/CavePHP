<?php

use CavePHP\Server\Client;
use CavePHP\Server\Logger\Color;
use CavePHP\Server\Packets\GenericPacket;
use CavePHP\Server\Packets\Uniq\Handshaking\Handshake;
use CavePHP\Server\Server;

require_once __DIR__ . '/vendor/autoload.php';

$server = new Server('127.0.0.1');

$server->on(Server::READY, function () use ($server) {
    $server->logger->info(bold('READY', Color::GREEN));
});

$server->on(Server::ERROR, function (Throwable $e) use ($server) {
    $server->logger->error('Server has errored!');
    echo $e->getMessage() . PHP_EOL;
});

$server->on(Server::CLOSE, function (Client $client, Server $server) {
    $server->logger->info('Server has closed');
});

$server->on(Server::NEW_CLIENT, function (Client $client, Server $server) {
    $server->logger->info('New client handshake starting [' . color(Color::GREEN, $client->getIp()) . ']');

    $client->on(Client::GENERIC_PACKET, static function (GenericPacket $packet, Client $client) {
        var_dump($packet->getType());
    });

    $client->on(Client::DISCONNECT, static function (Client $client) {
        $client->server->logger->info('Client disconnected [' . color(Color::GREEN, $client->getIp()) . ']');
    });
});

$server->on(Handshake::class, static function (Handshake $packet, Client $client): void {
    echo 'Handshake packet received' . PHP_EOL;
});

$server->start();
