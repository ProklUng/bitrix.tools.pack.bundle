<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Iblock;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\SystemException;
use Prokl\BitrixOrdinaryToolsBundle\Services\Facades\CacherFacade;

/**
 * Class HLIBlockElementManager
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Iblock
 */
class HLIBlockElementManager
{
    /** Результат выборки из HL ИБ.
     *
     * @param string $hLIblockCode Код HL блока.
     * @param array  $arParams     Параметры выборки.
     *
     * @return mixed
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getIBlockElements(
        string $hLIblockCode,
        array $arParams = [
            'AR_ORDER',
            'AR_FILTER',
            'AR_GROUP',
            'AR_SELECT',
        ]
    ): array {

        // Если не передали код HL блока, то прекращаем работу.
        if (!$hLIblockCode) {
            return [];
        }

        $arDefValues = [
            'AR_ORDER' => ['ID' => 'ASC'],
            'AR_FILTER' => [],
            'AR_SELECT' => ['*'],
        ];

        $arParams = array_merge($arDefValues, $arParams);

        $dataManager = $this->getHLBlockClassByCode($hLIblockCode);

        $data = $dataManager::getList(
            [
                'select' => $arParams['AR_SELECT'],
                'order' => $arParams['AR_ORDER'],
                'filter' => $arParams['AR_FILTER'],
            ]
        );

        $arResult = [];

        /** @var array $arData */
        while ($arData = $data->fetch()) {
            $idElement = (int)$arData['ID'];
            $arResult[$idElement] = $arData;
        }

        return $arResult;
    }


    /**
     *  Кэшированный результат выборки из HL блока.
     *
     * @param string $codeHLblock Код HL блока.
     * @param array  $arParams    Параметры с типом и кодом инфоблока.
     *
     * @return mixed
     * @psalm-suppress MixedMethodCall
     */
    public function getIBlockElementsCached(
        string $codeHLblock,
        array $arParams = ['AR_ORDER', 'AR_FILTER', 'AR_SELECT']
    ) : array {
        return CacherFacade::setCacheId($codeHLblock.serialize(array_values($arParams)))
            ->setCallback([$this, 'getIBlockElements'])
            ->setCallbackParams($codeHLblock, $arParams)
            ->setTtl(604800)
            ->returnResultCache();
    }

    /**
     * Получить название класса HL блока по его коду
     *
     * @param string $codeHLblock Код HL блока.
     *
     * @return DataManager
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getHLBlockClassByCode(
        string $codeHLblock
    ): DataManager {
        $hlblock = (string)HighloadBlockTable::getList(
            [
                'filter' => ['=NAME' => $codeHLblock],
            ]
        )->fetch();

        return (HighloadBlockTable::compileEntity($hlblock))->getDataClass();
    }
}
