<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Email\EventBridge\Sender;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Proklung\Notifier\Bitrix\EventBridgeMail;
use Prokl\BitrixOrdinaryToolsBundle\Services\Email\EventBridge\Notifier\BitrixNotification;
use Proklung\Notifier\Bitrix\Utils\EventTableUpdater;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

/**
 * Class BitrixPolicySender
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Email\EventBridge\Sender
 *
 * @since 29.07.2021
 */
class BitrixPolicySender
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
     * Отправить сообщение.
     *
     * @param string $codeEvent  Код события.
     * @param array  $arFields   Параметры события.
     * @param string $importance Важность сообщения (в понимании Notifier).
     *
     * @return void
     * @throws ArgumentException | ObjectPropertyException | SystemException Битриксовые ошибки.
     */
    public function send(string $codeEvent, array $arFields, string $importance) : void
    {
        $eventsInfo = $this->eventBridge->getMessageTemplate($codeEvent);
        foreach ($eventsInfo as $eventInfo) {
            $compileData = $this->eventBridge->compileMessage($eventInfo, $arFields, ['s1']);

            $notification = (new BitrixNotification($compileData['subject']))
                ->content($compileData['body'])
                ->importance($importance);

            $recipient = new Recipient($compileData['mail_to']);

            $this->notifier->send($notification, $recipient);

            // Эмуляция поведения Битрикса при обработке событий.
            EventTableUpdater::create($eventInfo->getEventCode(), $eventInfo->getMessageData(), 99999);
        }
    }
}
