<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Algorithm\Sort;
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Sort;

/**
 * CocktailShakerSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class CocktailShakerSort implements SortInterface
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
        $start = 0;
        $end   = \count($list) - 1;

        if ($end < 1) {
            return $list;
        }

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
