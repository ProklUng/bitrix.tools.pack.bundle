<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers\Seo;

use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Events\ResultModifierDetailEvent;
use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Interfaces\BitrixComponentEventHandlerInterface;

/**
 * Class H1
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers\Seo
 */
class H1Detail implements BitrixComponentEventHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function event() : string
    {
        return 'on.result.modifier.component.detail';
    }

    /**
     * @inheritDoc
     */
    public function priority() : int
    {
        return 350;
    }

    /**
     * Калькуляция H1 для детальных страниц.
     *
     * @param ResultModifierDetailEvent $event Объект события.
     *
     * @return void
     */
    public function action($event): void
    {
        $arResult = $event->arResult();

        $h1 = !empty($arResult['PROPERTIES']['H1']['VALUE']) ?
            $arResult['PROPERTIES']['H1']['VALUE'] : $arResult['NAME'];

        $event->setResult($event->arResult(), 'H1', $h1);
    }
}
