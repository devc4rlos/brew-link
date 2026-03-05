<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entities;

use BrewLink\Domain\Entities\Url;
use BrewLink\Domain\Exceptions\Url\InvalidOriginalUrlException;
use BrewLink\Domain\ValueObjects\UrlCode;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class UrlTest extends TestCase
{
    public function test_getters_return_correct_values(): void
    {
        $code = new UrlCode('xyz789');
        $createdAt = new DateTimeImmutable;

        $url = new Url(
            originalUrl: 'https://google.com',
            code: $code,
            createdAt: $createdAt,
        );

        $this->assertSame('https://google.com', $url->getOriginalUrl());
        $this->assertSame('xyz789', $url->getCode()->getValue());
        $this->assertSame($createdAt, $url->getCreatedAt());
    }

    public function test_throws_exception_when_url_is_invalid(): void
    {
        $this->expectException(InvalidOriginalUrlException::class);
        $this->expectExceptionMessage('The URL');

        new Url(
            originalUrl: 'invalid-url',
            code: new UrlCode('abc123'),
            createdAt: new DateTimeImmutable,
        );
    }
}
