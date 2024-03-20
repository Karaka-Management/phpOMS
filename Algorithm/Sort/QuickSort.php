<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Algorithm\Sort;
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Sort;

/**
 * QuickSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class QuickSort implements SortInterface
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
        $n = \count($list);

        if ($n < 2) {
            return $list;
        }

        $copy = $list;
        self::qsort($copy, 0, $n - 1, $order);

        return $copy;
    }

    /**
     * Recursive quick sort
     *
     * @param array $list  Data to sort
     * @param int   $lo    Low or left point to sort
     * @param int   $hi    High or right point to sort
     * @param int   $order Sort order
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function qsort(array &$list, int $lo, int $hi, int $order) : void
    {
        if ($lo < $hi) {
            $i = self::partition($list, $lo, $hi, $order);
            self::qsort($list, $lo, $i - 1, $order);
            self::qsort($list, $i + 1, $hi, $order);
        }
    }

    /**
     * Partition data and count the partitions
     *
     * @param array $list  Data to sort
     * @param int   $lo    Low or left point to sort
     * @param int   $hi    High or right point to sort
     * @param int   $order Sort order
     *
     * @return int
     *
     * @since 1.0.0
     */
    private static function partition(array &$list, int $lo, int $hi, int $order) : int
    {
        $pivot = $list[$hi];
        $i     = $lo - 1;

        for ($j = $lo; $j <= $hi - 1; ++$j) {
            if (!$list[$j]->compare($pivot, $order)) {
                ++$i;
                $old      = $list[$i];
                $list[$i] = $list[$j];
                $list[$j] = $old;
            }
        }

        $old          = $list[$i + 1];
        $list[$i + 1] = $list[$hi];
        $list[$hi]    = $old;

        return $i + 1;
    }
}
