<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\ErrorHandler\Agent;

use Bitrix\Main\Application;

/**
 * Class ClearTableAgent
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\ErrorHandler\Agent
 *
 * @since 01.08.2021
 */
class ClearTableAgent
{
    /**
     * Очистка таблицы b_fatal_error_log раз в сутки.
     *
     * @return string
     */
    public static function clear() : string
    {
        $connection = Application::getConnection();

        if ($connection->isTableExists('b_fatal_error_log')) {
            $connection->truncateTable('b_fatal_error_log');
        }

        return '\Proklung\Error\Notifier\ClearTableAgent::clear();';
    }
}
