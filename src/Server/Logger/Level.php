<?php

namespace CavePHP\Server\Logger;

use CavePHP\Server\Logger\Color;
use Stringable;

enum Level: string
{
    case ERROR = Color::RED_INVERTED;
    case WARNING = Color::YELLOW_INVERTED;
    case INFO = Color::BLUE_INVERTED;
    case DEBUG = Color::BLACK;
}
