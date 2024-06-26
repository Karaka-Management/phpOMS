<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils;

/**
 * Array utils.
 *
 * @package phpOMS\Utils
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class NumericUtils
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Unsigned right shift
     *
     * @param int $a Value to shift
     * @param int $b Shift by
     *
     * @return int unsigned int
     *
     * @since 1.0.0
     */
    public static function uRightShift(int $a, int $b) : int
    {
        if ($b >= 32 || $b < -32) {
            $m = (int) ($b / 32);
            $b -= $m * 32;
        }

        if ($b < 0) {
            $b = 32 + $b;
        }

        if ($b == 0) {
            return (($a >> 1) & 0x7fffffff) * 2 + (($a >> $b) & 1);
        }

        if ($a < 0) {
            $a >>= 1;
            $a &= 0x7fffffff;
            $a |= 0x40000000;
            $a >>= $b - 1;
        } else {
            $a >>= $b;
        }

        return $a;
    }
}
