<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS\Algorithm\Sort;
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Algorithm\Sort;

/**
 * CycleSort class.
 *
 * @package    phpOMS\Algorithm\Sort;
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class CycleSort implements SortInterface
{
    public static function sort(array $list, int $order = SortOrder::ASC) : array
    {
        $writes = 0;
        $n      = \count($list);

        if ($n < 2) {
            return $list;
        }

        for ($start = 0; $start < \count($list) - 1; ++$start) {
            $item = $list[$start];

            $pos     = $start;
            $length0 = \count($list);
            for ($i = $start + 1; $i < $length0; ++$i) {
                if ($list[$i]->getValue() < $item->getValue()) {
                    ++$pos;
                }
            }

            if ($pos === $start) {
                continue;
            }

            while ($item->getValue() === $list[$pos]->getValue()) {
                ++$pos;
            }

            $old        = $list[$pos];
            $list[$pos] = $item;
            $item       = $old;
            ++$writes;

            while ($pos !== $start) {
                $pos     = $start;
                $length1 = \count($list);
                for ($i = $start + 1; $i < $length1; ++$i) {
                    if ($list[$i]->getValue() < $item->getValue()) {
                        ++$pos;
                    }
                }

                while ($item->getValue() === $list[$pos]->getValue()) {
                    ++$pos;
                }

                $old        = $list[$pos];
                $list[$pos] = $item;
                $item       = $old;
                ++$writes;
            }
        }

        return $order === SortOrder::ASC ? $list : \array_reverse($list, false);;
    }
}
