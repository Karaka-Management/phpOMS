<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Utils
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils;

/**
 * Array utils.
 *
 * @package phpOMS\Utils
 * @license OMS License 1.0
 * @link    https://orange-management.org
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
        if ($b === 0) {
            return $a;
        }

        return ($a >> $b) & ~(1 << (8 * \PHP_INT_SIZE - 1) >> ($b - 1));
    }
}
