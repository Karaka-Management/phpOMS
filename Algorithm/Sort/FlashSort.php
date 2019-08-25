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
 * FlashSort class.
 *
 * @package    phpOMS\Algorithm\Sort;
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
 */
class FlashSort implements SortInterface
{
    public static function sort(array $list, int $order = SortOrder::ASC) : array
    {
        $n = \count($list);
        $i = 0;
        $j = 0;
        $k = 0;
        $m = 0.43 * $n;

        if ($m > 262143) {
            $m = 262143;
        }

        $l = \array_fill(0, $n, 0);
        $anmin = $list[0];
        $anmax = $anmin;
        $nmax = 0;
        $nmove = 0;
        $lk = 0;

        $kmin  = null;
        $kmax  = null;

        // todo: replace >>> with Numeric::uRightShift

        for ($i = 0; (($i += 2) - $n) >>> 31;) {
            if ((($kmax = $list[$i - 1])->getValue() - ($kmin = $list[$i])->getValue()) >>> 31) {
                if (($kmax->getValue() - $anmin->getValue()) >>> 31) {
                    $anmin = $list[$i - 1];
                }

                if (($anmax->getValue() - $kmin->getValue()) >>> 31) {
                    $anmax = $list[$i];
                    $nmax  = $i;
                }
            } else {
                if (($kmin->getValue() - $anmin->getValue()) >>> 31) {
                    $anmin = $list[$i];
                }

                if (($anmax->getValue() - $kmin->getValue()) >>> 31) {
                    $anmax = $list[$i - 1];
                    $nmax  = $i - 1;
                }
            }
        }

        if ((--$i - $n) >>> 31) {
            if ((($k = $list[$i])->getValue() - $anmin->getValue()) >>> 31) {
                $anmin = $list[$i];
            } elseif (($anmax->getValue() - $k->getValue()) >>> 31) {
                $anmax = $list[$i];
                $nmax  = $i;
            }
        }

        if ($anmin->getValue() === $anmax->getValue()) {
            return $list;
        }

        $c1 = (($m - 1) << 13) / ($anmax->getValue() - $anmin->getValue());

        for ($i = -1; (++$i - $n) >>> 31;) {
            ++$l[($c1 * ($list[$i]->getValue() - $anmin->getValue())) >> 13];
        }

        $lk = $l[0];
        for ($k = 0; (++$k - $m) >>> 31;) {
            $lk = ($l[$k] += $lk);
        }

        $hold = $anmax;
        $list[$nmax] = $list[0];
        $list[0] = $hold;

        $flash = null;
        $j = 0;
        $k = ($m - 1);
        $i = ($n - 1);

        while (($nmove - $i) >>> 31) {
            while ($j !== $lk) {
                $k = ($c1 * ($list[(++$j)]->getValue() - $anmin->getValue())) >> 13;
            }

            $flash = $a[$j];
            $lk = $l[$k];

            while ($j !== $lk)
        }
    }
}
