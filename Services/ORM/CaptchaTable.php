<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\ORM;

use Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\DatetimeField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator,
    Bitrix\Main\SystemException,
    Bitrix\Main\ArgumentTypeException;

/**
 * Class CaptchaTable
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\ORM
 *
 * Fields:
 * <ul>
 * <li> ID string(32) mandatory
 * <li> CODE string(20) mandatory
 * <li> IP string(15) mandatory
 * <li> DATE_CREATE datetime mandatory
 * </ul>
 */
class CaptchaTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'b_captcha';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return [
            'ID' => new StringField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => '',
            ]),
            'CODE' => new StringField('CODE', [
                'validation' => [__CLASS__, 'validateCode'],
                'title' => '',
            ]),
            'IP' => new StringField('IP', [
                'validation' => [__CLASS__, 'validateIp'],
                'title' => '',
            ]),
            'DATE_CREATE' => new DatetimeField('DATE_CREATE', [
                'title' => '',
            ]),
        ];
    }

    /**
     * Returns validators for CODE field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateCode(): array
    {
        return [
            new LengthValidator(null, 20),
        ];
    }

    /**
     * Returns validators for IP field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateIp(): array
    {
        return [
            new LengthValidator(null, 15),
        ];
    }
}