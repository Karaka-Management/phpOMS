<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Algorithm\Sort;
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Sort;

/**
 * Bucketsort class.
 *
 * @package    phpOMS\Algorithm\Sort;
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class BucketSort
{
    public static function sort(array $list, int $bucketCount, string $algo = InsertionSort::class, int $order = SortOrder::ASC) : array
    {
        $buckets = [];
        $M       = $list[0]::max($list);

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
