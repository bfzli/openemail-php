<?php

declare(strict_types=1);

namespace OpenEmail\Result;

final class Page implements \IteratorAggregate, \Countable
{
    public function __construct(
        public readonly array $items,
        public readonly bool $hasMore,
        public readonly ?string $nextCursor,
    ) {}

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }

    public function count(): int
    {
        return \count($this->items);
    }
}
