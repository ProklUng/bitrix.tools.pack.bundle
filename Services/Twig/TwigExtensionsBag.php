<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Twig;

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Twig\Environment;

/**
 * Class TwigExtensionsBag
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Twig
 *
 * @since 09.08.2021
 */
class TwigExtensionsBag
{
    /**
     * @var iterable|array $extensions Extensions.
     */
    private $extensions;

    /**
     * TwigExtensionsBag constructor.
     *
     * @param iterable|array $extensions Extensions.
     */
    public function __construct($extensions)
    {
        $this->extensions = $extensions;
    }

    /**
     * Обработчик события onAfterTwigTemplateEngineInited.
     *
     * @param Event $event Событие.
     *
     * @return EventResult
     */
    public function handle(Event $event) : EventResult
    {
        /** @var Environment $twig */
        $twig = $event->getParameter('engine');

        foreach ($this->extensions as $extension) {
            if (!$twig->hasExtension(is_object($extension) ? get_class($extension) : $extension)) {
                $twig->addExtension(is_object($extension) ? get_class($extension) : new $extension);
            }
        }

        return new EventResult(
            EventResult::SUCCESS,
            ['engine' => $twig]
        );
    }
}