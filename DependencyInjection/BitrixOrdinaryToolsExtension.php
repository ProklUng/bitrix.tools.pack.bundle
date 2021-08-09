<?php

namespace Prokl\BitrixOrdinaryToolsBundle\DependencyInjection;

use Exception;
use LogicException;
use Maximaster\Tools\Twig\TemplateEngine;
use Prokl\BitrixOrdinaryToolsBundle\Services\Twig\TwigExtensionsBag;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;

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
        $loader->load('warmers.yaml');

        $loader->load('notifier.yaml');
        if (class_exists(NotifierInterface::class)) {
            $loader->load('notifier_transports.yaml');
        }

        // Битриксовый транспорт для Messenger подключается только
        // если Messenger в наличии.
        if (class_exists(Envelope::class)) {
            $loader->load('bitrix_transport.yaml');
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

        // Не установлен tools.twig - удалить лишнее.
        if (!class_exists(TemplateEngine::class)) {
            $container->removeDefinition(TwigExtensionsBag::class);
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
