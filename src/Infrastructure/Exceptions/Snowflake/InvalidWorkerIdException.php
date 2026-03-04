<?php

declare(strict_types=1);

namespace BrewLink\Infrastructure\Exceptions\Snowflake;

use BrewLink\Domain\Exceptions\IdGenerationException;
use InvalidArgumentException;

final class InvalidWorkerIdException extends InvalidArgumentException implements IdGenerationException
{
    public function __construct(int $max)
    {
        parent::__construct("Machine ID must be between 0 and {$max}");
    }
}
