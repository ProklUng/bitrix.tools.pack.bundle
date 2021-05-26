<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class Application
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 *
 * @method static addBackgroundJob(callable $job, array $args = [], int $priority = 0)
 * @method static getContext()
 * @method static getCache()
 * @method static getConnection(string $name = '')
 * @method static null|string getDocumentRoot()
 * @method static getInstance()
 * @method static setContext(\Bitrix\Main\Context $context)
 */
class Application extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor() : string
    {
        return 'application.instance';
    }
}
