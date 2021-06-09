<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Iblock;

use Bitrix\Main\ArgumentException;
use CFile;
use CIBlockElement;
use CIBlockSection;

/**
 * Class IBlockSectionManager
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Iblock
 */
class IBlockSectionManager
{
    /**
     * Имя раздела инфоблока или пустую строку.
     *
     * @param integer $idSection ИД раздела инфоблока.
     *
     * @return string
     */
    public function getSBlockSectionNameByID(int $idSection): string
    {
        $obBlockResult = CIBlockSection::GetByID($idSection);

        if ($arSection = $obBlockResult->GetNext()) {
            return $arSection['NAME'];
        }

        return '';
    }

    /** ID инфоблока.
     *
     * @param array $arParams Параметры с типом и кодом инфоблока.
     *
     * @return mixed
     * @throws ArgumentException
     */
    public static function getIBlockSectionIdByCode($arParams = ['IBLOCK_CODE', 'CODE', 'AR_SELECT'])
    {
        $res = CIBlockSection::GetList(
            ['SORT' => 'ASC'],
            [
                'IBLOCK_CODE' => $arParams['IBLOCK_CODE'],
                'CODE' => $arParams['CODE'],
            ],
            false,
            (array)$arParams['AR_SELECT'],
            false
        );

        $arResult = $res->GetNext();
        if ((int)$arResult['ID'] > 0) {
            //если есть картинка, взять путь к ней
            if ((int)$arResult['PICTURE'] > 0) {
                $arResult['PICTURE'] = CFile::GetFileArray((int)$arResult['PICTURE']);
            }

            return $arResult;
        }

        throw new ArgumentException(
            'Раздел в инфоблоке '.$arParams['IBLOCK_CODE'].' не найден',
            $arParams['CODE']
        );
    }

    /**
     * Элементы подраздела по ID.
     *
     * @param array $arParams
     *
     * @return array
     */
    public function getSectionsItemsByCode($arParams = ['IBLOCK_CODE', 'ID', 'AR_SORT', 'AR_SELECT'])
    {
        $res = CIBlockSection::GetList(
            [],
            [
                'IBLOCK_CODE' => $arParams['IBLOCK_CODE'],
                'ID' => $arParams['ID']
            ],
            false,
            [],
            false
        );

        if ($obRes = $res->GetNext()) {
            $iblockId = $obRes['IBLOCK_ID'];
            $idSection = $obRes['ID'];
        } else {
            return [];
        }

        $arSort = (!empty($arParams['AR_SORT'])) ? $arParams['AR_SORT'] : ['SORT' => 'ASC'];
        $arSelect = (!empty($arParams['AR_SELECT'])) ? $arParams['AR_SELECT'] : [];

        $res = CIBlockElement::GetList(
            $arSort,
            [
                'IBLOCK_ID' => $iblockId,
                'SECTION_ID' => $idSection,
                'ACTIVE' => 'Y'
            ],
            false,
            false,
            $arSelect
        );

        $arResult = [];

        while ($ob = $res->GetNext()) {
            $arResult[$ob['ID']] = $ob;
        }

        return $arResult;
    }
}
