<?php

declare(strict_types=1);

namespace BrewLink\Infrastructure\Services;

use BrewLink\Domain\Alphabet;
use BrewLink\Domain\Contracts\CodeGenerator;
use BrewLink\Infrastructure\Exceptions\ShortCodeGenerator\InvalidBaseException;
use InvalidArgumentException;

readonly class ShortCodeGenerator implements CodeGenerator
{
    /** @var array<int, string> */
    private array $alphabetArray;

    private int $base;

    public function __construct(?string $alphabet = null)
    {
        $alphabet = $alphabet ?? Alphabet::BASE62->value;

        $this->alphabetArray = mb_str_split($alphabet, 1, 'UTF-8');
        $this->base = count($this->alphabetArray);

        if ($this->base < 2) {
            throw new InvalidBaseException;
        }
    }

    public function encode(int $number): string
    {
        if ($number < 0) {
            throw new InvalidArgumentException('The number must be greater than or equal to 0.');
        }

        if ($number === 0) {
            return $this->alphabetArray[0];
        }

        $code = '';
        while ($number > 0) {
            $remainder = $number % $this->base;

            $code = $this->alphabetArray[$remainder].$code;

            $number = (int) ($number / $this->base);
        }

        return $code;
    }
}
