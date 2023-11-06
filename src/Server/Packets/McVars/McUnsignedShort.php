<?php

namespace CavePHP\Server\Packets\McVars;

use Attribute;
use CavePHP\Server\Packets\ByteStream;

#[Attribute(Attribute::TARGET_PROPERTY)]
class McUnsignedShort implements McVar
{
    public function read(ByteStream $stream): mixed
    {
        return $stream->readUnsignedShort();
    }
}