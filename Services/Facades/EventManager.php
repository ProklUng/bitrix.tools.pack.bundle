<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class EventManager
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 *
 */
class EventManager extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor() : string
    {
        return 'bitrix_ordinary_tools.event_manager';
    }
}
