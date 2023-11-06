<?php

namespace CavePHP\Server\Packets;

enum ClientState: int
{
    case HANDSHAKING = 0;
    case STATUS = 1;
    case LOGIN = 2;
}
