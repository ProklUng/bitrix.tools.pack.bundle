<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class IblockSectionFacade
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 */
class IblockSectionFacade extends AbstractFacade
{
    /**
     * Сервис фасада.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'iblock.section.manager';
    }
}
