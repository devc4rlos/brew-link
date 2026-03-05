<?php

declare(strict_types=1);

namespace BrewLink\Domain\Contracts;

interface CodeGeneratorInterface
{
    public function encode(int $number): string;
}
