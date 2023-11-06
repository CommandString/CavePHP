<?php

namespace CavePHP\Server\Packets\McVars;

use Attribute;
use CavePHP\Exceptions\CavePHPException;
use CavePHP\Server\Packets\ByteStream;

#[Attribute(Attribute::TARGET_PROPERTY)]
class McVarInt implements McVar
{
    public const SEGMENT_BITS = 0x7F;
    public const CONTINUE_BIT = 0x80;

    public function read(ByteStream $stream): int
    {
        $value = 0;
        $shift = 0;

        do {
            $byte = $stream->readByte();
            $value |= ($byte & self::SEGMENT_BITS) << $shift;
            $shift += 7;

            if ($shift >= 32) {
                throw new CavePHPException('VarInt is too big');
            }
        } while ($byte & self::CONTINUE_BIT);

        return $value;
    }
}
