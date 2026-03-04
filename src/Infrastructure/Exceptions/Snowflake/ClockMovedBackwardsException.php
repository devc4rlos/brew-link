<?php

declare(strict_types=1);

namespace BrewLink\Infrastructure\Exceptions\Snowflake;

use BrewLink\Domain\Exceptions\IdGenerationException;
use RuntimeException;

final class ClockMovedBackwardsException extends RuntimeException implements IdGenerationException
{
    public function __construct()
    {
        parent::__construct('System clock moved backwards.');
    }
}
