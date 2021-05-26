<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class Application
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 *
 */
class ApplicationD7 extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor() : string
    {
        return 'bitrix_ordinary_tools.application_d7';
    }
}
