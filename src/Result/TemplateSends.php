<?php

declare(strict_types=1);

namespace OpenEmail\Result;

final class TemplateSends implements \IteratorAggregate, \Countable
{
    public function __construct(
        public readonly array $items,
        public readonly int $total,
        public readonly int $page,
        public readonly int $pageSize,
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
