<?php

declare(strict_types=1);

namespace App\Context\Site\Domain\ValueObjects;


final class SiteShortUrl
{
    private ?string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): ?string
    {
        return $this->value;
    }
}