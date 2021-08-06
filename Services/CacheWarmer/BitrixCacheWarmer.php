<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\CacheWarmer;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class BitrixCacheWarmer
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\CacheWarmer
 *
 * @since 06.08.2021
 */
class BitrixCacheWarmer implements CacheWarmerInterface
{
    /**
     * @var HttpClientInterface $httpClient Клиент.
     */
    private $httpClient;

    /**
     * @var string $httpHost Адрес сайта со схемой.
     */
    private $httpHost;

    /**
     * @var array $urls URL для прогрева.
     */
    private $urls;

    /**
     * BitrixCacheWarmer constructor.
     *
     * @param HttpClientInterface $httpClient Клиент.
     * @param string              $httpHost   Адрес сайта со схемой.
     * @param array               $urls       URL-ы для прогрева.
     */
    public function __construct(
        HttpClientInterface $httpClient,
        string $httpHost,
        array $urls = []
    ) {
        $this->httpClient = $httpClient;
        $this->httpHost = $httpHost;
        $this->urls = $urls;
    }

    /**
     * @inheritDoc
     */
    public function isOptional()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function warmUp($cacheDir)
    {
        foreach ($this->urls as $url) {
            $this->loadUri($url);
        }

        return [];
    }

    /**
     * @param string $uri URL к загрузке.
     *
     * @return void
     */
    private function loadUri(string $uri) : void
    {
        try {
            $this->httpClient->request('GET', $this->httpHost . $uri);
        } catch (TransportExceptionInterface $e) {
            // Silence gold
        }
    }
}
