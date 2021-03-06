<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Email\EventBridge\Sender;

use Prokl\BitrixOrdinaryToolsBundle\Services\Email\EventBridge\Contract\BitrixNotifierSenderInterface;
use Prokl\BitrixOrdinaryToolsBundle\Services\Email\EventBridge\EventBridgeMail;
use Prokl\BitrixOrdinaryToolsBundle\Services\Email\EventBridge\Utils\EventTableUpdater;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

/**
 * Class BitrixMailEventSender
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Email\EventBridge\Sender
 *
 * @since 28.07.2021
 */
class BitrixMailEventSender implements BitrixNotifierSenderInterface
{
    /**
     * @var EventBridgeMail $eventBridge Обработка битриксовых данных события.
     */
    private $eventBridge;

    /**
     * @var NotifierInterface $notifier Notifier.
     */
    private $notifier;

    /**
     * BitrixMailEventSender constructor.
     *
     * @param EventBridgeMail   $eventBridge Обработка битриксовых данных события.
     * @param NotifierInterface $notifier    Notifier.
     */
    public function __construct(EventBridgeMail $eventBridge, NotifierInterface $notifier)
    {
        $this->eventBridge = $eventBridge;
        $this->notifier = $notifier;
    }

    /**
     * Статический фасад.
     *
     * @param NotifierInterface $notifier Notifier.
     *
     * @return static
     */
    public static function getInstance(NotifierInterface $notifier) : self
    {
        return new static(new EventBridgeMail(), $notifier);
    }

    /**
     * @inheritdoc
     */
    public function send(string $codeEvent, array $arFields) : void
    {
        $eventsInfo = $this->eventBridge->getMessageTemplate($codeEvent);
        foreach ($eventsInfo as $eventInfo) {
            $compileData = $this->eventBridge->compileMessage($eventInfo, $arFields, ['s1']);

            $notification = (new Notification($compileData['subject'], ['email']))
                ->content($compileData['body']);

            $recipient = new Recipient($compileData['mail_to']);

            $this->notifier->send($notification, $recipient);

            // Эмуляция поведения Битрикса при обработке событий.
            EventTableUpdater::create($eventInfo->getEventCode(), $eventInfo->getMessageData(), 99999);
        }
    }
}
