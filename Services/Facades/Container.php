<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class Container
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 *
 * @method static void set(string $id, ?object $service)
 * @method static object|null get($id, int $invalidBehavior)
 * @method static bool has($id)
 * @method static array|bool|float|int|string|null getParameter(string $name)
 * @method static bool hasParameter(string $name)
 * @method static void setParameter(string $name, $value)
 */
class Container extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor() : string
    {
        return 'service_container';
    }
}
