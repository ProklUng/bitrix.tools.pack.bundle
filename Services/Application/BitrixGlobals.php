<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Application;

use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use CMain;
use RuntimeException;

/**
 * Class BitrixGlobals
 * $APPLICATION для инжекции в сервисы и контроллеры.
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Application
 *
 * @since 11.10.2020
 */
class BitrixGlobals
{
    /**
     * $APPLICATION.
     *
     * @return CMain
     * @throws RuntimeException
     */
    public function instance() : CMain
    {
        if (empty($GLOBALS['APPLICATION'])) {
            throw new RuntimeException('Bitrix is not initialized.');
        }

        return $GLOBALS['APPLICATION'];
    }

    /**
     * Инстанц Application D7.
     *
     * @return Application
     */
    public function instanceD7() : Application
    {
        return Application::getInstance();
    }

    /**
     * EventManager.
     *
     * @return EventManager
     */
    public function eventManager() : EventManager
    {
        return EventManager::getInstance();
    }
}