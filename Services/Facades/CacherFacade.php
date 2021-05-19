<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class CacherFacade
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 */
class CacherFacade extends AbstractFacade
{
    /**
     * Сервис фасада.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'bitrix_ordinary_tools.cacher';
    }
}
