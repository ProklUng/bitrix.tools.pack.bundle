<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers\Seo;

use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Events\ComponentEpilogEvent;
use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Interfaces\BitrixComponentEventHandlerInterface;

/**
 * Class OnDetailSetSeoData
 * @package Tests\App\Events\Samples\Handlers\Seo
 */
class OnDetailSetSeoData implements BitrixComponentEventHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function event() : string
    {
        return 'on.component.epilog';
    }

    /**
     * @inheritDoc
     */
    public function priority() : int
    {
        return 200;
    }

    /**
     * Слушатель события set.seo.meta.tags. Установка title & description.
     *
     * @param ComponentEpilogEvent $event Объект события.
     *
     * @return void
     */
    public function action($event): void
    {
        if (empty($event->arResult())) {
            return;
        }

        $title = $this->title($event);
        $description = $this->description($event);

        $this->setSeoMetaTags($title, $description);
    }

    /**
     * Title.
     *
     * @param ComponentEpilogEvent $event
     *
     * @return string
     */
    private function title(ComponentEpilogEvent $event) : string
    {
        $arResult = (array)$event->arResult();

        if (!array_key_exists('IPROPERTY_VALUES', $arResult)
            || count((array)$arResult['IPROPERTY_VALUES']) === 0
        ) {
            return (string)$arResult['NAME'];
        }

        $title = (string)$arResult['IPROPERTY_VALUES']['ELEMENT_META_TITLE'];

        return $title ?: $arResult['NAME'];
    }

    /**
     * Description.
     *
     * @param ComponentEpilogEvent $event
     *
     * @return string
     */
    private function description(ComponentEpilogEvent $event) : string
    {
        $arResult = (array)$event->arResult();

        if (!array_key_exists('IPROPERTY_VALUES', $arResult)
            || count((array)$arResult['IPROPERTY_VALUES']) === 0
        ) {
            return '';
        }

        $description = (string)$arResult['IPROPERTY_VALUES']['SECTION_META_DESCRIPTION'];

        return $description ?: '';
    }

    /**
     * Установить мета тэги.
     *
     * @param string $title
     * @param string $description
     *
     * @return void
     */
    private function setSeoMetaTags(string $title, string $description) : void
    {
        if ($title) {
            $GLOBALS['APPLICATION']->SetPageProperty('title', $title);
        }

        if ($description) {
            $GLOBALS['APPLICATION']->SetPageProperty('description', $description);
        }
    }
}
