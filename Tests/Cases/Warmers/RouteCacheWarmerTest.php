<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Tests\Cases\Warmers;

use Mockery;
use Prokl\BitrixOrdinaryToolsBundle\Services\CacheWarmer\RouterCacheWarm;
use Prokl\TestingTools\Base\BaseTestCase;
use Symfony\Component\Routing\Router;

/**
 * Class RouteCacheWarmerTest
 * @package Prokl\BitrixOrdinaryToolsBundle\Tests\Cases\Warmers
 *
 * @since 06.08.2021
 */
class RouteCacheWarmerTest extends BaseTestCase
{
    /**
     * @var RouterCacheWarm $obTestObject
     */
    protected $obTestObject;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->obTestObject = new RouterCacheWarm($this->getMockRouter());
    }

    /**
     * warmUp.
     *
     * @return void
     */
    public function testWarmUp() : void
    {
        $this->obTestObject->warmUp('/dir/');

        // Проверка идет на количество вызовов методов роутера.
        $this->assertTrue(true);
    }

    /**
     * Мок Router.
     *
     * @return mixed
     */
    private function getMockRouter()
    {
        $mock = Mockery::mock(Router::class);

        $mock = $mock->shouldReceive('getMatcher')->once();
        $mock = $mock->shouldReceive('getGenerator')->once();

        return $mock->getMock();
    }

}
