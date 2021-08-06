<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\CacheWarmer;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\Routing\Router;

/**
 * Class RouterCacheWarm
 * @package Local\Services\Bitrix
 *
 * @since 06.08.2021
 */
class RouterCacheWarm implements CacheWarmerInterface
{
    /**
     * @var Router $router Роутер.
     */
    private $router;

    /**
     * RouterCacheWarm constructor.
     *
     * @param Router $router Роутер.
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
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
        $this->router->getMatcher();
        $this->router->getGenerator();

        return [];
    }
}