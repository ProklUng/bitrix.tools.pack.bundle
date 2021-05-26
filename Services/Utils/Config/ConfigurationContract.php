<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Utils\Config;

/**
 * Interface ConfigurationContract
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Utils\Config
 */
interface ConfigurationContract
{
    /**
     * Get config value.
     *
     * @param string $key Ключ.
     *
     * @return mixed
     */
    public function get(string $key);
}
