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
 * TimSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class TimSort implements SortInterface
{
    /**
     * Blocks the sorting is divided into
     *
     * @var int
     * @since 1.0.0
     */
    private const BLOCKS = 32;

    /**
     * {@inheritdoc}
     */
    public static function sort(array $list, int $order = SortOrder::ASC) : array
    {
        $n = \count($list);

        if ($n < 2) {
            return $list;
        }

        for ($lo = 0; $lo < $n; $lo += self::BLOCKS) {
            // insertion sort
            $hi = \min($lo + 31, $n - 1);
            for ($j = $lo + 1; $j <= $hi; ++$j) {
                $temp = $list[$j];
                $c    = $j - 1;

                while ($c >= $lo && $list[$c]->compare($temp, $order)) {
                    $list[$c + 1] = $list[$c];
                    --$c;
                }

                $list[$c + 1] = $temp;
            }
        }

        for ($size = self::BLOCKS; $size < $n; $size *= 2) {
            for ($lo = 0; $lo < $n; $lo += 2 * $size) {
                // merge sort
                $mi = $lo + $size - 1;
                $hi = \min($lo + 2 * $size - 1, $n - 1);

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

        return $list;
    }
}
