<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Iblock;

use Bitrix\Iblock\IblockTable;
use Bitrix\Main\ArgumentException;
use CFile;
use CIBlock;
use Prokl\BitrixOrdinaryToolsBundle\Services\Facades\CacherFacade;

/**
 * Class IblockManager
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Iblock
 */
class IblockManager
{
    /**
     * @var integer $defaultCacheTTl Время жизни кэша по умолчанию.
     */
    private $defaultCacheTTl = 86400;

    /** ID инфоблока по коду.
     *
     * @param string $iblockType Тип инфоблока.
     * @param string $iblockCode Код инфоблока.
     * @return mixed
     *
     * @throws ArgumentException Когда инфоблок не найден.
     */
    public function getIBlockIdByCode(string $iblockType, string $iblockCode) : int
    {
        $iBlockTable = IblockTable::getList([
            'select' => ['ID'],
            'filter' => ['=CODE' => $iblockCode, "=TYPE" => $iblockType],
            'cache' => ['ttl' => $this->defaultCacheTTl],
        ]);

        if ($iblockData = $iBlockTable->fetch()) {
            return (int)$iblockData['ID'];
        }

        throw new ArgumentException('Инфоблок '.$iblockCode.' не найден', $iblockCode);
    }

    /**
     * Описание инфоблока.
     *
     * @param string $iblockType Тип инфоблока.
     * @param string $iblockCode Код инфоблока.
     *
     * @return mixed
     * @throws ArgumentException Когда инфоблок не найден.
     */
    public function getIBlockDescriptionByCode(string $iblockType, string $iblockCode)
    {
        $iBlockTable = IblockTable::getList([
            'select' => ['ID', 'DESCRIPTION'],
            'filter' => ['=ACTIVE' => 'Y', '=CODE' => $iblockCode, "=TYPE" => $iblockType],
            'cache' => ['ttl' => $this->defaultCacheTTl],
        ]);

        $arResult = $iBlockTable->Fetch();
        if ($arResult['ID'] > 0) {
            return $arResult['DESCRIPTION'];
        }

        throw new ArgumentException('Инфоблок '.$iblockCode.' не найден', $iblockCode);
    }

    /**
     * Описание инфоблока по ID.
     *
     * @param integer $iblockId
     *
     * @return string
     */
    public function getDescriptionById(int $iblockId) : string
    {
        $description = $this->getFieldValue($iblockId, 'DESCRIPTION');

        return !empty($description) ? $description : '';
    }

    /**
     * Описание инфоблока по ID.
     *
     * @param int $iblockId
     *
     * @return string
     */
    public function getDescriptionByIdCached(int $iblockId) : string
    {
        $cacher = CacherFacade::setCacheId('iblockDescription' . $iblockId)
            ->setCallback([$this, 'getDescriptionById'])
            ->setCallbackParams($iblockId)
            ->setTtl(604800);

        return $cacher->returnResultCache();
    }

    /**
     * Название инфоблока по ID.
     *
     * @param integer $iblockId
     *
     * @return string
     */
    public function getNameById(int $iblockId) : string
    {
        $name = $this->getFieldValue($iblockId, 'NAME');

        return !empty($name) ? $name : '';
    }

    /**
     * Название инфоблока по ID из кэша.
     *
     * @param integer $iblockId
     *
     * @return string
     */
    public function getNameByIdCached(int $iblockId) : string
    {
        $cacher = CacherFacade::setCacheId('iblockName' . $iblockId)
            ->setCallback([$this, 'getNameById'])
            ->setCallbackParams($iblockId)
            ->setTtl(604800);

        return $cacher->returnResultCache();
    }

    /**
     * Название инфоблока по коду.
     *
     * @param string $iblockCode
     * @return string
     */
    public function getNameByCode(string $iblockCode) : string
    {
        $query = CIBlock::GetList(
            [],
            ['ACTIVE' => 'Y', 'CODE' => $iblockCode]
        );

        $arResult = $query->Fetch();

        return !empty($arResult['NAME']) ? $arResult['NAME'] : '';
    }

    /**
     * Получить URL инфоблока.
     *
     * @param array $arParams
     *
     * @return mixed
     * @throws ArgumentException
     */
    public function getIblockUrlByCode($arParams = ['TYPE', 'CODE'])
    {
        $res = CIBlock::GetList(
            [],
            ['ACTIVE' => 'Y', 'TYPE' => $arParams['TYPE'], 'CODE' => $arParams['CODE']]
        );
        $arResult = $res->Fetch();

        if ($arResult['ID'] > 0) {
            return str_replace('#SITE_DIR#', '', $arResult['LIST_PAGE_URL']);
        }

        throw new ArgumentException('Инфоблок '.$arParams['CODE'].' не найден', $arParams['CODE']);
    }

    /**
     * Кэшированный ответ - URL инфоблока по коду.
     *
     * @param string $sIBlockType Тип инфоблока.
     * @param string $sIBlockCode Код инфоблока.
     *
     * @return mixed
     */
    public function getIblockUrlByCodeCached($sIBlockType, $sIBlockCode)
    {
        $cacher = CacherFacade::setCacheId('iblockurl'.$sIBlockType . $sIBlockCode)
            ->setCallback([$this, 'getIblockUrlByCode'])
            ->setCallbackParams($sIBlockType, $sIBlockCode)
            ->setTtl(604800);

        return $cacher->returnResultCache();
    }

    /**
     * Получить картинку инфоблока.
     *
     * @param integer $iblockId ID инфоблока.
     *
     * @return string
     */
    public function getImageIB(int $iblockId) : string
    {
        $iPictureId = $this->getImageIbId($iblockId);
        $sUrlPicture = CFile::GetPath($iPictureId);

        return $sUrlPicture ?: '';
    }

    /**
     * Получить ID картинки инфоблока.
     *
     * @param integer $iblockId ID инфоблока.
     *
     * @return integer
     */
    public function getImageIbId(int $iblockId) : int
    {
        $iPictureId = $this->getFieldValue($iblockId, 'PICTURE');

        return $iPictureId ?: 0;
    }

    /**
     * Получить ID картинки инфоблока. Кэширование.
     *
     * @param integer $iblockId ID инфоблока.
     *
     * @return mixed
     */
    public function getImageIbIdCached(int $iblockId)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $cacher = CacherFacade::setCacheId('iblockimage'.$iblockId)
            ->setCallback([$this, 'getImageIbId'])
            ->setCallbackParams($iblockId)
            ->setTtl(604800);

        return $cacher->returnResultCache();
    }

    /**
     * Получить ID картинки инфоблока по его коду.
     *
     * @param string $iblockCode Код инфоблока.
     *
     * @return integer
     */
    public function getImageByCode(string $iblockCode) : int
    {
        $query = CIBlock::GetList(
            [],
            ['ACTIVE' => 'Y', 'CODE' => $iblockCode]
        );

        $arResult = $query->Fetch();

        return !empty($arResult['PICTURE']) ? $arResult['PICTURE'] : 0;
    }

    /**
     * Поле из свойств инфоблока.
     *
     * @param int $iblockId
     * @param string $field
     *
     * @return mixed
     */
    private function getFieldValue(int $iblockId, string $field)
    {
        $arData = CIBlock::GetArrayByID($iblockId);

        return $arData[$field];
    }

    /**
     * @param integer $defaultCacheTTl
     */
    public function setCacheTTl(int $defaultCacheTTl): void
    {
        $this->defaultCacheTTl = $defaultCacheTTl;
    }
}
