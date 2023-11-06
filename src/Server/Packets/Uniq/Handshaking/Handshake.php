<?php

namespace CavePHP\Server\Packets\Uniq\Handshaking;

use CavePHP\Server\Packets\ClientState;
use CavePHP\Server\Packets\McVars\McInt;
use CavePHP\Server\Packets\McVars\McIntEnum;
use CavePHP\Server\Packets\McVars\McString;
use CavePHP\Server\Packets\McVars\McUnsignedShort;
use CavePHP\Server\Packets\Uniq\UniqPacket;

class Handshake extends UniqPacket
{
    #[McInt]
    public int $protocolVersion;

    #[McString(255)]
    public string $serverAddress;

    #[McUnsignedShort]
    public string $serverPort;

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
