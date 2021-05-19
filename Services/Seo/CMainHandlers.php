<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Seo;

use CHTTP;
use Prokl\BitrixOrdinaryToolsBundle\Services\Facades\LastModifiedFacade;

/**
 * Class CMainHandlers
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Seo
 */
class CMainHandlers
{
    /**
     * Обработчик LastModified заголовков.
     *
     * @return boolean
     */
    public static function checkIfModifiedSince() : bool
    {
         // Для админов - выключить.
        if ($GLOBALS['USER']->IsAdmin()
            ||
            env('DEBUG', false) === false
        ) {
            return true;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        $lastModifiedStamp = LastModifiedFacade::getNewestModified();

        if (!empty($lastModifiedStamp) && !headers_sent()) {
            header('Cache-Control: public');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $lastModifiedStamp) . ' GMT');

            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])
                && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $lastModifiedStamp) {
                $GLOBALS['APPLICATION']->RestartBuffer();
                CHTTP::SetStatus('304 Not Modified');
                exit();
            }
        }

        return false;
    }
}
