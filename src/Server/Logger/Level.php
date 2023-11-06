<?php

namespace CavePHP\Server\Logger;

enum Level: string
{
    case ERROR = Color::RED_INVERTED;
    case WARNING = Color::YELLOW_INVERTED;
    case INFO = Color::BLUE_INVERTED;
    case DEBUG = Color::BLACK;
}
