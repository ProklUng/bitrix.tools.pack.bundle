<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class CacherFacade
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 *
 * @method static cacheFacade(string $cacheId, ?callable $callback, array $arCallbackParams = [], int $timeSeconds = 86400,string $currentUrl = '')
 * @method static returnResultCache()
 * @method static setCacheId(string $cacheId): LegacyCacher
 * @method static setCallback(callable $callback): LegacyCacher
 * @method static setCallbackParams(): LegacyCacher
 * @method static setBaseDir(string $baseDir): LegacyCacher
 * @method static setTTL(int $timeSeconds): LegacyCacher
 * @method static setCurrentUrl(string $currentUrl): LegacyCacher
 */
class CacherFacade extends AbstractFacade
{
    /**
     * Сервис фасада.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'bitrix_ordinary_tools.cacher';
    }
}
