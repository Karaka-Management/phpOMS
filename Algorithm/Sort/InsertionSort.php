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
 * InsertionSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class InsertionSort implements SortInterface
{
    /**
     * {@inheritdoc}
     */
    public static function sort(array $list, int $order = SortOrder::ASC) : array
    {
        $n = \count($list);

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
