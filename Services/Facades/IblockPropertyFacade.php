<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class IblockPropertyFacade
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 */
class IblockPropertyFacade extends AbstractFacade
{
    /**
     * Сервис фасада.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'iblock.property.manager';
    }
}
