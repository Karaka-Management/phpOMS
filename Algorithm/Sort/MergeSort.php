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
 * MergeSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class MergeSort implements SortInterface
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

        $clone = $list;
        self::sortHalve($clone, 0, $n - 1, $order);

        return $clone;
    }

    /**
     * Recursive sorting of halve of the list and then merging it
     *
     * @param array $list  Data to sort
     * @param int   $lo    Start of the list to sort
     * @param int   $hi    End of the list to sort
     * @param int   $order Sort order
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function sortHalve(array &$list, int $lo, int $hi, int $order) : void
    {
        if ($lo >= $hi) {
            return;
        }

        $mi = (int) ($lo + ($hi - $lo) / 2);

        self::sortHalve($list, $lo, $mi, $order);
        self::sortHalve($list, $mi + 1, $hi, $order);

        self::merge($list, $lo, $mi, $hi, $order);
    }

    /**
     * Merge and sort sub list
     *
     * @param array $list  Data to sort
     * @param int   $lo    Start of the list to sort
     * @param int   $mi    Middle point of the list to sort
     * @param int   $hi    End of the list to sort
     * @param int   $order Sort order
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function merge(array &$list, int $lo, int $mi, int $hi, int $order) : void
    {
        $n1 = $mi - $lo + 1;
        $n2 = $hi - $mi;

        $loList = [];
        $hiList = [];

        for ($i = 0; $i < $n1; ++$i) {
            $loList[$i] = $list[$lo + $i];
        }

        for ($i = 0; $i < $n2; ++$i) {
            $hiList[$i] = $list[$mi + 1 + $i];
        }

        $i = 0;
        $j = 0;
        $k = $lo;

        while ($i < $n1 && $j < $n2) {
            if (!$loList[$i]->compare($hiList[$j], $order)) {
                $list[$k] = $loList[$i];
                ++$i;
            } else {
                $list[$k] = $hiList[$j];
                ++$j;
            }

            ++$k;
        }

        while ($i < $n1) {
            $list[$k] = $loList[$i];
            ++$i;
            ++$k;
        }

        while ($j < $n2) {
            $list[$k] = $hiList[$j];
            ++$j;
            ++$k;
        }
    }
}
