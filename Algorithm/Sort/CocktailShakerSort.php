<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
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
 * CocktailShakerSort class.
 *
 * @package    phpOMS\Algorithm\Sort;
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class CocktailShakerSort implements SortInterface
{
    public static function sort(array $list, int $order = SortOrder::ASC) : array
    {
        $start = 0;
        $end   = \count($list) - 1;

        while ($start <= $end) {
            $newStart = $end;
            $newEnd   = $start;

            for ($i = $start; $i < $end; ++$i) {
                if ($list[$i]->compare($list[$i + 1], $order)) {
                    $old          = $list[$i];
                    $list[$i]     = $list[$i + 1];
                    $list[$i + 1] = $old;

                    $newEnd = $i;
                }
            }

            $end = $newEnd - 1;

            for ($i = $end; $i >= $start; --$i) {
                if ($list[$i]->compare($list[$i + 1], $order)) {
                    $old          = $list[$i];
                    $list[$i]     = $list[$i + 1];
                    $list[$i + 1] = $old;

                    $newStart = $i;
                }
            }

            $start = $newStart + 1;
        }

        return $list;
    }
}
