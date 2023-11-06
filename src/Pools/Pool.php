<?php

namespace CavePHP\Pools;

use CavePHP\Exceptions\CavePHPException;
use Countable;

abstract class Pool implements Countable
{
    private array $items = [];

    abstract public function isInstance(object $item): bool;

    public function __construct(array $items = [])
    {
        $this->fill($items);
    }

    public function fill(array $items): void
    {
        foreach ($items as $item) {
            if (!is_object($item) || !$this->isInstance($item)) {
                throw new CavePHPException('This object is not supported by this collection');
            }
        }

        $this->items = [...$this->items, ...$items];
    }

    public function add(mixed $item): void
    {
        $this->fill([$item]);
    }

    public function remove(mixed $element): void
    {
        $this->items = array_filter($this->items, static fn ($item) => $item !== $element);
    }

    public function filter(callable $callback): static
    {
        $that = clone $this;

        $that->loop(static function (object $item) use ($that) {
            $that->remove($item);
        });

        return $that;
    }

    public function loop(callable $callback): void
    {
        foreach ($this->items as $item) {
            $callback($item);
        }
    }

    public function find(callable $callback): ?object
    {
        foreach ($this->items as $item) {
            if ($callback($item)) {
                return $item;
            }
        }

        return null;
    }

    public function reduce(callable $callback, mixed $initial = null): mixed
    {
        $reduced = $initial;

        $this->loop(static fn ($reduced, $item) => $callback($reduced, $item));

        return $reduced;
    }

    public function count(): int
    {
        return count($this->items);
    }
}
