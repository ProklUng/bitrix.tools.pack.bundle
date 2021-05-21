<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class TimestampFacade
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 *
 * @method static getNewestTimestamp() : string
 * @method static setTimestamps(array $arResultItems): self
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
