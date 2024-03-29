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
 * Color class for color operations.
 *
 * @package phpOMS\Utils
 * @license OMS License 2.0
 * @link    https://jingga.app
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
        $i = (255 & $rgb['r']) << 16;
        $i += (255 & $rgb['g']) << 8;
        $i += (255 & $rgb['b']);

        return $i;
    }

    /**
     * Calculate the color distance
     *
     * Important: This is not how humans perceive color differences
     *
     * @param array $rgb1 RGB 1
     * @param array $rgb2 RGB 2
     *
     * @return float
     *
     * @see approximateColorDistance
     *
     * @since 1.0.0
     */
    public static function colorDistance(array $rgb1, array $rgb2) : float
    {
        $r = ($rgb2['r'] - $rgb1['r']);
        $g = ($rgb2['g'] - $rgb1['g']);
        $b = ($rgb2['b'] - $rgb1['b']);

        return \sqrt($r * $r + $g * $g + $b * $b);
    }

    /**
     * Approximate the perceived color distance
     *
     * @param array $rgb1 RGB 1
     * @param array $rgb2 RGB 2
     *
     * @return float
     *
     * @see https://www.compuphase.com/cmetric.htm
     *
     * @since 1.0.0
     */
    public static function approximateColorDistance(array $rgb1, array $rgb2) : float
    {
        $rMean = (int) (($rgb1['r'] + $rgb2['r']) / 2);
        $r = ($rgb2['r'] - $rgb1['r']);
        $g = ($rgb2['g'] - $rgb1['g']);
        $b = ($rgb2['b'] - $rgb1['b']);

        return \sqrt(
            (((512 + $rMean) * $r * $r) >> 8)
            + 4 * $g * $g
            + (((767 - $rMean) * $b * $b) >> 8)
        );
    }
}
