<?php

namespace Prokl\BitrixOrdinaryToolsBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Router;

/**
 * Class WarmersConfiguratorCompilerPass
 * @package Prokl\BitrixOrdinaryToolsBundle\DependencyInjection\CompilerPass
 *
 * @since 06.08.2021
 */
final class WarmersConfiguratorCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        // Нет роутера - нет команды по прогреву кэша роутера.
        if (!class_exists(Router::class)
            ||
            !$container->hasDefinition('router')
        ) {
            $container->removeDefinition('bitrix_ordinary_tools.router_cache_warmer');
        }

        // Нет http клиента - нет команды по прогреву кэша битриксовых страниц.
        if (!$container->hasDefinition('http_client')
            &&
            !$container->hasAlias('http_client')
        ) {
            $container->removeDefinition('bitrix_ordinary_tools.bitrix_page_cache_warmer');

            return;
        }

        // Нет в контейнере параметра warming_pages - прогрев только главной страницы.
        if ($container->hasParameter('warming_pages')) {
            $definition = $container->getDefinition('bitrix_ordinary_tools.bitrix_page_cache_warmer');
            $definition->replaceArgument(2, $container->getParameter('warming_pages'));
        }
   }
}
