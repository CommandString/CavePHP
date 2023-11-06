<?php

namespace CavePHP\Utilities;

class EventEmitter extends \Evenement\EventEmitter
{
    public function emit($event, array $arguments = []): void
    {
        parent::emit($event, [...$arguments, $this]);
    }
}
