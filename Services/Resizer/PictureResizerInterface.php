<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Resizer;

/**
 * Class PictureResizerInterface
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Resizer
 */
interface PictureResizerInterface
{
    /**
     * Ресайзнуть картинку.
     *
     * @return string
     */
    public function resizePicture() : string;

    /**
     * Ресайз картинки по URL.
     *
     * @param string $urlImage URL картинки.
     *
     * @return string
     */
    public function resizePictureByUrl(string $urlImage) : string;

    /**
     * Установить качество выходной картинки.
     *
     * @param integer $iJpgQuality Качество JPG.
     *
     * @return self
     */
    public function setJpgQuality(int $iJpgQuality);

    /**
     * Установить качество выходной картинки.
     *
     * @param integer $idImage Битриксовое ID картинки.
     *
     * @return self
     */
    public function setImageId(int $idImage);

    /**
     * Ширина картинки.
     *
     * @param integer $width
     *
     * @return void
     */
    public function setWidth(int $width);

    /**
     * Высота картинки.
     *
     * @param integer $height
     *
     * @return void
     */
    public function setHeight(int $height);
}
