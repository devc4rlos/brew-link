<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use BrewLink\Domain\Exceptions\Url\CodeUrlOutsideAlphabetAllowedException;
use BrewLink\Domain\ValueObjects\UrlCode;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class UrlCodeTest extends TestCase
{
    public function test_can_create_valid_url_code_with_default_alphabet(): void
    {
        $code = new UrlCode('abc123XYZ');

        $this->assertSame('abc123XYZ', $code->getValue());
    }

    public function test_can_create_valid_url_code_with_custom_alphabet(): void
    {
        $alphabet = '01';

        $code = new UrlCode('10101', $alphabet);

        $this->assertSame('10101', $code->getValue());
    }

    public function test_throws_exception_when_value_is_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The code cannot be empty.');

        new UrlCode('');
    }

    public function test_throws_exception_when_value_contains_invalid_characters(): void
    {
        $this->expectException(CodeUrlOutsideAlphabetAllowedException::class);
        $this->expectExceptionMessage('contains characters outside the allowed alphabet.');

        new UrlCode('abc@123');
    }

    public function test_equals_returns_true_for_same_value(): void
    {
        $code1 = new UrlCode('abc123');
        $code2 = new UrlCode('abc123');

        $this->assertTrue($code1->equals($code2));
    }

    public function test_equals_returns_false_for_different_values(): void
    {
        $code1 = new UrlCode('abc123');
        $code2 = new UrlCode('xyz789');

        $this->assertFalse($code1->equals($code2));
    }

    public function test_invalid_character_with_custom_alphabet(): void
    {
        $alphabet = 'ABC';

        $this->expectException(CodeUrlOutsideAlphabetAllowedException::class);

        new UrlCode('ABD', $alphabet);
    }

    public function test_can_create_url_code_with_utf8_alphabet(): void
    {
        $alphabet = '😀😁😂😎';

        $code = new UrlCode('😀😎😁', $alphabet);

        $this->assertSame('😀😎😁', $code->getValue());
    }

    public function test_throws_exception_when_utf8_value_contains_invalid_character(): void
    {
        $alphabet = '😀😁😂😎';

        $this->expectException(CodeUrlOutsideAlphabetAllowedException::class);
        $this->expectExceptionMessage('contains characters outside the allowed alphabet.');

        new UrlCode('😀🤖', $alphabet);
    }

    public function test_equals_works_with_utf8_values(): void
    {
        $alphabet = '😀😁😂😎';

        $code1 = new UrlCode('😀😁', $alphabet);
        $code2 = new UrlCode('😀😁', $alphabet);
        $code3 = new UrlCode('😁😀', $alphabet);

        $this->assertTrue($code1->equals($code2));
        $this->assertFalse($code1->equals($code3));
    }
}
