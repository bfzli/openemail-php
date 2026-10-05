<?php

declare(strict_types=1);

namespace OpenEmail\Result;

final class BatchResult implements \IteratorAggregate, \Countable
{
    public function __construct(
        public readonly array $items,
        public readonly ?int $sent,
        public readonly ?int $failed,
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
