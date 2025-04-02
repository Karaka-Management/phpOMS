<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Base;

use phpOMS\Stdlib\Base\HeapItemInterface;

final class HeapItem implements HeapItemInterface
{
    private int $value = 0;

    public static function compare(self $a, self $b) : int
    {
        return $a <=> $b;
    }

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function setValue(int $value) : void
    {
        $this->value = $value;
    }

    public function getValue() : int
    {
        return $this->value;
    }

    public function isEqual(HeapItemInterface $item) : bool
    {
        /** @var self $item */
        return $this->value === $item->getValue();
    }
}
