<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
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
 * CycleSort class.
 *
 * @package phpOMS\Algorithm\Sort;
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class CycleSort implements SortInterface
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

        for ($start = 0; $start < $n - 1; ++$start) {
            $item = $list[$start];

            $pos     = $start;
            $length0 = \count($list);
            for ($i = $start + 1; $i < $length0; ++$i) {
                if (!$list[$i]->compare($item, $order)) {
                    ++$pos;
                }
            }

            if ($pos === $start) {
                continue;
            }

            while ($item->equals($list[$pos])) {
                ++$pos;
            }

            if ($pos !== $start) {
                $old        = $list[$pos];
                $list[$pos] = $item;
                $item       = $old;
            }

            while ($pos !== $start) {
                $pos = $start;

                for ($i = $start + 1; $i < $n; ++$i) {
                    if (!$list[$i]->compare($item, $order)) {
                        ++$pos;
                    }
                }

                while ($item->equals($list[$pos])) {
                    ++$pos;
                }

                if (!$item->equals($list[$pos])) {
                    $old        = $list[$pos];
                    $list[$pos] = $item;
                    $item       = $old;
                }
            }
        }

        return $list;
    }
}
