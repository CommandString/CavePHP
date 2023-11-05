<?php

namespace CavePHP\Server;

use CavePHP\Server\Logger\Level;
use CavePHP\Server\Logger\Logger;
use CavePHP\Server\Pools\ClientPool;
use CavePHP\Utilities\EventEmitter;
use React\Socket\ConnectionInterface;
use React\Socket\SocketServer;
use Throwable;

class Server extends EventEmitter {
    public const NEW_CLIENT = 'new_client';
    public const ERROR = 'error';
    public const READY = 'ready';

    private SocketServer $server;
    public readonly Logger $logger;
    public readonly ClientPool $clientPool;

    public function __construct(
        public readonly string $host = '127.0.0.1',
        public readonly int $port = 25565,
        ?Logger $logger = null
    ) {
        $this->logger = $logger ?? new Logger;
        $this->clientPool = new ClientPool;
    }

    public function start() {
        $this->server = new SocketServer("{$this->host}:{$this->port}");
        $this->setupEvents();
        $this->emit(self::READY);
    }

    private function setupEvents(): void {
        $this->server->on('connection', function (ConnectionInterface $connection) {
            $client = new Client($connection);
            $this->clientPool->add($client);
            $this->emit(self::NEW_CLIENT, [$client]);
            $this->logger->log(Level::INFO, "New client connected: {$client->getIp()}");
        });

        $this->server->on('error', function (Throwable $error) {
            $this->emit(self::ERROR, [$error]);
        });
    }
}