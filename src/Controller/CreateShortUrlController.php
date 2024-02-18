<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Context\Site\Infrastructure\Controllers\CreateShortUrlController as HACreateShortUrlController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreateShortUrlController extends AbstractController
{
    /**
     * @var HACreateShortUrlController
     */
    private $createShortUrlController;

    /**
     * Construct
     *
     * @param HACreateShortUrlController $createShortUrlController
     */
    public function __construct(HACreateShortUrlController $createShortUrlController)
    {
        $this->createShortUrlController = $createShortUrlController;
    }

    #[Route(path: '/api/v1/short-urls', name: 'short-urls', methods: ['POST'])]
    public function __invoke(Request $request) : JsonResponse
    {
        $site = $this->createShortUrlController->__invoke($request->getPayload()->get('url'));
        return new JsonResponse([
            'url' => $site->shortUrl()->value()
        ]);
    }
}

