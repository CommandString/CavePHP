<?php

function hexToAnsiColor($hexColor, $isBackground = false) {
    $ansiCode = $isBackground ? 48 : 38;
    $hexColor = ltrim($hexColor, '#'); // Remove '#' if present
    list($r, $g, $b) = sscanf($hexColor, "%02x%02x%02x");
    return "\033[{$ansiCode};2;{$r};{$g};{$b}m";
}

function color(string $color, string $content): string
{
    return hexToAnsiColor($color) . $content . "\033[0m"; // Reset
}

function bold(string $content): string
{
    return "\033[1m{$content}\033[0m";
}
