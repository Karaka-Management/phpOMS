<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Algorithm\Sort;
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Sort;

/**
 * GnomeSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class GnomeSort implements SortInterface
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
            $j = $i;

            while ($j > 0 && $list[$j - 1]->compare($list[$j], $order)) {
                $old          = $list[$j - 1];
                $list[$j - 1] = $list[$j];
                $list[$j]     = $old;

                --$j;
            }
        }

        return $list;
    }
}
