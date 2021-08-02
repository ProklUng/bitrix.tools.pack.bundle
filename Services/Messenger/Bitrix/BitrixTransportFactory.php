<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Messenger\Bitrix;

use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use Prokl\BitrixOrdinaryToolsBundle\Services\Messenger\Bitrix\Entity\MessageTable;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

/**
 * Class BitrixTransportFactory
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Messenger\Bitrix
 *
 * @internal Форк из https://github.com/bsidev/bitrix-queue.
 */
class BitrixTransportFactory implements TransportFactoryInterface
{
    /**
     * BitrixTransportFactory конструктор
     */
    public function __construct()
    {
        // Создать кастомную таблицу, если еще не.
        if (!Application::getConnection()->isTableExists(
            Base::getInstance(MessageTable::class)->getDBTableName()
        )) {
            Base::getInstance(MessageTable::class)->createDBTable();
        }
    }

    /**
     * @inheritdoc
     */
    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        unset($options['transport_name']);

        if (preg_match('/:\/\/$/', $dsn)) {
            $configuration = $options;
        } else {
            $configuration = Connection::buildConfiguration($dsn, $options);
        }

        return new BitrixTransport(new Connection($configuration), $serializer);
    }

    /**
     * @inheritdoc
     */
    public function supports(string $dsn, array $options): bool
    {
        return strpos($dsn, 'bitrix://') === 0;
    }
}