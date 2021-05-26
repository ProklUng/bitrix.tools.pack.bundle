<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Utils;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class LoaderContent
 * Загрузчик контента.
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Utils
 */
class LoaderContent
{
    /**
     * Загрузить контент страницы.
     *
     * @param Request $request Объект Request.
     * @param string  $url     URL.
     *
     * @return string
     */
    public function getContentPage(Request $request, string $url) : string
    {
        $arOptions = [
            'http' =>
                ['header'=> 'Cookie: ' . $request->server->get('HTTP_COOKIE')."\r\n"]
        ];

        $context = stream_context_create($arOptions);

        session_write_close(); // Unlock the file (иначе не получится подменить страницу)

        $protocol = ($request->server->get('HTTPS')
                      &&
                      $request->server->get('HTTPS') !== 'off') ? 'https' : 'http';

        // Путь к файлу.
        $urlFile = $protocol . '://' . $request->server->get('SERVER_NAME') . $url;

        $content = @file_get_contents($urlFile, false, $context);

        session_start();

        return $content;
    }
}
