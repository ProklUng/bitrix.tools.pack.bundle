<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class EventMail
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 *
 */
class EventMail extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor() : string
    {
        return 'Bitrix\Main\Mail\Event';
    }
}
