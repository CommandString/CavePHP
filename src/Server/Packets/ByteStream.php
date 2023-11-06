<?php

namespace CavePHP\Server\Packets;

use CavePHP\Exceptions\CavePHPException;
use CavePHP\Server\Packets\McVars\McVarInt;

class ByteStream
{
    public const SIGNED_CHAR = 'c';
    public const UNSIGNED_CHAR = 'C';
    public const SIGNED_SHORT = 's';
    public const UNSIGNED_SHORT = 'S';
    public const INT = 'i';
    public const LONG = 'l';
    public const FLOAT = 'f';
    public const DOUBLE = 'd';

    private Bytes $data;
    private int $position = 0;

    public function __construct()
    {
        $this->data = new Bytes();
    }

    /**
     * @throws CavePHPException If length is less than 1
     */
    public function read(int $length = 1): string
    {
        if ($length < 1) {
            throw new CavePHPException('Length must be greater than 0');
        }

        $data = $this->data->getRange($this->position, $length);
        $this->move($length);

        return $data;
    }

    public function unpack(string $format, int $length): mixed
    {
        return unpack($format, $this->read($length))[1];
    }

    public function getLength(): int
    {
        return count($this->data);
    }

    public function readByte(): int
    {
        return $this->unpack(self::SIGNED_CHAR, 1);
    }

    public function readUnsignedByte(): int
    {
        return $this->unpack(self::UNSIGNED_CHAR, 1);
    }

    public function readShort(): int
    {
        return $this->unpack(self::SIGNED_SHORT, 2);
    }

    public function readUnsignedShort(): int
    {
        return $this->unpack(self::UNSIGNED_SHORT, 2);
    }

    public function readInt(): int
    {
        return $this->unpack(self::INT, 4);
    }

    public function readLong(): int
    {
        return $this->unpack(self::LONG, 8);
    }

    public function readFloat(): float
    {
        return $this->unpack(self::FLOAT, 4);
    }

    public function readDouble(): float
    {
        return $this->unpack(self::DOUBLE, 8);
    }

    public function readBoolean(): bool
    {
        return $this->readByte() === 1;
    }

    public function readVarInt(): int
    {
        return (new McVarInt())->read($this);
    }

    /**
     * @throws CavePHPException
     */
    public function write(string $data): void
    {
        if (isset($this->length) && count($this->data) + strlen($data) > $this->length) {
            throw new CavePHPException('Data exceeds the length of the stream');
        }

        $this->data->push(...str_split($data));

        if (!isset($this->length)) {
            $this->rewind();
        }
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    /** @return int The new position after moving */
    public function move(int $length): int
    {
        return $this->position += $length;
    }

    public function getData(): Bytes
    {
        return $this->data;
    }

    public static function createWithData(string $data): self
    {
        $stream = new self();
        $stream->write($data);

        return $stream;
    }
}
