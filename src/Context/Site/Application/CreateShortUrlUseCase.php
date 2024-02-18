<?php

declare(strict_types=1);

namespace App\Context\Site\Application;

use App\Context\Site\Domain\Contracts\SiteRepositoryContract;
use App\Context\Site\Domain\Site;
use App\Context\Site\Domain\ValueObjects\SiteUrl;

final class CreateShortUrlUseCase
{
    private $repository;

    /**
     * Construct
     *
     * @param SiteRepositoryContract $repository
     */
    public function __construct(SiteRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle the incoming request and instance method
     *
     * @param string $url
     * @return Site
     */
    public function __invoke(string $url) : Site
    {
        $siteUrl = new SiteUrl($url);
        $site = new Site($siteUrl);
        return $this->repository->createShortUrl($site);
    }
}