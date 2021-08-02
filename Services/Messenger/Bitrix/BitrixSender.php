<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Messenger\Bitrix;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Serialization\PhpSerializer;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

/**
 * Class BitrixSender
 * @package Local\Services\Messanger
 *
 * @internal Форк из https://github.com/bsidev/bitrix-queue.
 */
class BitrixSender implements SenderInterface
{
    /**
     * @var Connection $connection
     */
    private $connection;

    /**
     * @var SerializerInterface $serializer
     */
    private $serializer;

    /**
     * BitrixSender constructor.
     *
     * @param Connection               $connection
     * @param SerializerInterface|null $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer = null)
    {
        $this->connection = $connection;
        $this->serializer = $serializer ?? new PhpSerializer();
    }

    /**
     * {@inheritdoc}
     */
    public function send(Envelope $envelope): Envelope
    {
        $encodedMessage = $this->serializer->encode($envelope);

        /** @var DelayStamp|null $delayStamp */
        $delayStamp = $envelope->last(DelayStamp::class);
        $delay = null !== $delayStamp ? $delayStamp->getDelay() : 0;

        $id = $this->connection->send($encodedMessage['body'], $encodedMessage['headers'] ?? [], $delay);

        return $envelope->with(new TransportMessageIdStamp($id));
    }
}
