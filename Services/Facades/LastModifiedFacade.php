<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class LastModified
 * @package Prokl\BitrixOrdinaryToolsBundle\Services
 */
class LastModifiedFacade extends AbstractFacade
{
    /**
     * Сервис фасада.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'bitrix_ordinary_tools.last_modified';
    }
}
