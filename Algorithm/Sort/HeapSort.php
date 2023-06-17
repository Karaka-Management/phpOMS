<?php
/**
 * Jingga
 *
 * PHP Version 8.1
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
 * HeapSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class HeapSort implements SortInterface
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

        for ($p = (int) ($n / 2 - 1); $p >= 0; --$p) {
            self::heapify($copy, $n, $p, $order);
        }

        for ($i = $n - 1; $i > 0; --$i) {
            $temp     = $copy[$i];
            $copy[$i] = $copy[0];
            $copy[0]  = $temp;

            --$n;
            self::heapify($copy, $n, 0, $order);
        }

        return $copy;
    }

    /**
     * Convert into heap data structure
     *
     * @param array $list  Data to sort
     * @param int   $size  Heap size
     * @param int   $index Index element
     * @param int   $order Sort order
     *
     * @return void
     *
     * @since 1.0.0
     */
    private static function heapify(array &$list, int $size, int $index, int $order) : void
    {
        $left  = ($index + 1) * 2 - 1;
        $right = ($index + 1) * 2;
        $pivot = 0;

        $pivot = $left < $size && $list[$left]->compare($list[$index], $order) ? $left : $index;

        if ($right < $size && $list[$right]->compare($list[$pivot], $order)) {
            $pivot = $right;
        }

        if ($pivot !== $index) {
            $temp         = $list[$index];
            $list[$index] = $list[$pivot];
            $list[$pivot] = $temp;

            self::heapify($list, $size, $pivot, $order);
        }
    }
}
