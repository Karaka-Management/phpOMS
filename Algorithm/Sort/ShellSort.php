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
 * ShellSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class ShellSort implements SortInterface
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

        for ($i = $n / 2; $i > 0; $i = (int) ($i / 2)) {
            for ($j = $i; $j < $n; ++$j) {
                $temp = $list[$j];

                for ($c = $j; $c >= $i && $list[$c - $i]->compare($temp, $order); $c -= $i) {
                    $list[$c] = $list[$c - $i];
                }

                $list[$c] = $temp;
            }
        }

        return $list;
    }
}
