<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Logger;

use Bitrix\Main\Application;
use Bitrix\Main\EventLog\Internal\EventLogTable;
use Bitrix\Main\Type\DateTime;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Class EventLogLogger
 * Записывает логи в журнал событий /bitrix/admin/event_log.php?lang=ru.
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Logger
 */
class EventLogLogger extends AbstractProcessingHandler
{
    /**
     * @var string
     */
    private $defaultModuleId;

    /**
     * @inheritdoc
     */
    public function __construct($level = Logger::DEBUG, string $moduleId = '')
    {
        parent::__construct($level);

        $this->defaultModuleId = $moduleId;
    }

    /**
     * @inheritdoc
     */
    protected function write(array $record): void
    {
        global $USER;
        $appContext = Application::getInstance()->getContext();
        $server = $appContext->getServer();

        /** @var \DateTime $datetime */
        $datetime = $record['datetime'];

        EventLogTable::add([
            'TIMESTAMP_X' => DateTime::createFromTimestamp($datetime->getTimestamp()),
            'SEVERITY' => $record['level_name'],
            'MODULE_ID' => $record['context']['MODULE_ID'] ?? $this->defaultModuleId,
            'AUDIT_TYPE_ID' => $record['level_name'],
            'ITEM_ID' => $record['context']['ITEM_ID'] ?? '',
            'REMOTE_ADDR' => $record['context']['REMOTE_ADDR'] ?? $server->getRemoteAddr(),
            'USER_AGENT' => $record['context']['USER_AGENT'] ?? $server->getUserAgent(),
            'REQUEST_URI' => $record['context']['REQUEST_URI'] ?? $server->getRequestUri(),
            'SITE_ID' => $record['context']['SITE_ID'] ?? $appContext->getSite(),
            'USER_ID' => $record['context']['USER_ID'] ?? $USER->GetID(),
            'GUEST_ID' => $record['context']['GUEST_ID'] ?? '',
            'DESCRIPTION' => $this->interpolate($record['formatted'], (array)$record['context']),
        ]);
    }

    /**
     * @param string $message
     * @param array  $context
     *
     * @return string
     */
    public function interpolate(string $message, array $context = []): string
    {
        // build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $val) {
            // check that the value can be cast to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}