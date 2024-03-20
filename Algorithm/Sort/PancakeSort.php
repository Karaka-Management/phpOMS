<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
 * PancakeSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class PancakeSort implements SortInterface
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
