<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Messenger\Bitrix\Entity;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\ArrayField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;

/**
 * Class MessageTable
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Messenger\Bitrix\Entity
 *
 * @internal Форк из https://github.com/bsidev/bitrix-queue.
 */
class MessageTable extends DataManager
{
    /**
     * @inheritdoc
     */
    public static function getTableName(): string
    {
        return 'symfony_messanger_queue_message';
    }

    /**
     * @inheritdoc
     */
    public static function getMap(): array
    {
        return [
            (new IntegerField('ID'))
                ->configurePrimary(true)
                ->configureAutocomplete(true),

            (new TextField('BODY'))
                ->configureRequired(true),

            (new ArrayField('HEADERS'))
                ->configureRequired(true)
                ->configureSerializationPhp(),

            (new StringField('QUEUE_NAME'))
                ->configureRequired(true)
                ->configureSize(190),

            (new DatetimeField('CREATED_AT'))
                ->configureRequired(true),

            (new DatetimeField('AVAILABLE_AT'))
                ->configureRequired(true),

            (new DatetimeField('DELIVERED_AT')),
        ];
    }
}
