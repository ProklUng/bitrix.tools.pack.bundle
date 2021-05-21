<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers\Seo;

use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Events\ResultModifierSectionsEvent;
use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Interfaces\BitrixComponentEventHandlerInterface;

/**
 * Class H1
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers\Seo
 */
class H1 implements BitrixComponentEventHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function event() : string
    {
        return 'on.result.modifier.component';
    }

    /**
     * @inheritDoc
     */
    public function priority() : int
    {
        return 250;
    }

    /**
     * Калькуляция H1.
     *
     * @param ResultModifierSectionsEvent $event Объект события.
     *
     * @return void
     */
    public function action($event): void
    {
        $h1 = (string)$event->arParams('H1_PROPERTY') ?: (string)$event->arResult('NAME');

        $event->setResult((array)$event->arResult(), 'H1', $h1);
    }

}
