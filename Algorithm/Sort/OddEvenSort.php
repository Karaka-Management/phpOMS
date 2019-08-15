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
 * OddEvenSort class.
 *
 * @package    phpOMS\Algorithm\Sort;
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class OddEvenSort implements SortInterface
{
    public static function sort(array $list, int $order = SortOrder::ASC) : array
    {
        $sorted = false;
        $n      = \count($list);

        while (!$sorted) {
            $sorted = true;

            for ($i = 1; $i < $n - 1; $i += 2) {
                if ($list[$i]->compare($list[$i + 1], $order)) {
                    $old          = $list[$i];
                    $list[$i]     = $list[$i + 1];
                    $list[$i + 1] = $old;

                    $sorted = false;
                }
            }

            for ($i = 0; $i < $n - 1; $i += 2) {
                if ($list[$i]->compare($list[$i + 1], $order)) {
                    $old          = $list[$i];
                    $list[$i]     = $list[$i + 1];
                    $list[$i + 1] = $old;

                    $sorted = false;
                }
            }
        }

        return $list;
    }
}
