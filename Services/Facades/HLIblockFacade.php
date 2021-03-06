<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class HLIblockFacade
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 */
class HLIblockFacade extends AbstractFacade
{
    /**
     * Сервис фасада.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'hlblock.manager';
    }
}
