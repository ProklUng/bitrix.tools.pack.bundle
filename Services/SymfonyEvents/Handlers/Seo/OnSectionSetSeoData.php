<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers\Seo;

use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Events\ComponentEpilogEvent;
use Prokl\BitrixOrdinaryToolsBundle\Services\Facades\IblockFacade;
use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Interfaces\BitrixComponentEventHandlerInterface;

/**
 * Class OnSectionSetSeoData
 * @package Tests\App\Events\Samples\Handlers\Seo
 */
class OnSectionSetSeoData implements BitrixComponentEventHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function event() : string
    {
        return 'on.component.epilog.sections';
    }

    /**
     * @inheritDoc
     */
    public function priority() : int
    {
        return 200;
    }

    /**
     * Слушатель события set.seo.section.page. Установка title & description.
     *
     * @param ComponentEpilogEvent $event Объект события.
     *
     * @return void
     */
    public function action($event): void
    {
        if (!$event->arResult()) {
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
        $arParams = (array)$event->arParams();

        if ($GLOBALS['APPLICATION']->GetPageProperty('title')) {
            return (string)$GLOBALS['APPLICATION']->GetPageProperty('title');
        }

        /** @var string $title */
        $title = IblockFacade::getNameByIdCached($arParams['IBLOCK_ID']);

        return $title ?: '';
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
        $arParams = (array)$event->arParams();

        if ($GLOBALS['APPLICATION']->GetPageProperty('description')) {
            return (string)$GLOBALS['APPLICATION']->GetPageProperty('description');
        }

        /** @var string $description */
        $description = IblockFacade::getDescriptionByIdCached($arParams['IBLOCK_ID']);

        return !empty($description) ? $description :  '';
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
        if (!empty($title)) {
            $GLOBALS['APPLICATION']->SetPageProperty('title', $title);
        }

        if (!empty($description)) {
            $GLOBALS['APPLICATION']->SetPageProperty('description', $description);
        }
    }
}
