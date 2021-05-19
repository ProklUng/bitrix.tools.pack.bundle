<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class CFileFacade
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Facades
 *
 * @method static path(int $idFile) : string
 * @method static saveFile(string $path, string $savePath) : int
 * @method static savedFileUrl(string $path, string $savePath) : string
 * @method static getSchemaAndHost() : string
 * @method static getSchema() : string
 */
class CFileFacade extends AbstractFacade
{
    /**
     * Сервис фасада.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'bitrix_ordinary_tools.cfile_wrapper';
    }
}
