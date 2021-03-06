<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class ResizerImageUpscaleFacade
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 *
 * @method static setImageId(int $idImage) : self
 * @method static url(string $url = '', int $width = 0, int $height = 0) : string
 */
class ResizerImageUpscaleFacade extends AbstractFacade
{
    /**
     * Сервис фасада.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'image.resizer.upscale';
    }
}
