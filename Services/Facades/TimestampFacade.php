<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class TimestampFacade
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 */
class TimestampFacade extends AbstractFacade
{
    /**
     * Сервис фасада.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'bitrix_ordinary_tools.timestamp_news';
    }
}
