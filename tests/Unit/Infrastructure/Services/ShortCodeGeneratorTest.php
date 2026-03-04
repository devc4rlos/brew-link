<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Services;

use BrewLink\Infrastructure\Exceptions\ShortCodeGenerator\InvalidBaseException;
use BrewLink\Infrastructure\Services\ShortCodeGenerator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ShortCodeGeneratorTest extends TestCase
{
    public function test_encode_zero_returns_first_character_of_default_alphabet(): void
    {
        $generator = new ShortCodeGenerator;

        $result = $generator->encode(0);

        $this->assertSame('a', $result);
    }

    public function test_encode_single_digit_number_with_default_alphabet(): void
    {
        $generator = new ShortCodeGenerator;

        $result = $generator->encode(1);

        $this->assertSame('b', $result);
    }

    public function test_encode_multiple_digits_with_default_alphabet(): void
    {
        $generator = new ShortCodeGenerator;

        $result = $generator->encode(62);

        $this->assertSame('ba', $result);
    }

    public function test_encode_with_custom_alphabet(): void
    {
        $generator = new ShortCodeGenerator('01');

        $result = $generator->encode(5);

        $this->assertSame('101', $result);
    }

    public function test_encode_with_custom_alphabet_base_16(): void
    {
        $generator = new ShortCodeGenerator('0123456789ABCDEF');

        $result = $generator->encode(255);

        $this->assertSame('FF', $result);
    }

    public function test_throws_exception_when_alphabet_has_less_than_two_characters(): void
    {
        $this->expectException(InvalidBaseException::class);
        $this->expectExceptionMessage('The alphabet must be at least 2 characters long.');

        new ShortCodeGenerator('a');
    }

    public function test_throws_exception_when_number_is_negative(): void
    {
        $generator = new ShortCodeGenerator;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The number must be greater than or equal to 0.');

        $generator->encode(-1);
    }

    public function test_encode_with_utf8_alphabet(): void
    {
        $alphabet = '😀😁😂😎';

        $generator = new ShortCodeGenerator($alphabet);

        $this->assertSame('😀', $generator->encode(0));

        $this->assertSame('😁', $generator->encode(1));

        $this->assertSame('😁😁', $generator->encode(5));
    }
}
