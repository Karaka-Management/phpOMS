<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Utils\RnG
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\RnG;

/**
 * Number generator.
 *
 * @package phpOMS\Utils\RnG
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Number
{
    /**
     * Generate normal distributed random number
     *
     * @param int $min Min value
     * @param int $max Max value
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function normalDistributedRand(int $min, int $max) : int
    {
        $u1 = \mt_rand(1, 100) / 100;
        $u2 = \mt_rand(1, 100) / 100;

        // Box-Muller transform
        $z = \sqrt(-2 * \log($u1)) * \cos(2 * \M_PI * $u2);

        return (int) \max($min, \min($max, \round(($z + 3) / 6 * ($max - $min) + $min)));
    }

    /**
     * Generate exponentially distributed random number
     *
     * For values [0; 100] a lambda of around 0.2 is recommended
     *
     * @param int   $min    Min value
     * @param int   $max    Max value
     * @param float $lambda Lambda
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function exponentialDistributedRand(int $min, int $max, float $lambda) : int
    {
        $u = \mt_rand(1, 100) / 100;

        $randomVariable = -$lambda * \log($u);

        $randomValue = $min + ($max - $min) * $randomVariable;

        return (int) \max($min, \min($max, $randomValue));
    }
}
