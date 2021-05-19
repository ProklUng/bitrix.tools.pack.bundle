<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Resizer;

use Intervention\Image\Image;

/**
 * Class ResizeUpscale
 * Upscale изображения вне зависимости от его размеров.
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Resizer
 */
class ResizeUpscale extends Resize
{
    /**
     * Проверка размеров картинок.
     *
     * @param Image $obImage
     *
     * @return boolean
     */
    protected function checkSize(Image $obImage) : bool
    {
        return true;
    }
}
