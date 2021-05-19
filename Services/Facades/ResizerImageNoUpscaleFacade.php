<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class ResizerImageNoUpscaleFacade
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 *
 * @method static setImageId(int $idImage) : self
 * @method static setWidth(int $width) : self
 */
class ResizerImageNoUpscaleFacade extends AbstractFacade
{
    /**
     * Сервис фасада.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'image.resizer.no.upscale';
    }
}
