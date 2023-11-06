<?php

namespace Tnapf\Package\Test;

use CavePHP\Server\Logger\Logger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    protected readonly Logger $logger;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->logger = new Logger();
    }

    public function obCapture(callable $callback): string
    {
        ob_start();
        $callback();

        return trim(ob_get_clean());
    }

    public function testItFormats()
    {
        $output = $this->obCapture(function () {
            $this->logger->debug('{message}');
        });

        $this->assertIsInt(
            preg_match_all('/(\[(.*)\] \[\d{4}-\d{2}-\d{2} \d{2}\:\d{2}\:\d{2}] (.*))/m', $output, $matches, PREG_SET_ORDER, 0),
            "Logger did not log correctly. Received output: {$output}"
        );
    }
}
