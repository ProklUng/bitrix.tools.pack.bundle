<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class IblockElementFacade
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 */
class IblockElementFacade extends AbstractFacade
{
    /**
     * Сервис фасада.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'iblock.element.manager';
    }
}
