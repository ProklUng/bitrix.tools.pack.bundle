<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Twig;

use Twig\Error\LoaderError;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Class TwigService
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Twig
 *
 * @since 07.09.2020
 * @since 12.10.2020 Доработка. Расширение функционала.
 */
class TwigService
{
    /**
     * @var Twig_Environment Twig.
     */
    private $twigEnvironment;

    /**
     * @var Twig_Loader_Filesystem $loader Загрузчик Twig.
     */
    private $loader;

    /**
     * @var array $twigOptions Опции Twig.
     */
    private $twigOptions;

    /**
     * @var string $debug
     */
    private $debug;

    /**
     * @var string $cachePath
     */
    private $cachePath;

    /**
     * TwigService constructor.
     *
     * @param Twig_Loader_Filesystem $loader      Загрузчик.
     * @param string                 $debug       Среда.
     * @param string                 $cachePath   Путь к кэшу (серверный).
     * @param array|null             $twigOptions Опции Твига.
     */
    public function __construct(
        Twig_Loader_Filesystem $loader,
        string $debug,
        string $cachePath,
        array $twigOptions = null
    ) {
        $this->loader = $loader;
        $this->twigOptions = $twigOptions;
        $this->debug = $debug;
        $this->cachePath = $cachePath;

        $this->twigEnvironment = $this->initTwig(
            $loader,
            $debug,
            $cachePath
        );
    }

    /**
     * Инстанс Твига.
     *
     * @return Twig_Environment
     */
    public function instance() : Twig_Environment
    {
        return $this->twigEnvironment;
    }

    /**
     * Пути к базовой директории шаблонов из конфига контейнера.
     *
     * @return array
     *
     * @since 06.11.2020
     */
    public function getPaths() : array
    {
        return $this->twigOptions['paths'] ?? [];
    }

    /**
     * Еще один базовый путь к шаблонам Twig.
     *
     * @param string $path Путь.
     *
     * @return void
     * @throws LoaderError Ошибки Twig.
     */
    public function addPath(string $path) : void
    {
        $this->loader->addPath($path);

        // Переинициализировать.
        $this->twigEnvironment = $this->initTwig(
            $this->loader,
            $this->debug,
            $this->cachePath
        );
    }

    /**
     * Инициализация.
     *
     * @param Twig_Loader_Filesystem $loader    Загрузчик.
     * @param string                 $debug     Среда.
     * @param string                 $cachePath Путь к кэшу (серверный).
     *
     * @return Twig_Environment
     */
    protected function initTwig(
        Twig_Loader_Filesystem $loader,
        string $debug,
        string $cachePath
    ) : Twig_Environment {

        return new Twig_Environment(
            $loader,
            [
                'debug' => (bool)$debug,
                'cache' => $cachePath,
            ]
        );
    }
}
