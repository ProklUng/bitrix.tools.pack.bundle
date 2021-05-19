<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Seo;

/**
 * Class Clearizer
 * @package Local\SEO
 */
class Clearizer
{
    /**
     * Зачистка HTML комментариев.
     *
     * @param mixed $buffer Буфер.
     *
     * @return void
     */
    public function clearHtmlComments(&$buffer) : void
    {
        global $USER;

        if ($USER->IsAuthorized()) {
            return;
        }

        $buffer = trim(preg_replace(
            [
                '/<!--(?![^<]*noindex)(.*?)-->/s',
                '/<!-(?![^<]*noindex)(.*?)->/s',
                '/<!--(?![^<]*noindex)(.*?)->/s',
            ],
            [
                '', '', ''
            ],
            $buffer
        ));
    }

    /**
     * Убрать type="text/javascript".
     *
     * @param mixed $buffer Буфер.
     */
    public function removeTypeScript(&$buffer)
    {
        $buffer = str_replace(" type=\"text/javascript\"", false, $buffer);
    }
}
