<?php

declare(strict_types=1);

namespace OpenEmail\Result;

final class AddressBook implements \IteratorAggregate, \Countable
{
    public function __construct(
        public readonly ?bool $unrestricted,
        public readonly array $addresses,
        public readonly array $domains,
    ) {}

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->addresses);
    }

    public function count(): int
    {
        return \count($this->addresses);
    }
}
