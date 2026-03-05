<?php

declare(strict_types=1);

namespace BrewLink\Domain\Contracts;

interface IdGeneratorInterface
{
    public function generate(): int;
}
