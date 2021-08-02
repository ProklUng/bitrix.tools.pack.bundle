<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Messenger\Bitrix;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Receiver\ListableReceiverInterface;
use Symfony\Component\Messenger\Transport\Receiver\MessageCountAwareInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

/**
 * Class BitrixTransport
 * @package Local\Services\Messanger
 *
 * @internal Форк из https://github.com/bsidev/bitrix-queue.
 */
class BitrixTransport implements TransportInterface, MessageCountAwareInterface, ListableReceiverInterface
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
     * @var BitrixReceiver $receiver
     */
    private $receiver;

    /**
     * @var BitrixSender $sender
     */
    private $sender;

    /**
     * BitrixTransport constructor.
     *
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): iterable
    {
        return ($this->receiver ?? $this->getReceiver())->get();
    }

    /**
     * {@inheritdoc}
     */
    public function ack(Envelope $envelope): void
    {
        ($this->receiver ?? $this->getReceiver())->ack($envelope);
    }

    /**
     * {@inheritdoc}
     */
    public function reject(Envelope $envelope): void
    {
        ($this->receiver ?? $this->getReceiver())->reject($envelope);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageCount(): int
    {
        return ($this->receiver ?? $this->getReceiver())->getMessageCount();
    }

    /**
     * {@inheritdoc}
     */
    public function all(int $limit = null): iterable
    {
        return ($this->receiver ?? $this->getReceiver())->all($limit);
    }

    /**
     * {@inheritdoc}
     */
    public function find($id): ?Envelope
    {
        return ($this->receiver ?? $this->getReceiver())->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function send(Envelope $envelope): Envelope
    {
        return ($this->sender ?? $this->getSender())->send($envelope);
    }

    /**
     * @return BitrixReceiver
     */
    private function getReceiver(): BitrixReceiver
    {
        return $this->receiver = new BitrixReceiver($this->connection, $this->serializer);
    }

    /**
     * @return BitrixSender
     */
    private function getSender(): BitrixSender
    {
        return $this->sender = new BitrixSender($this->connection, $this->serializer);
    }
}
