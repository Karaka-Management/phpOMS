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
 * BitonicSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class BitonicSort implements SortInterface
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

        $first  = self::sort(\array_slice($list, 0, (int) ($n / 2)), SortOrder::ASC);
        $second = self::sort(\array_slice($list, (int) ($n / 2)), SortOrder::DESC);

        return self::merge(\array_merge($first, $second), $order);
    }

    /**
     * Splitting, merging and sorting list
     *
     * @param array $list  List to sort
     * @param int   $order Sort order
     *
     * @return array
     *
     * @since 1.0.0
     */
    private static function merge(array $list, int $order) : array
    {
        $n = \count($list);

        if ($n === 1) {
            return $list;
        }

        $dist = $n / 2;
        for ($i = 0; $i < $dist; ++$i) {
            if ($list[$i]->compare($list[$i + $dist], $order)) {
                $old              = $list[$i];
                $list[$i]         = $list[$i + $dist];
                $list[$i + $dist] = $old;
            }
        }

        $first  = self::merge(\array_slice($list, 0, (int) ($n / 2)), $order);
        $second = self::merge(\array_slice($list, (int) ($n / 2)), $order);

        return \array_merge($first, $second);
    }
}
