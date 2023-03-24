<?php
/**
 * Karaka
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
 * OddEvenSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class OddEvenSort implements SortInterface
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
        $sorted = false;
        $n      = \count($list);

        if ($n < 2) {
            return $list;
        }

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
