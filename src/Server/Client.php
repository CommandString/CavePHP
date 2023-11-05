<?php

namespace CavePHP\Server;

use CavePHP\Utilities\EventEmitter;
use React\Socket\ConnectionInterface;

class Client extends EventEmitter
{
    public const RAW_DATA = 'raw_data';
    public const PACKET = 'packet';

    public function __construct(
        protected readonly ConnectionInterface $connection
    ) {
        $this->setupEvents();
    }

    public function getIp(): string
    {
        return $this->connection->getRemoteAddress();
    }

    private function setupEvents(): void
    {
        $this->connection->on('data', function ($data) {
            $this->emit('data', [$data]);
        });
    }
}