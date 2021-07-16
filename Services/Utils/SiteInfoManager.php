<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Utils;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Context;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SiteTable;
use Bitrix\Main\SystemException;

/**
 * Class SiteInfoManager
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Utils
 *
 * @since 16.07.2021
 */
class SiteInfoManager
{
    /**
     * SiteId.
     *
     * @return string
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function getSiteId(): string
    {
        if (!$siteId = Context::getCurrent()->getSite()) {
            $siteId = (string)SiteTable::getList([
                'order' => ['SORT' => 'ASC'],
                'select' => ['LID'],
                'cache' => ['ttl' => 86400],
            ])->fetch()['LID'];
        }

        return $siteId;
    }
}