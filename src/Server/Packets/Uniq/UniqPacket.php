<?php

namespace CavePHP\Server\Packets\Uniq;

use CavePHP\Server\Packets\ClientState;
use CavePHP\Server\Packets\GenericPacket;

abstract class UniqPacket extends GenericPacket
{
    abstract public static function getId(): int;

    abstract public static function getState(): ClientState;
}
