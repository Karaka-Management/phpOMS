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
 * Bubblesort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class BubbleSort implements SortInterface
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

        do {
            $newN = 0;

            for ($i = 1; $i < $n; ++$i) {
                if ($list[$i - 1]->compare($list[$i], $order)) {
                    $old          = $list[$i - 1];
                    $list[$i - 1] = $list[$i];
                    $list[$i]     = $old;

                    $newN = $i;
                }
            }

            $n = $newN;
        } while ($n > 1);

        return $list;
    }
}
