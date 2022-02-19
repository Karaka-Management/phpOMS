<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Algorithm\Sort;
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Sort;

/**
 * InsertionSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class InsertionSort implements SortInterface
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

        for ($i = 1; $i < $n; ++$i) {
            $pivot = $list[$i];
            $j     = $i - 1;

            while ($j >= 0 && $list[$j]->compare($pivot, $order)) {
                $list[$j + 1] = $list[$j];
                --$j;
            }

            $list[$j + 1] = $pivot;
        }

        return $list;
    }
}
