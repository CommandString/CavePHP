<?php

namespace CavePHP\Server\Packets;

use CavePHP\Exceptions\CavePHPException;
use Countable;
use stdClass;
use Stringable;

class Bytes implements Countable, Stringable
{
    private stdClass $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    /**
     * @throws CavePHPException If a given byte is not a string of length 1
     */
    public function push(string $byte, string ...$bytes): void
    {
        $bytes = [$byte, ...$bytes];

        foreach ($bytes as $byte) {
            if (strlen($byte) !== 1) {
                throw new CavePHPException('Byte must be a string of length 1');
            }

            $this->data->{$this->count()} = $byte;
        }
    }

    public function getByte(int $index): mixed
    {
        return $this->data->{$index};
    }

    public function getRange(int $start = 0, int $length = -1): string
    {
        return substr(implode('', get_object_vars($this->data)), $start, $length);
    }

    public function count(): int
    {
        return count(get_object_vars($this->data));
    }

    public function __toString()
    {
        return implode('', get_object_vars($this->data));
    }
}
