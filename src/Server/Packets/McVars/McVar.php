<?php

namespace CavePHP\Server\Packets\McVars;

use Attribute;
use CavePHP\Server\Packets\ByteStream;

#[Attribute(Attribute::TARGET_PROPERTY)]
interface McVar
{
    public function read(ByteStream $stream): mixed;
}