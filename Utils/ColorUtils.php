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
 * Color class for color operations.
 *
 * @package phpOMS\Utils
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class ColorUtils
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
     * Convert int to rgb
     *
     * @param int $rgbInt Value to convert
     *
     * @return array{r:int, g:int, b:int}
     *
     * @since 1.0.0
     */
    public static function intToRgb(int $rgbInt) : array
    {
        $rgb = ['r' => 0, 'g' => 0, 'b' => 0];

        $rgb['b'] = $rgbInt & 255;
        $rgb['g'] = ($rgbInt >> 8) & 255;
        $rgb['r'] = ($rgbInt >> 16) & 255;

        return $rgb;
    }

    /**
     * Convert rgb to int
     *
     * @param array{r:int, g:int, b:int} $rgb Int rgb array
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function rgbToInt(array $rgb) : int
    {
        $i  = (255 & $rgb['r']) << 16;
        $i += (255 & $rgb['g']) << 8;
        $i += (255 & $rgb['b']);

        return $i;
    }
}
