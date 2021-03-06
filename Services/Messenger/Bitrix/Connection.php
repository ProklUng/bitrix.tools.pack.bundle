<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Messenger\Bitrix;

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Db\SqlQueryException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Exception;
use Prokl\BitrixOrdinaryToolsBundle\Services\Messenger\Bitrix\Entity\MessageTable;
use Symfony\Component\Messenger\Exception\InvalidArgumentException;
use Symfony\Component\Messenger\Exception\TransportException;
use Throwable;

/**
 * Class Connection
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Messenger\Bitrix
 *
 * @internal Форк из https://github.com/bsidev/bitrix-queue.
 */
class Connection
{
    protected const DEFAULT_OPTIONS = [
        'queue_name' => 'default',
        'redeliver_timeout' => 3600,
    ];

    /**
     * Configuration of the connection.
     *
     * Available options:
     *
     * * queue_name: name of the queue
     * * redeliver_timeout: Timeout before redeliver messages still in handling state
     * (i.e: delivered_at is not null and message is still in table). Default: 3600
     */
    protected $configuration = [];

    /**
     * Connection constructor.
     *
     * @param array $configuration Конфигурация.
     */
    public function __construct(array $configuration = [])
    {
        $this->configuration = array_replace_recursive(static::DEFAULT_OPTIONS, $configuration);
    }

    /**
     * @param string $dsn     DSN.
     * @param array  $options Опции.
     *
     * @return array
     */
    public static function buildConfiguration(string $dsn, array $options = []): array
    {
        if (($components = parse_url($dsn)) === false) {
            throw new InvalidArgumentException(sprintf('The given Bitrix DSN "%s" is invalid.', $dsn));
        }

        $query = [];
        if (isset($components['query'])) {
            parse_str($components['query'], $query);
        }

        $configuration = [];
        /** @noinspection AdditionOperationOnArraysInspection */
        $configuration += $query + $options + static::DEFAULT_OPTIONS;

        $optionsExtraKeys = array_diff(array_keys($options), array_keys(static::DEFAULT_OPTIONS));

        if (count($optionsExtraKeys) > 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unknown option found: [%s]. Allowed options are [%s].',
                    implode(', ', $optionsExtraKeys),
                    implode(', ', array_keys(static::DEFAULT_OPTIONS))
                )
            );
        }

        $queryExtraKeys = array_diff(array_keys($query), array_keys(static::DEFAULT_OPTIONS));
        if (count($queryExtraKeys) > 0) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unknown option found in DSN: [%s]. Allowed options are [%s].',
                    implode(', ', $queryExtraKeys),
                    implode(', ', array_keys(static::DEFAULT_OPTIONS))
                )
            );
        }

        return $configuration;
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @param string   $body
     * @param array   $headers
     * @param integer $delay
     *
     * @return integer
     * @throws Exception
     */
    public function send(string $body, array $headers, int $delay = 0): int
    {
        $now = new DateTime();

        $availableAt = (clone $now)->add(sprintf('+%d seconds', $delay / 1000));

        $result = MessageTable::add([
            'BODY' => $body,
            'HEADERS' => $headers,
            'QUEUE_NAME' => $this->configuration['queue_name'],
            'CREATED_AT' => $now,
            'AVAILABLE_AT' => $availableAt,
        ]);
        if (!$result->isSuccess()) {
            throw new TransportException(implode("\n", $result->getErrorMessages()), 0);
        }

        return (int) $result->getId();
    }

    /**
     * @return array|null
     *
     * @throws ArgumentException | SqlQueryException | ObjectPropertyException | SystemException
     * @throws Throwable
     */
    public function get(): ?array
    {
        $driverConnection = Application::getConnection(MessageTable::getConnectionName());

        $driverConnection->startTransaction();
        try {
            $query = $this->createAvailableMessagesQuery()
                ->addOrder('AVAILABLE_AT', 'ASC')
                ->setLimit(1);

            $bitrixEnvelope = $query->exec()->fetch();

            if ($bitrixEnvelope === false || $bitrixEnvelope === null) {
                return null;
            }

            $result = MessageTable::update($bitrixEnvelope['ID'], ['DELIVERED_AT' => new DateTime()]);
            if (!$result->isSuccess()) {
                throw new TransportException(implode("\n", $result->getErrorMessages()), 0);
            }

            $driverConnection->commitTransaction();

            return $bitrixEnvelope;
        } catch (Throwable $e) {
            $driverConnection->rollbackTransaction();
            throw $e;
        }
    }

    /**
     * @param integer $id
     *
     * @return boolean
     * @throws Exception
     */
    public function ack(int $id): bool
    {
        $result = MessageTable::delete($id);
        if (!$result->isSuccess()) {
            throw new TransportException(implode("\n", $result->getErrorMessages()), 0);
        }

        return true;
    }

    /**
     * @param integer $id
     *
     * @return boolean
     * @throws Exception
     */
    public function reject(int $id): bool
    {
        return $this->ack($id);
    }

    /**
     * @return integer
     * @throws ObjectPropertyException | SystemException
     */
    public function getMessageCount(): int
    {
        $query = $this->createAvailableMessagesQuery()
            ->setSelect(['CNT' => Query::expr()->count('ID')])
            ->setLimit(1);

        $data = $query->exec()->fetch();

        return (int) $data['CNT'];
    }

    /**
     * @param integer|null $limit
     *
     * @return array
     *
     * @throws ObjectPropertyException | SystemException
     */
    public function findAll(int $limit = null): array
    {
        $query = $this->createAvailableMessagesQuery();
        if ($limit !== null) {
            $query->setLimit($limit);
        }

        return $query->exec()->fetchAll();
    }

    /**
     * @param integer $id
     *
     * @return array|null
     * @throws ArgumentException | ObjectPropertyException | SystemException
     */
    public function find(int $id): ?array
    {
        return MessageTable::getRowById($id);
    }

    /**
     * @return Query
     *
     * @throws ArgumentException | SystemException
     */
    private function createAvailableMessagesQuery(): Query
    {
        $now = new DateTime();

        $redeliverLimit = (clone $now)->add(sprintf('-%d seconds', $this->configuration['redeliver_timeout']));

        return MessageTable::query()
            ->setSelect(['*'])
            ->where(
                Query::filter()->logic('OR')
                    ->whereNull('DELIVERED_AT')
                    ->where('DELIVERED_AT', '<', $redeliverLimit)
            )
            ->where('AVAILABLE_AT', '<=', $now)
            ->where('QUEUE_NAME', $this->configuration['queue_name']);
    }
}
