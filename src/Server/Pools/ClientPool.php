<?php

namespace CavePHP\Server\Pools;

use CavePHP\Pools\Pool;
use CavePHP\Server\Client;

class ClientPool extends Pool
{
    public function isInstance(object $item): bool
    {
        return $item instanceof Client;
    }
}
