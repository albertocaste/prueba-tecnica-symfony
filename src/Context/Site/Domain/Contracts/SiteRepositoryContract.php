<?php

declare(strict_types=1);

namespace App\Context\Site\Domain\Contracts;

use App\Context\Site\Domain\Site;

interface SiteRepositoryContract
{
    public function check(): void;
    public function createShortUrl(Site $site): Site;
}