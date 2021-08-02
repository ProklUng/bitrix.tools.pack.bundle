<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\ErrorHandler\Entity;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;

/**
 * Class ErrorLogTable
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\ErrorHandler\Entity
 */
class ErrorLogTable extends DataManager
{
    /**
     * @inheritdoc
     */
    public static function getTableName()
    {
        return 'b_fatal_error_log';
    }

    /**
     * @inheritdoc
     */
    public static function getMap()
    {
        return [
            'ID' => new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => '',
            ]),
            'DATE_CREATE' => new DatetimeField('DATE_CREATE', [
                'title' => 'Дата создания',
            ]),
            'MD5' => new StringField('MD5', [
                'title' => 'MD5 исключения',
                'required' => true
            ]),
            'EXCEPTION' => new TextField('EXCEPTION', [
                'title' => 'Сериализованная ошибка',
                'serialized' => true
            ]),
        ];
    }
}
