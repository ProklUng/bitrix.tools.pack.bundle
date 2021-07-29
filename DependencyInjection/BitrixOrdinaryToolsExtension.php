<?php

namespace Prokl\BitrixOrdinaryToolsBundle\DependencyInjection;

use Exception;
use LogicException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Notifier\NotifierInterface;

/**
 * Class BitrixOrdinaryToolsExtension
 * @package Prokl\BitrixOrdinaryTools\DependencyInjection
 *
 * @since 19.05.2021
 */
class BitrixOrdinaryToolsExtension extends Extension
{
    private const DIR_CONFIG = '/../Resources/config';

    /**
     * @inheritDoc
     * @throws Exception | LogicException
     */
    public function load(array $configs, ContainerBuilder $container) : void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . self::DIR_CONFIG)
        );

        $loader->load('services.yaml');
        $loader->load('seo.yaml');
        $loader->load('image_resizer.yaml');
        $loader->load('loggers.yaml');

        $loader->load('notifier.yaml');
        if (class_exists(NotifierInterface::class)) {
            $loader->load('notifier_transports.yaml');
        }

        if (!class_exists('Prokl\CustomFrameworkExtensionsBundle\DependencyInjection\CustomFrameworkExtensionsExtension')) {
            throw new LogicException(
                'Чтобы использовать Твиг нужно установить и активировать core.framework.extension.bundle. 
              Попробуйте composer require proklung/core-framework-extension-bundle.'
            );
        }

        $loader->load('twig.yaml');

        // Фасады подтягиваются только, если установлен соответствующий бандл.
        if (class_exists('Prokl\FacadeBundle\Services\AbstractFacade')) {
            $loader->load('facades.yaml');
        }
    }

    /**
     * @inheritDoc
     */
    public function getAlias() : string
    {
        return 'bitrix-ordinary-tools';
    }
}
