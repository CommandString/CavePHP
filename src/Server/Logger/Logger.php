<?php

namespace CavePHP\Server\Logger;

use Stringable;

class Logger
{
    /** @var Level[] */
    protected array $optOut = [];

    public function log(Level $level, Stringable|string $message, array $context = []): void
    {
        if (in_array($level, $this->optOut)) {
            return;
        }

        $makeContextString = static function (array|int $item): string
        {
            if (is_array($item)) {
                return json_encode($item);
            }

            return "#{$item}";
        };

        $message = $this->formatMessage($level, $message, $context);

        if (count($context) > 0) {
            $message .= ' |';

            foreach ($context as $item) {
                $message .= " " . $makeContextString($item);
            }
        }

        echo $message . PHP_EOL;
    }

    protected function formatMessage(Level $level, string $message): string
    {
        $log = "";

        $log .= '[' .color($level->value, $level->name) . '] ';
        $log .= '[' . color(Color::BROWN, date('Y-m-d H:i:s')) . ']';
        $log .= " {$message}";

        return $log;
    }
}