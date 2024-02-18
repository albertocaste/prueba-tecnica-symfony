<?php

declare(strict_types=1);

namespace App\Context\Site\Infrastructure\Controllers;

use Illuminate\Http\Request;
use App\Context\Site\Application\CreateShortUrlUseCase;
use App\Context\Site\Domain\Site;
use App\Context\Site\Infrastructure\Repositories\TinyUrlApiRepository;

final class CreateShortUrlController
{
    private $repository;

    public function __construct(TinyUrlApiRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get Request and instance use case
     *
     * @param Request $request
     * @return Site
     */
    public function __invoke(string $url): Site
    {
        $createShortUrlUseCase = new CreateShortUrlUseCase($this->repository);
        return $createShortUrlUseCase->__invoke($url);
    }
}
