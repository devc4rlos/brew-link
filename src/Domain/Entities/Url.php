<?php

declare(strict_types=1);

namespace BrewLink\Domain\Entities;

use BrewLink\Domain\Exceptions\Url\InvalidOriginalUrlException;
use BrewLink\Domain\ValueObjects\UrlCode;
use DateTimeImmutable;

final readonly class Url
{
    public function __construct(
        private string $originalUrl,
        private UrlCode $code,
        private DateTimeImmutable $createdAt,
    ) {
        $this->validateUrl($originalUrl);
    }

    public function getOriginalUrl(): string
    {
        return $this->originalUrl;
    }

    public function getCode(): UrlCode
    {
        return $this->code;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    private function validateUrl(string $url): void
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidOriginalUrlException($url);
        }
    }
}
