<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Messenger\Bitrix;

use Symfony\Component\Messenger\Stamp\NonSendableStampInterface;

/**
 * Class BitrixReceivedStamp
 * @package Prokl\BitrixOrdinaryToolsBundle\Services\Messenger\Bitrix
 *
 * @internal Форк из https://github.com/bsidev/bitrix-queue.
 */
class BitrixReceivedStamp implements NonSendableStampInterface
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * BitrixReceivedStamp constructor.
     *
     * @param integer $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }
}
