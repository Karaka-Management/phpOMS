<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Algorithm\Sort;
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Sort;

/**
 * SelectionSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class SelectionSort implements SortInterface
{
    /**
     * {@inheritdoc}
     */
    public static function sort(array $list, int $order = SortOrder::ASC) : array
    {
        $n = \count($list);

        if ($n < 2) {
            return $list;
        }

        for ($i = 0; $i < $n - 1; ++$i) {
            $min = $i;

            for ($j = $i + 1; $j < $n; ++$j) {
                if (!$list[$j]->compare($list[$min], $order)) {
                    $min = $j;
                }
            }

            if ($min !== $i) {
                $old        = $list[$i];
                $list[$i]   = $list[$min];
                $list[$min] = $old;
            }
        }

        return $list;
    }
}
