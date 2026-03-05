<?php

declare(strict_types=1);

namespace BrewLink\Domain\Exceptions\Url;

use BrewLink\Domain\Exceptions\UrlException;
use InvalidArgumentException;

final class CodeUrlOutsideAlphabetAllowedException extends InvalidArgumentException implements UrlException
{
    public function __construct(string $value)
    {
        parent::__construct(sprintf('The code "%s" contains characters outside the allowed alphabet.', $value));
    }
}
