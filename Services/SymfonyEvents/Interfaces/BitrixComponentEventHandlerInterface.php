<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Interfaces;

use Psr\EventDispatcher\StoppableEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Interface BitrixComponentEventHandlerInterface
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\SymfonyEvents\Interfaces
 */
interface BitrixComponentEventHandlerInterface
{
    /**
     * Название события.
     *
     * @return string
     */
    public function event() : string;

    /**
     * Приоритет.
     *
     * @return integer
     */
    public function priority() : int;

    /**
     * @param Event $event
     *
     * @return mixed
     */
    public function action($event) : void;
}