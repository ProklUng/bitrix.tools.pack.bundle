<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Application;

use CMain;

/**
 * Class GetApplication
 * $APPLICATION для инжекции в сервисы и контроллеры.
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Application
 *
 * @since 11.10.2020
 */
class GetApplication
{
    /**
     * $APPLICATION.
     *
     * @return CMain
     */
    public function instance() : CMain
    {
        /** @var CMain $APPLICATION */
        global $APPLICATION;

        return $APPLICATION;
    }
}