<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers;

use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Events\ResultModifierSectionsEvent;
use Prokl\BitrixOrdinaryToolsBundle\Services\Facades\TimestampFacade;
use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Interfaces\BitrixComponentEventHandlerInterface;

/**
 * Class onResultModifierSections
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers
 */
class OnResultModifierSections implements BitrixComponentEventHandlerInterface
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
        return 100;
    }

    /**
     * Слушатель события on.result.modifier.component. Вычисление последнего измененного элемента.
     *
     * @param ResultModifierSectionsEvent $event Объект данных события.
     *
     * @return mixed
     */
    public function action($event): void
    {
        $arResult = $event->arResult();
        // ITEMS или SECTIONS
        $arData = (array)$this->getData($arResult);

        $arResult['LAST_MODIFIED'] = '';
        if (count($arData) > 0) {
            $arResult['LAST_MODIFIED'] = TimestampFacade::setTimestamps($arData)->getNewestTimestamp();
        }

        $event->setResult($arResult);
    }

    /**
     * ITEMS или SECTIONS.
     *
     * @param array $arResult
     *
     * @return array
     */
    private function getData(array $arResult) : array
    {
        $result = [];

        if (array_key_exists('ITEMS', $arResult) && count($arResult['ITEMS']) > 0) {
            $result = $arResult['ITEMS'];
        }

        if (array_key_exists('SECTIONS', $arResult) && count($arResult['SECTIONS']) > 0) {
            $result = $arResult['SECTIONS'];
        }

        return $result;
    }
}
