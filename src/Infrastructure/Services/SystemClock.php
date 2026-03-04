<?php

declare(strict_types=1);

namespace BrewLink\Infrastructure\Services;

use BrewLink\Domain\Contracts\ClockInterface;

class SystemClock implements ClockInterface
{
    public function getTimestamp(): int
    {
        return (int) floor(microtime(true) * 1000);
    }
}
