<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents;

use Iterator;
use Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Interfaces\BitrixComponentEventHandlerInterface;
use stdClass;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CustomEvents
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents
 */
class CustomEvents
{
    /**
     * @var BitrixComponentEventHandlerInterface[] $listenersCollection Слушатели событий.
     */
    private $listenersCollection = [];

    /**
     * @var EventDispatcherInterface $dispatcher Event dispatcher.
     */
    private $dispatcher;

    /**
     * Events constructor.
     *
     * @param EventDispatcherInterface $dispatcher          Event dispatcher.
     * @param mixed                    ...$eventsHandlerBag Сервисы, отмеченные тэгом bitrix.component.event.
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        ... $eventsHandlerBag
    ) {

        $handlers = [];

        foreach ($eventsHandlerBag as $eventHandler) {
            $iterator = $eventHandler->getIterator();
            $array = iterator_to_array($iterator);
            $handlers[] = $array;
        }


        $handlers = array_filter($handlers);

        if (count($handlers) > 0) {
            $this->listenersCollection = $handlers;
        }

        $this->dispatcher = $dispatcher;
    }

    /**
     * Применение слушателей событий.
     *
     * @return mixed
     */
    public function applyListeners()
    {
        /** @var BitrixComponentEventHandlerInterface $listener */
        foreach ($this->listenersCollection as $listener) {
            $this->dispatcher->addListener(
                $listener->event(),
                [$listener, 'action'],
                $listener->priority()
            );
        }

        return $this->dispatcher;
    }

    /**
     * Запустить событие.
     *
     * @param string $eventName Событие.
     * @param mixed  $params    Объект-параметры.
     *
     * @return mixed|object|null
     */
    public function dispatch(string $eventName, $params = null)
    {
        if (!$eventName) {
            return null;
        }

        if ($params === null) {
            $params = new stdClass();
        }

        return $this->dispatcher->dispatch($params, $eventName);
    }

    /**
     * Декоратор добавления слушателя.
     *
     * @param string  $eventName Название события.
     * @param mixed   $listener  Слушатель.
     * @param integer $priority  Приоритет.
     *
     * @return void
     */
    public function addListener(string $eventName, $listener, int $priority = 0): void
    {
        $this->dispatcher->addListener($eventName, $listener, $priority);
    }

    /**
     * Декоратор удаления слушателя события.
     *
     * @param string $eventName Название события.
     * @param mixed  $listener  Слушатель.
     *
     * @return void
     */
    public function removeListener(string $eventName, $listener) : void
    {
        $this->dispatcher->removeListener($eventName, $listener);
    }
}
