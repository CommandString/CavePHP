<?php

namespace CavePHP\Server\Packets;

use CavePHP\Server\Client;

class GenericPacket
{
    private readonly int $length;
    private readonly PacketType $type;
    protected readonly ByteStream $stream;

    public function __construct(
        public readonly Client $client
    ) {
        $this->stream = new ByteStream();
    }

    public function getLength(): int
    {
        return $this->length ??=
            ByteStream::createWithData(
                $this->stream->getData()
            )->readVarInt();
    }

    public function getType(): PacketType
    {
        return $this->type ??=
            PacketType::createFromIdAndStatus(
                ByteStream::createWithData(
                    $this->stream->getData()->getRange(3)
                )->readVarInt(),
                $this->client->getState()
            );
    }

    public function write(string $data): void
    {
        $this->stream->write($data);
    }

    public function isReady(): bool
    {
        var_dump((string)$this->stream->getData());

        return $this->getLength() === $this->length;
    }
}
