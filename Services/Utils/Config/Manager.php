<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Utils\Config;

use Bitrix\Main\Config\Configuration;

/**
 * Class Manager
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Utils
 */
class Manager implements ConfigurationContract
{
    /**
     * @var Configuration|null $bxConfig
     */
    private $bxConfig;

    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        $value = $this->getBxConfig()->get($key);

        if (!empty($value)) {
            return $value;
        }

        $keyPath = explode('.', $key);

        $root = $this->getBxConfig()->get(array_shift($keyPath));

        if (empty($root) || ! is_array($root)) {
            return '';
        }

        $current = $root;
        foreach ($keyPath as $key => $val) {
            if (empty($current[$val])) {
                return '';
            }

            $current = $current[$val];
        }

        return $current;
    }

    /**
     * getBxConfig.
     *
     * @return Configuration
     */
    protected function getBxConfig()
    {
        if (!$this->bxConfig) {
            $this->bxConfig = Configuration::getInstance();
        }

        return $this->bxConfig;
    }
}
