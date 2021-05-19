<?php

namespace Prokl\BitrixOrdinaryToolsBundle;

use Prokl\BitrixOrdinaryToolsBundle\DependencyInjection\BitrixOrdinaryToolsExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class BitrixOrdinaryToolsBundle
 * @package Prokl\BitrixOrdinaryToolsBundle
 *
 * @since 19.05.2021
 */
class BitrixOrdinaryToolsBundle extends Bundle
{
   /**
   * @inheritDoc
   */
    public function getContainerExtension()
    {
        if ($this->extension === null) {
            $this->extension = new BitrixOrdinaryToolsExtension();
        }

        return $this->extension;
    }
}
