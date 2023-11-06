<?php

namespace CavePHP\Server\Packets\Uniq\Configuration;

use CavePHP\Server\Packets\ClientState;
use CavePHP\Server\Packets\McVars\McIntEnum;
use CavePHP\Server\Packets\McVars\McString;
use CavePHP\Server\Packets\Uniq\Handshaking\NextState;
use CavePHP\Server\Packets\Uniq\UniqPacket;

class RegistryData extends UniqPacket
{
    #[McString]
    public string $registryCodec;

    #[McIntEnum(NextState::class)]
    public NextState $nextState;

    public static function getId(): int
    {
        return 0x00;
    }

    public static function getState(): ClientState
    {
        return ClientState::HANDSHAKING;
    }
}
