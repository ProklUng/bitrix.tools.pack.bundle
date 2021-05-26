<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Cache;

use CPHPCache;
use Exception;

/**
 * Class LegacyCacher
 * Кэширование.
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Cache
 *
 * @since 22.10.2020 Доработка.
 */
final class LegacyCacher
{
    /**
     * @var CPHPCache $cacheHandler Обработчик кэша.
     */
    private $cacheHandler;

    /**
     * @var string ID кэша.
     */
    private $cacheId;

    /**
     * @var callable $callback Обработчик, он же получатель данных.
     */
    private $callback;

    /**
     * @var array $timeSeconds Параметры обработчика.
     */
    private $arCallbackParams;

    /**
     * @var integer $timeSeconds Время жизни кэша.
     */
    private $timeSeconds;

    /**
     * @var string $currentUrl Текущий URL.
     */
    private $currentUrl;

    /**
     * @var string $baseDir Базовая директория кэша (подпапка в bitrix/cache).
     */
    private $baseDir = '';

    /**
     * Cacher constructor.
     *
     * @param CPHPCache     $cacheHandler     Обработчик кэша.
     * @param string        $cacheId          Ключ кэша.
     * @param callable|null $callback         Callback функция.
     * @param array         $arCallbackParams Параметры callback функции.
     * @param integer       $timeSeconds      Время кэширования.
     * @param string        $currentUrl       Текущий URL.
     */
    public function __construct(
        CPHPCache $cacheHandler,
        string $cacheId = '',
        ?callable $callback = null,
        array $arCallbackParams = [],
        int $timeSeconds = 604800,
        string $currentUrl = ''
    ) {
        $this->cacheHandler = $cacheHandler;
        $this->currentUrl = $currentUrl;

        $callbackSalt = '';
        if ($callback !== null) {
            /** @var mixed $callbackResult */
            $callbackResult = $callback();
            if (is_array($callbackResult)) {
                $callbackResult = implode('', $callbackResult);
            }

            if (is_object($callbackResult)) {
                $callbackResult = serialize($callbackResult);
            }

            $callbackSalt = (string)$callbackResult;
        }

        // ID кэша формируется из переданного и соли от callback и параметров.
        $this->cacheId = $cacheId . md5($callbackSalt) . $this->hashCache($arCallbackParams);

        if ($callback !== null) {
            $this->callback = $callback;
        }

        $this->arCallbackParams = $arCallbackParams;

        // Отрубить кэш для окружения dev.
        $this->timeSeconds = (env('DEBUG', false) === true) ? $timeSeconds : 0 ;
    }

    /**
     * Фасад.
     *
     * @param string        $cacheId          Ключ кэша.
     * @param callable|null $callback         Callback функция.
     * @param array         $arCallbackParams Параметры callback функции.
     * @param integer       $timeSeconds      Время кэширования.
     * @param string        $currentUrl       Текущий URL.
     *
     * @return mixed
     * @throws Exception
     */
    public static function cacheFacade(
        string $cacheId,
        ?callable $callback,
        array $arCallbackParams = [],
        int $timeSeconds = 86400,
        string $currentUrl = ''
    ) {
        $instance = new static(
            new CPHPCache(),
            $cacheId,
            $callback,
            $arCallbackParams,
            $timeSeconds,
            $currentUrl
        );

        return $instance->returnResultCache();
    }

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function returnResultCache()
    {
        /** Результат. */
        $arResult = [];

        $cachePath = '/' . SITE_ID . '/' . $this->baseDir . $this->cacheId;

        if ($this->cacheHandler->InitCache($this->timeSeconds, $this->cacheId, $cachePath)) {
            /** @var array $vars */
            $vars = $this->cacheHandler->GetVars();
            $arResult = (array)$vars['result'];
        } elseif ($this->cacheHandler->StartDataCache()) {
            $callback = $this->callback;
            try {
                /** @psalm-suppress MixedAssignment */
                $arResult = $callback(...$this->arCallbackParams);
            } catch (Exception $e) {
                $this->cacheHandler->AbortDataCache();
                $exceptionClass = get_class($e);
                throw new $exceptionClass($e->getMessage());
            }

            $this->cacheHandler->EndDataCache(['result' => $arResult]);
        }

        return $arResult;
    }

    /**
     * ID кэша.
     *
     * @param string $cacheId ID кэша.
     *
     * @return LegacyCacher
     */
    public function setCacheId(string $cacheId): LegacyCacher
    {
        $this->cacheId = $cacheId;

        return $this;
    }

    /**
     * Callback.
     *
     * @param callable $callback Callback.
     *
     * @return LegacyCacher
     */
    public function setCallback(callable $callback): LegacyCacher
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Параметры callback.
     *
     * @return LegacyCacher
     */
    public function setCallbackParams(): LegacyCacher
    {
        $this->arCallbackParams = func_get_args();

        return $this;
    }

    /**
     * Базовая директория кэша.
     *
     * @param string $baseDir Базовая директория кэша.
     *
     * @return LegacyCacher
     *
     * @since 22.10.2020
     */
    public function setBaseDir(string $baseDir): LegacyCacher
    {
        $this->baseDir = $baseDir;

        return $this;
    }

    /**
     * Время кэширования.
     *
     * @param integer $timeSeconds Время в секундах.
     *
     * @return LegacyCacher
     */
    public function setTTL(int $timeSeconds): LegacyCacher
    {
        $this->timeSeconds = $timeSeconds;

        return $this;
    }

    /**
     * Задать текущий URL.
     *
     * @param string $currentUrl Текущий URL.
     *
     * @return LegacyCacher
     */
    public function setCurrentUrl(string $currentUrl): LegacyCacher
    {
        $this->currentUrl = $currentUrl;

        return $this;
    }

    /**
     * Salt кэша.
     *
     * @param array $arParams Параметры callback.
     *
     * @return string
     */
    private function hashCache(array $arParams = []) : string
    {
        return md5(serialize($arParams));
    }
}
