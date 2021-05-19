<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class TimestampIblock
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 */
class TimestampIblockFacade extends AbstractFacade
{
    /**
     * Сервис фасада.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'bitrix_ordinary_tools.timestamp_iblock';
    }
}
