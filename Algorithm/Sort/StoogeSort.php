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
 * StoogeSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class StoogeSort implements SortInterface
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

        $copy = $list;
        self::stoogeSort($copy, 0, $n - 1, $order);

        return $copy;
    }

    /**
     * Recursively sort each 3rd of the list
     *
     * @param array $list  Data to sort
     * @param int   $lo    Lower bound
     * @param int   $hi    Higher bound
     * @param int   $order Sort order
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function stoogeSort(array &$list, int $lo, int $hi, int $order) : void
    {
        if ($lo >= $hi) {
            return;
        }

        if ($list[$lo]->compare($list[$hi], $order)) {
            $temp      = $list[$lo];
            $list[$lo] = $list[$hi];
            $list[$hi] = $temp;
        }

        if ($hi - $lo + 1 > 2) {
            $t = (int) (($hi - $lo + 1) / 3);

            self::stoogeSort($list, $lo, $hi - $t, $order);
            self::stoogeSort($list, $lo + $t, $hi, $order);
            self::stoogeSort($list, $lo, $hi - $t, $order);
        }
    }
}
