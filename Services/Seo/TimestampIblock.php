<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Seo;

use CIBlockElement;

/**
 * Class TimestampIblock
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Seo
 */
class TimestampIblock
{
    /**
     * @var CIBlockElement
     */
    private $el;

    /**
     * @var integer
     */
    private $iblockId;

    /**
     * TimestampIblock constructor.
     *
     * @param CIBlockElement $el Битриксовый CIBlockElement.
     */
    public function __construct(
        CIBlockElement $el
    ) {
        $this->el = $el;
    }

    /**
     * Получить свежайший timestamp.
     *
     * @return string
     */
    public function getNewestTimestamp() : string
    {
        $arData = $this->query($this->iblockId);

        return $arData[0]['TIMESTAMP_X'] ?? '';
    }

    /**
     * Сеттер ID инфоблока.
     *
     * @param integer $iblockId ID инфоблока.
     *
     * @return $this
     */
    public function setIblockId(int $iblockId): self
    {
        $this->iblockId = $iblockId;

        return $this;
    }

    /**
     * ВСЕ Элементы инфоблока.
     *
     * @param integer $iblockId ID инфоблока.
     *
     * @return array
     */
    private function query(
        int $iblockId
    ) : array {

        $arResult = [];
        $result = $this->el->GetList(
            ['timestamp_x' => 'desc'],
            ['IBLOCK_ID' => $iblockId],
            false,
            false,
            ['TIMESTAMP_X']
        );

        while ($item = $result->fetch()) {
            $arResult[] = $item;
        }

        return $arResult;
    }
}
