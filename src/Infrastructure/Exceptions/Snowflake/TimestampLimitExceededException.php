<?php

declare(strict_types=1);

namespace BrewLink\Infrastructure\Exceptions\Snowflake;

use BrewLink\Domain\Exceptions\IdGenerationException;
use RuntimeException;

final class TimestampLimitExceededException extends RuntimeException implements IdGenerationException
{
    public function __construct(int $maxDelta)
    {
        parent::__construct(sprintf(
            'Time limit exceeded. The generator supports up to %d milliseconds after the EPOCH.',
            $maxDelta,
        ));
    }
}
