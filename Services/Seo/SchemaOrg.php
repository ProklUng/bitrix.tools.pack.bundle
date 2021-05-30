<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Seo;

/**
 * Class SchemaOrg
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Seo
 */
class SchemaOrg
{
    /**
     * Вставка нужного itemprop в description.
     *
     * @param mixed $buffer Буфер.
     *
     * @return void
     */
    public function descriptionItemprop(&$buffer) : void
    {
        $buffer = str_replace('<meta name="description"', '<meta itemprop="description" name="description"', $buffer);
    }
}
