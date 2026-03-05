<?php

declare(strict_types=1);

namespace BrewLink\Domain\Exceptions\UrlCode;

use BrewLink\Domain\Exceptions\UrlCode;
use InvalidArgumentException;

final class CodeOutsideAlphabetAllowedException extends InvalidArgumentException implements UrlCode
{
    public function __construct(string $value)
    {
        parent::__construct(sprintf('The code "%s" contains characters outside the allowed alphabet.', $value));
    }
}
