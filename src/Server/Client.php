<?php

namespace CavePHP\Server;

use CavePHP\Server\Packets\ClientState;
use CavePHP\Server\Packets\GenericPacket;
use CavePHP\Utilities\EventEmitter;
use React\Socket\ConnectionInterface;

/**
 * @property $username
 */
class Client extends EventEmitter
{
    public const RAW_DATA = 'raw_data';
    public const GENERIC_PACKET = 'generic_packet';
    public const DISCONNECT = 'disconnect';

    private ClientState $state = ClientState::HANDSHAKING;

    public function __construct(
        protected readonly ConnectionInterface $connection,
        public readonly Server $server
    ) {
        $this->setupEvents();
    }

    public function getIp(): string
    {
        return $this->connection->getRemoteAddress();
    }

    public function getState(): ClientState
    {
        return $this->state;
    }

    private function setupEvents(): void
    {
        $this->connection->on('data', function ($data) {
            $this->emit(self::RAW_DATA, [$data]);
        });

        $this->connection->on('close', fn () => $this->emit(self::DISCONNECT));

        /** @var ?GenericPacket $packet */
        $packet = null;

        $this->on(self::RAW_DATA, function ($data) use (&$packet) {
            $packet = new GenericPacket($this);

            $packet->write($data);

            if ($packet->isReady()) {
                $this->emit(self::GENERIC_PACKET, [$packet]);
                $packet = null;
            }
        });
    }

    public function emit($event, array $arguments = []): void
    {
        parent::emit($event, [...$arguments, $this]);
    }

    public function disconnect(): void
    {
        $this->connection->close();
    }
}