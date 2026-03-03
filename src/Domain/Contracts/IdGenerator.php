<?php

declare(strict_types=1);

namespace BrewLink\Domain\Contracts;

interface IdGenerator
{
    public function generate(): int;
}
