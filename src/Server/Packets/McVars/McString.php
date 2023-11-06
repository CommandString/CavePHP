<?php

namespace CavePHP\Server\Packets\McVars;

use Attribute;
use CavePHP\Server\Packets\ByteStream;

#[Attribute(Attribute::TARGET_PROPERTY)]
class McString implements McVar
{
    public readonly int $length;

    public function __construct(
        int $length = 0
    ) {
        if ($length === 0) {
            $length = PHP_INT_MAX;
        }

        $this->length = $length;
    }

    public function read(ByteStream $stream): string
    {
        return $stream->read($this->length);
    }
}