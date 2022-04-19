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
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Sort;

/**
 * ShellSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class ShellSort implements SortInterface
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
