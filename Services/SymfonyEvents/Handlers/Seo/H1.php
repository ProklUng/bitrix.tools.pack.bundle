<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers\Seo;

use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Events\ResultModifierSectionsEvent;

/**
 * Class H1
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers\Seo
 */
class H1
{
    /**
     * Калькуляция H1.
     *
     * @param ResultModifierSectionsEvent $event Объект события.
     *
     * @return void
     */
    public function action(ResultModifierSectionsEvent $event): void
    {
        $h1 = $event->arParams('H1_PROPERTY') ?: $event->arResult('NAME');

        $event->setResult($event->arResult(), 'H1', $h1);
    }

}
