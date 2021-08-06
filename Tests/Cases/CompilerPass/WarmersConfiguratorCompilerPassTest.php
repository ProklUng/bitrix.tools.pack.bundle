<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Tests\Cases\CompilerPass;

use Prokl\BitrixOrdinaryToolsBundle\DependencyInjection\CompilerPass\WarmersConfiguratorCompilerPass;
use Prokl\TestingTools\Base\BaseTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class WarmersConfiguratorCompilerPassTest
 * @package Prokl\BitrixOrdinaryToolsBundle\Tests\Cases\CompilerPass
 *
 * @since 06.08.2021
 */
class WarmersConfiguratorCompilerPassTest extends BaseTestCase
{
    /**
     * @var WarmersConfiguratorCompilerPass $obTestObject
     */
    protected $obTestObject;

    /**
     * @var object $fakeService
     */
    private $fakeService;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->fakeService = new class {
            public function handle()
            {
            }
        };

        $this->container = new ContainerBuilder();

        $this->obTestObject = new WarmersConfiguratorCompilerPass();
    }

    /**
     * Нет http клиента.
     *
     * @return void
     */
    public function testNoHttpClient() : void
    {
        $this->container->setDefinition(
            'bitrix_ordinary_tools.bitrix_page_cache_warmer',
            new Definition(get_class($this->fakeService))
        );

        $this->obTestObject->process($this->container);

        $this->assertFalse($this->container->hasDefinition('bitrix_ordinary_tools.bitrix_page_cache_warmer'));
    }

    /**
     * Не задан параметр warming_pages.
     *
     * @return void
     */
    public function testNoPageParameters() : void
    {
        $this->container->setDefinition(
            'http_client',
            new Definition(get_class($this->fakeService))
        );

        $definition = new Definition(get_class($this->fakeService));
        $definition->addArgument($this->container->getDefinition('http_client'));
        $definition->addArgument('localhost');
        $definition->addArgument(['/xxx/']);

        $this->container->setDefinition(
            'bitrix_ordinary_tools.bitrix_page_cache_warmer',
            $definition
        );

        $this->obTestObject->process($this->container);

        $newDefinition = $this->container->getDefinition('bitrix_ordinary_tools.bitrix_page_cache_warmer');
        $newParam = $newDefinition->getArguments();

        $this->assertSame(['/xxx/'], $newParam[2]);
    }

    /**
     * Задан параметр warming_pages.
     *
     * @return void
     */
    public function testHasPageParameters() : void
    {
        $this->container->setDefinition(
            'http_client',
            new Definition(get_class($this->fakeService))
        );

        $this->container->setParameter(
            'warming_pages',
            ['/page/']
        );

        $definition = new Definition(get_class($this->fakeService));
        $definition->addArgument($this->container->getDefinition('http_client'));
        $definition->addArgument('localhost');
        $definition->addArgument(['/xxx/']);

        $this->container->setDefinition(
            'bitrix_ordinary_tools.bitrix_page_cache_warmer',
            $definition
        );

        $this->obTestObject->process($this->container);

        $newDefinition = $this->container->getDefinition('bitrix_ordinary_tools.bitrix_page_cache_warmer');
        $newParam = $newDefinition->getArguments();

        $this->assertSame(['/page/'], $newParam[2]);
    }

    /**
     * Нет router.
     *
     * @return void
     */
    public function testNoRouter() : void
    {
        $this->container->setDefinition(
            'bitrix_ordinary_tools.router_cache_warmer',
            new Definition(get_class($this->fakeService))
        );

        $this->obTestObject->process($this->container);

        $this->assertFalse($this->container->hasDefinition('bitrix_ordinary_tools.router_cache_warmer'));
    }

    /**
     * Нет router.
     *
     * @return void
     */
    public function testHasRouter() : void
    {
        $this->container->setDefinition(
            'bitrix_ordinary_tools.router_cache_warmer',
            new Definition(get_class($this->fakeService))
        );

        $this->container->setDefinition(
            'router',
            new Definition(get_class($this->fakeService))
        );

        $this->obTestObject->process($this->container);

        $this->assertTrue($this->container->hasDefinition('bitrix_ordinary_tools.router_cache_warmer'));
    }
}
