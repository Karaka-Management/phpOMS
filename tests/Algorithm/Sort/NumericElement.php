<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Algorithm\Sort;

use phpOMS\Algorithm\Sort\SortableInterface;
use phpOMS\Algorithm\Sort\SortOrder;

require_once __DIR__ . '/../../Autoloader.php';

class NumericElement implements SortableInterface
{
    public int |

float $value = 0;

    public function __construct(int | float $value)
    {
        $this->value = $value;
    }

    public function compare(SortableInterface $obj, int $order = SortOrder::ASC) : bool
    {
        return $order === SortOrder::ASC ? $this->value >= $obj->value : $this->value <= $obj->value;
    }

    public function equals(SortableInterface $obj) : bool
    {
        return $this->value === $obj->getValue();
    }

    public function getValue() : int | float
    {
        return $this->value;
    }

    public static function max(array $list) : int | float
    {
        $values = [];
        foreach ($list as $element) {
            $values[] = $element->value;
        }

        return \max($values);
    }

    public static function min(array $list) : int | float
    {
        $values = [];
        foreach ($list as $element) {
            $values[] = $element->value;
        }

        return \min($values);
    }
}
