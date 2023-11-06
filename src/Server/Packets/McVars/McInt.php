<?php

namespace CavePHP\Server\Packets\McVars;

use Attribute;
use CavePHP\Server\Packets\ByteStream;

#[Attribute(Attribute::TARGET_PROPERTY)]
class McInt implements McVar
{
    public function read(ByteStream $stream): int
    {
        return $stream->readInt();
    }
}