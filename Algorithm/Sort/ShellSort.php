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
 * ShellSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 2.2
 * @link    https://jingga.app
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

        for ($i = (int) ($n / 2); $i > 0; $i = (int) ($i / 2)) {
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
