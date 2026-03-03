<?php

declare(strict_types=1);

namespace BrewLink\Domain\Contracts;

interface ClockInterface
{
    public function getTimestamp(): int;
}
