<?php

declare(strict_types=1);

namespace BrewLink\Infrastructure\Exceptions\ShortCodeGenerator;

use BrewLink\Domain\Exceptions\ShortCodeGeneratorException;
use InvalidArgumentException;

final class InvalidBaseException extends InvalidArgumentException implements ShortCodeGeneratorException
{
    public function __construct()
    {
        parent::__construct('The alphabet must be at least 2 characters long.');
    }
}
