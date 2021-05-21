<?php


namespace Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers\Seo;

use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Events\ResultModifierDetailEvent;
use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Interfaces\BitrixComponentEventHandlerInterface;

/**
 * Class Canonical
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers\Seo
 */
class Canonical implements BitrixComponentEventHandlerInterface
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
        return 100;
    }

    /**
     * Калькуляция Canonical.
     *
     * @param ResultModifierDetailEvent $event Объект события.
     *
     * @return void
     */
    public function action($event) : void
    {
        $arResult = $event->arResult();

        $event->setResult($event->arResult(), 'CANONICAL_URL', '');

        if (!empty($arResult['PROPERTIES']['CANONICAL_URL']['VALUE'])) {
            $event->setResult(
                $arResult,
                'CANONICAL_URL',
                $arResult['PROPERTIES']['CANONICAL_URL']['VALUE']
            );
        }
    }
}
