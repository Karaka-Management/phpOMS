<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\RnG
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\RnG;

/**
 * Linear congruential generator class
 *
 * @package phpOMS\Utils\RnG
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class LinearCongruentialGenerator
{
    /**
     * BSD seed value.
     *
     * @var int
     * @since 1.0.0
     */
    private static $bsdSeed = 0;

    /**
     * MSVCRT seed value.
     *
     * @var int
     * @since 1.0.0
     */
    private static $msvcrtSeed = 0;

    /**
     * BSD random number
     *
     * @param int $seed Starting seed
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function bsd(int $seed = 0) : int
    {
        if ($seed !== 0) {
            self::$bsdSeed = $seed;
        }

        return self::$bsdSeed = (1103515245 * self::$bsdSeed + 12345) % (1 << 31);
    }

    /**
     * MS random number
     *
     * @param int $seed Starting seed
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function msvcrt(int $seed = 0) : int
    {
        if ($seed !== 0) {
            self::$msvcrtSeed = $seed;
        }

        return (self::$msvcrtSeed = (214013 * self::$msvcrtSeed + 2531011) % (1 << 31)) >> 16;
    }
}
