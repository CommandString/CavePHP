<?php

use CommandString\Utils\FileSystemUtils;

function hexToAnsiColor($hexColor, $isBackground = false)
{
    $ansiCode = $isBackground ? 48 : 38;
    $hexColor = ltrim($hexColor, '#'); // Remove '#' if present
    [$r, $g, $b] = sscanf($hexColor, '%02x%02x%02x');

    return "\033[{$ansiCode};2;{$r};{$g};{$b}m";
}

function color(string $color, string $content): string
{
    return hexToAnsiColor($color) . $content . "\033[0m"; // Reset
}

function bold(string $content, ?string $color = null): string
{
    $bold = "\033[1m{$content}\033[0m";

    if ($color !== null) {
        $bold = color($color, $bold);
    }

    return $bold;
}

/**
 * Loop through all the classes in a directory and call a callback function with the class name
 */
function loopClasses(string $directory, callable $callback): void
{
    $convertPathToNamespace = static fn (string $path): string => '\CavePHP' . str_replace([realpath(__DIR__), '/'], ['', '\\'], $path);

    foreach (FileSystemUtils::getAllFiles($directory, true) as $file) {
        if (!str_ends_with($file, '.php')) {
            continue;
        }

        $className = basename($file, '.php');
        $path = dirname($file);
        $namespace = $convertPathToNamespace($path);
        $className = $namespace . '\\' . $className;

        $callback($className, $namespace, $file, $path);
    }
}

/**
 * @template T
 *
 * @param class-string $class
 * @param class-string<T> $attribute
 *
 * @throws ReflectionException
 *
 * @return T|false
 */
function doesClassHaveAttribute(string $class, string $attribute): object|false
{
    return (new ReflectionClass($class))->getAttributes($attribute, ReflectionAttribute::IS_INSTANCEOF)[0] ?? false;
}
