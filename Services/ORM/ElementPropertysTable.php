<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\ORM;

use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;

/**
 * Class ElementPropertysTable
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\ORM
 */
class ElementPropertysTable extends DataManager
{
    /**
     * @var integer $iblockId ID инфоблока.
     */
    protected static $iblockId;

    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return 'b_iblock_element_prop_s' . static::$iblockId;
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     * @throws SystemException
     */
    public static function getMap(): array
    {
        $map = [
            'IBLOCK_ELEMENT_ID' => new IntegerField('IBLOCK_ELEMENT_ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('IBLOCK_ELEMENT_ID_FIELD'),
            ]),
        ];

        $properties = \Bitrix\Iblock\PropertyTable::getList([
            'select' => ['ID', 'PROPERTY_TYPE'],
            'filter' => ['=IBLOCK_ID' => self::$iblockId],
        ])->fetchAll();

        foreach ($properties as $property) {
            $fieldName = 'PROPERTY_' . $property['ID'];
            $fieldEnumName = 'PROPERTY_' . $property['ID'] . '_ENUM';

            switch ($property['PROPERTY_TYPE']) {
                case 'L':
                    $map[$fieldName] = new IntegerField($fieldName);
                    $map[$fieldEnumName] = new Reference(
                        $fieldEnumName,
                        PropertyEnumerationTable::class,
                        Join::on("this.$fieldName", 'ref.ID')
                    );
                    break;
                case 'F':
                case 'E':
                case 'G':
                    $map[$fieldName] = new IntegerField($fieldName);
                    break;
                case 'N':
                    $map[$fieldName] = new FloatField($fieldName);
                    break;
                case 'S':
                default:
                    $map[$fieldName] = new StringField($fieldName);
            }
        }

        return $map;
    }

    /**
     * Returns validators for ID field.
     *
     * @return array
     * @throws ArgumentTypeException
     */
    public static function validateId(): array
    {
        return [
            new LengthValidator(null, 32),
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
     * @param integer $iblockId
     *
     * @return DataManager
     */
    public static function compileEntity(int $iblockId): DataManager
    {
        self::$iblockId = $iblockId;
        $class = 'ElementPropertys' . self::$iblockId . 'Table';

        if (!class_exists($class)) {
            $eval = "class {$class} extends " . __CLASS__ . "{}";
            eval($eval);
        }

        return new $class;
    }
}