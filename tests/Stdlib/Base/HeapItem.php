<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Base;

final class HeapItem
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

    public function isEqual(self $item) : bool
    {
        return $this->value === $item->getValue();
    }
}
