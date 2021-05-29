<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Cache;

/**
 * Class LegacyCacheManager
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Cache
 *
 * @deprecated
 */
class LegacyCacheManager
{
    /**
     * @param int $timeSeconds Время кэширования.
     * @param string $cacheId  Ключ кэша.
     * @param $callback
     * @param array $arCallbackParams Параметры callback функции
     *
     * @return mixed
     */
    public static function returnResultCache($cacheId, $callback, $arCallbackParams = array(), $timeSeconds = 86400)
    {
        $result = null;

        $cacher = new \CPHPCache();
        $cachePath = '/' . SITE_ID . '/' . $cacheId;
        if ($cacher->InitCache($timeSeconds, $cacheId, $cachePath)) {
            $vars = $cacher->GetVars();
            $result = $vars['result'];
        } elseif ($cacher->StartDataCache()) {
            $result = $callback($arCallbackParams);
            $cacher->EndDataCache(['result' => $result]);
        }
        return $result;

    }

}
