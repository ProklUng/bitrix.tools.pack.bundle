<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Utils;

use CFile;
use CMain;

/**
 * Class CFileWrapper
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Utils
 */
class CFileWrapper
{
    /**
     * @var CFile $file
     */
    private $file;

    /**
     * CFileWrapper constructor.
     *
     * @param CFile $file Битриксовый CFile.
     */
    public function __construct(
        CFile $file
    ) {
        $this->file = $file;
    }

    /**
     * Путь к файлу.
     *
     * @param integer $fileId ID файла.
     *
     * @return string
     */
    public function path(int $fileId) : string
    {
        $result =  $this->file::GetPath($fileId);

        return $result ?? '';
    }

    /**
     * Сохранить файл.
     *
     * @param string $path     Исходный путь.
     * @param string $savePath Поддиректория для сохранения.
     *
     * @return integer
     */
    public function saveFile(string $path, string $savePath) : int
    {
        if (!$path) {
            return 0;
        }

        $arFile = $this->file::MakeFileArray($_SERVER['DOCUMENT_ROOT'] . $path);

        return (int)$this->file::SaveFile($arFile, $savePath);
    }

    /**
     * Сохранить файл в Битриксе и вернуть URL.
     *
     * @param string $path     Исходный путь.
     * @param string $savePath Поддиректория для сохранения.
     *
     * @return string
     */
    public function savedFileUrl(string $path, string $savePath) : string
    {
        if (!$path) {
            return '';
        }

        $result = $this->saveFile($path, $savePath);
        if (!$result) {
            return '';
        }

        return (string)$this->file::GetPath($result);
    }

    /**
     * Возвращает полную ссылку на сервер.
     *
     * @return string
     */
    public function getSchemaAndHost() : string
    {
        $protocol = CMain::IsHTTPS() ? 'https://' : 'http://';

        return $protocol . $_SERVER['HTTP_HOST'];
    }

    /**
     * Возвращает текущую схему к серверу.
     *
     * @return string
     */
    public function getSchema() : string
    {
        return CMain::IsHTTPS() ? 'https://' : 'http://';
    }
}
