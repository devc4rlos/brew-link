<?php

declare(strict_types=1);

namespace BrewLink\Domain\ValueObjects;

use BrewLink\Domain\Enums\Alphabet;
use BrewLink\Domain\Exceptions\Url\CodeUrlOutsideAlphabetAllowedException;
use InvalidArgumentException;

readonly class UrlCode
{
    public function __construct(
        private string $value,
        ?string $alphabet = null,
    ) {
        $alphabet = $alphabet ?? Alphabet::BASE62->value;

        $this->validate($value, $alphabet);
    }

    private function validate(string $value, string $alphabet): void
    {
        if ($value === '') {
            throw new InvalidArgumentException('The code cannot be empty.');
        }

        $regex = sprintf('/^[%s]+$/u', preg_quote($alphabet, '/'));

        if (! preg_match($regex, $value)) {
            throw new CodeUrlOutsideAlphabetAllowedException($value);
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(UrlCode $other): bool
    {
        return $this->value === $other->getValue();
    }
}
