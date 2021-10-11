<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
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
 * IntroSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class IntroSort implements SortInterface
{
    /**
     * Constructor
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function sort(array $list, int $order = SortOrder::ASC) : array
    {
        $clone = $list;
        $size  = self::partition($clone, 0, \count($list) - 1, $order);

        if ($size < 16) {
            return InsertionSort::sort($clone, $order);
        }

        if ($size > \log(\count($list)) * 2) {
            return HeapSort::sort($clone, $order);
        }

        return QuickSort::sort($clone);
    }

    /**
     * Partition list and return the size
     *
     * @param array $list  List reference
     * @param int   $lo    Low or left side
     * @param int   $hi    High or right side
     * @param int   $order Order type
     *
     * @return int
     *
     * @since 1.0.0
     */
    private static function partition(array &$list, int $lo, int $hi, int $order) : int
    {
        $pivot = $list[$hi];
        $i     = $lo;

        for ($j = $lo; $j < $hi; ++$j) {
            if ($list[$j]->compare($pivot, $order)) {
                $temp     = $list[$j];
                $list[$j] = $list[$i];
                $list[$i] = $temp;

                ++$i;
            }
        }

        $list[$hi] = $list[$i];
        $list[$i]  = $pivot;

        return $i;
    }
}
