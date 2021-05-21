<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers;

use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Events\ComponentEpilogEvent;
use Prokl\BitrixOrdinaryToolsBundle\Services\Facades\LastModifiedFacade;
use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Interfaces\BitrixComponentEventHandlerInterface;

/**
 * Class LastModifiedHandler
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Handlers
 */
class OnComponentEpilogLastModified implements BitrixComponentEventHandlerInterface
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
        return 100;
    }

    /**
     * Слушатель события on.component.epilog. Установка LastModified заголовков.
     *
     * @param ComponentEpilogEvent $event.
     *
     * @return mixed
     */
    public function action($event) : void
    {
        // LastModified.
        if (empty($event->payload('timestamp'))
            &&
            empty($event->payload('timestamp_raw'))
        ) {
            return;
        }

        // Уникализация ключа в глобальном состоянии LastModifier.
        $salt = md5($event->payload('templateName') . $event->payload('componentPath'));

        $timestamp = !empty($event->payload('timestamp_raw')) ?
            $event->payload('timestamp_raw') : MakeTimeStamp($event->payload('timestamp'));

        /** @noinspection PhpUndefinedMethodInspection */
        // Установить timestamp.
        LastModifiedFacade::add(
            $salt,
            $timestamp
        );
    }
}
