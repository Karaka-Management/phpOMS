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
 * Bucketsort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class BucketSort
{
    /**
     * Sort array
     *
     * @param array  $list        List of sortable elements
     * @param int    $bucketCount Buckets to divide the list into
     * @param string $algo        Algorithm to use for sort
     * @param int    $order       Sort order
     *
     * @return array Sorted array
     *
     * @since 1.0.0
     */
    public static function sort(array $list, int $bucketCount, string $algo = InsertionSort::class, int $order = SortOrder::ASC) : array
    {
        $buckets = [];
        $M       = $list[0]::max($list);

        if ($bucketCount < 1) {
            return [];
        }

        if (\count($list) < 2) {
            return $list;
        }

        foreach ($list as $element) {
            $buckets[(int) \floor(($bucketCount - 1) * $element->getValue() / $M)][] = $element;
        }

        $sorted = [];
        foreach ($buckets as $bucket) {
            $sorted[] = $algo::sort($bucket, SortOrder::ASC);
        }

        return $order === SortOrder::ASC ? \array_merge(...$sorted) : \array_reverse(\array_merge(...$sorted), false);
    }
}
