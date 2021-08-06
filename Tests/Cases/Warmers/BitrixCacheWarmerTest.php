<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Tests\Cases\Warmers;

use Mockery;
use Prokl\BitrixOrdinaryToolsBundle\Services\CacheWarmer\BitrixCacheWarmer;
use Prokl\TestingTools\Base\BaseTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class BitrixCacheWarmerTest
 * @package Prokl\BitrixOrdinaryToolsBundle\Tests\Cases\Warmers
 *
 * @since 06.08.2021
 */
class BitrixCacheWarmerTest extends BaseTestCase
{
    /**
     * @var BitrixCacheWarmer $obTestObject
     */
    protected $obTestObject;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $testUrls = ['/', '/test/page'];

        $this->obTestObject = new BitrixCacheWarmer(
            $this->getMockHttpClient(count($testUrls)),
            'localhost',
            $testUrls
        );
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
     * Мок HttpClientInterface.
     *
     * @param integer $count
     *
     * @return mixed
     */
    private function getMockHttpClient(int $count)
    {
        $mock = Mockery::mock(HttpClientInterface::class);

        $mock = $mock->shouldReceive('request')->times($count);

        return $mock->getMock();
    }
}
