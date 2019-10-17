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
 * PancakeSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class PancakeSort implements SortInterface
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

        for ($i = $n; $i > 1; --$i) {
            $m = 0;
            for ($j = 0; $j < $i; ++$j) {
                if ($list[$j]->compare($list[$m], $order)) {
                    $m = $j;
                }
            }

            if ($m !== $i - 1) {
                // flip max/min to the beginning
                $start = 0;
                $c     = $m;

                while ($start < $c) {
                    $temp         = $list[$start];
                    $list[$start] = $list[$c];
                    $list[$c]     = $temp;

                    ++$start;
                    --$c;
                }

                // flip reverse array
                $start = 0;
                $c     = $i - 1;

                while ($start < $c) {
                    $temp         = $list[$start];
                    $list[$start] = $list[$c];
                    $list[$c]     = $temp;

                    ++$start;
                    --$c;
                }
            }
        }

        return $list;
    }
}
