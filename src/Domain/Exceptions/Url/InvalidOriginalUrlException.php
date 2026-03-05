<?php

declare(strict_types=1);

namespace BrewLink\Domain\Exceptions\Url;

use BrewLink\Domain\Exceptions\Url;
use InvalidArgumentException;

final class InvalidOriginalUrlException extends InvalidArgumentException implements Url
{
    public function __construct(string $value)
    {
        parent::__construct(sprintf('The URL "%s" is invalid.', $value));
    }
}
