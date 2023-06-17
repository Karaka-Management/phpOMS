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
 * CombSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class CombSort implements SortInterface
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
        $gap    = $n;
        $shrink = 1.3;

        if ($n < 2) {
            return $list;
        }

        while (!$sorted) {
            $gap = (int) \floor($gap / $shrink);

            if ($gap < 2) {
                $gap    = 1;
                $sorted = true;
            }

            $i = 0;
            while ($i + $gap < $n) {
                if ($list[$i]->compare($list[$i + $gap], $order)) {
                    $old          = $list[$i];
                    $list[$i]     = $list[$i + 1];
                    $list[$i + 1] = $old;

                    $sorted = false;
                }

                ++$i;
            }
        }

        return $list;
    }
}
