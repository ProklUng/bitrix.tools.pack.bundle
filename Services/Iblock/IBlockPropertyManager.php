<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Iblock;

use CIBlockProperty;
use CIBlockPropertyEnum;
use CIBlockPropertyEnumResult;
use Prokl\BitrixOrdinaryToolsBundle\Services\Facades\CacherFacade;

/**
 * Class IBlockPropertyManager
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Iblock
 *
 */
class IBlockPropertyManager
{
    /** Свойство типа - список с XML_ID.
     *
     * @param array $arParams Параметры с типом и кодом инфоблока.
     *
     * @return array
     */
    public function getPropertyEnumListByCode($arParams = ['IBLOCK_ID', 'PROPERTY_CODE']) : array
    {
        $arPropEnumList = [];

        $arSort = ['ID' => 'ASC'];
        $arFilter = ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => $arParams['PROPERTY_CODE']];

        $rs = CIBlockPropertyEnum::GetList($arSort, $arFilter);

        /** @var array $ob */
        while ($ob = $rs->Fetch()) {
            $arPropEnumList[(int)$ob['ID']] = $ob;
        }

        return $arPropEnumList;
    }

    /**
     * Свойство типа - список с XML_ID из кэша.
     *
     * @param integer $iblockID     ID инфоблока.
     * @param string  $propertyCode Код свойства инфоблока.
     *
     * @return mixed
     * @psalm-suppress MixedMethodCall
     */
    public function getPropertyEnumListByCodeCached(int $iblockID, string $propertyCode)
    {
        return CacherFacade::setCacheId('property' . $iblockID . $propertyCode)
            ->setCallback([$this, 'getPropertyEnumListByCode'])
            ->setCallbackParams(['IBLOCK_ID' => $iblockID, 'PROPERTY_CODE' => $propertyCode])
            ->setTtl(604800)
            ->returnResultCache();
    }

    /** Все свойства ИБ.
     *
     * @param integer $iblockId ID инфоблока.
     *
     * @return array
     */
    public function getPropertiesIblockID(int $iblockId)
    {
        $arResult = [];

        $properties = CIBlockProperty::GetList(
            ['sort' => 'asc'],
            ['IBLOCK_ID' =>$iblockId]
        );

        /** @var array $prop_fields */
        while ($prop_fields = $properties->GetNext()) {
            $arResult[(string)$prop_fields['CODE']] = $prop_fields;
        }

        return $arResult;
    }

    /**
     * Все свойства ИБ из кэша.
     *
     * @param string $iblockID - ID инфоблока
     *
     * @return mixed
     * @psalm-suppress MixedMethodCall
     */
    public function getPropertiesIblockIDCached($iblockID)
    {
        return CacherFacade::setCacheId($iblockID . '_getPropertiesIblockID')
            ->setCallback([$this, 'getPropertiesIblockID'])
            ->setCallbackParams($iblockID)
            ->setTtl(604800)
            ->returnResultCache();
    }
}
