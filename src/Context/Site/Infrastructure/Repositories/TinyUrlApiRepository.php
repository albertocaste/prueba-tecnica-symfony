<?php

declare(strict_types=1);

namespace App\Context\Site\Infrastructure\Repositories;

use App\Context\Site\Domain\Contracts\SiteRepositoryContract;
use App\Context\Site\Domain\Site;
use App\Context\Site\Domain\ValueObjects\SiteShortUrl;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Config\Framework\HttpClientConfig;

final class TinyUrlApiRepository implements SiteRepositoryContract
{
    private string $BASE_URL = 'https://tinyurl.com/';
    private $client;

    /**
     * Instance client
     *
     * @param Site $site
     * @return Site
     */
    public function __construct(

    )
    {
        $this->client = HttpClient::create();
        $this->client->withOptions([
            'verify_host'=> false
        ]);
    }

    /**
     * Check tiny url API
     *
     * @return void
     */
    public function check(): void
    {
        $this->client->request('GET', $this->BASE_URL);
    }

    /**
     * Create short url by tiny url API
     *
     * @param Site $site
     * @return Site
     */
    public function createShortUrl(Site $site): Site
    {
        $response = $this->client->request('GET', $this->BASE_URL . 'api-create.php', [
            'query' => [
                'url' => $site->url()->value()
            ]
        ]);
        $siteShortUrl = new SiteShortUrl($response->getContent());
        $site->updateShortUrl($siteShortUrl);
        return $site;
    }

}