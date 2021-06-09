<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class EventDispatcherFacade
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 *
 * @method static mixed dispatch($event, $params)
 * @method static mixed applyListeners()
 * @method static void addListener(string $eventName, $listener, int $priority = 0)
 * @method static void removeListener(string $eventName, $listener)
 */
class ComponentEventDispatcherFacade extends AbstractFacade
{
    /**
     * Сервис фасада.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'bitrix_ordinary_tools.custom_event_dispatcher';
    }
}
