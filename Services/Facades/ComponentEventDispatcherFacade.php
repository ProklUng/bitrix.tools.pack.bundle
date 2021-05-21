<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class EventDispatcherFacade
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
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
