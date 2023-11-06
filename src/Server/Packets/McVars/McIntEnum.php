<?php

namespace CavePHP\Server\Packets\McVars;

use Attribute;
use BackedEnum;
use CavePHP\Server\Packets\ByteStream;
use InvalidArgumentException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class McIntEnum implements McVar
{
    public function __construct(
        /** @var class-string<BackedEnum> */
        public string $enum
    ) {
        if (!$enum instanceof BackedEnum) {
            throw new InvalidArgumentException("{$enum} must be a BackedEnum");
        }
    }

    public function read(ByteStream $stream): BackedEnum
    {
        $int = $stream->readInt();

        return $this->enum::tryFrom($int);
    }
}