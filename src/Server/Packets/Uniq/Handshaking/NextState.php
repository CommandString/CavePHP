<?php

namespace CavePHP\Server\Packets\Uniq\Handshaking;

enum NextState: int
{
    case STATUS = 1;
    case LOGIN = 2;
}
