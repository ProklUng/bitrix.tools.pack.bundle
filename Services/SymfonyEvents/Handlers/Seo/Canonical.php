<?php


namespace Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers\Seo;

use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Events\ResultModifierDetailEvent;

/**
 * Class Canonical
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers\Seo
 */
class Canonical
{
    /**
     * Калькуляция Canonical.
     *
     * @param ResultModifierDetailEvent $event Объект события.
     *
     * @return void
     */
    public function action(ResultModifierDetailEvent $event)
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
