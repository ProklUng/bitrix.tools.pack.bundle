<?php

namespace Prokl\BitrixOrdinaryToolsBundle;

use Prokl\BitrixOrdinaryToolsBundle\DependencyInjection\BitrixOrdinaryToolsExtension;
use Prokl\BitrixOrdinaryToolsBundle\DependencyInjection\CompilerPass\WarmersConfiguratorCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class BitrixOrdinaryToolsBundle
 * @package Prokl\BitrixOrdinaryToolsBundle
 *
 * @since 19.05.2021
 */
final class BitrixOrdinaryToolsBundle extends Bundle
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

    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container) : void
    {
        parent::build($container);

        $container->addCompilerPass(new WarmersConfiguratorCompilerPass());
    }
}
