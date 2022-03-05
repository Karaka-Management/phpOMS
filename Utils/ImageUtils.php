<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Utils
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Utils;

/**
 * Image utils class.
 *
 * This class provides static helper functionalities for images.
 *
 * @package phpOMS\Utils
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class ImageUtils
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
     * Decode base64 image.
     *
     * @param string $img Encoded image
     *
     * @return string Decoded image
     *
     * @since 1.0.0
     */
    public static function decodeBase64Image(string $img) : string
    {
        $img = \str_replace('data:image/png;base64,', '', $img);
        $img = \str_replace(' ', '+', $img);

        return (string) \base64_decode($img);
    }

    public static function lightness(int $rgb) : float
    {
        $sR = ($rgb >> 16) & 0xFF;
        $sG = ($rgb >> 8) & 0xFF;
        $sB = $rgb & 0xFF;

        return self::lightnessFromRgb($sR, $sG, $sB);
    }

    public static function lightnessFromRgb(int $r, int $g, int $b) : float
    {
        $vR = $r / 255.0;
        $vG = $g / 255.0;
        $vB = $b / 255.0;

        $lR = $vR <= 0.04045 ? $vR / 12.92 : \pow((($vR + 0.055) / 1.055), 2.4);
        $lG = $vG <= 0.04045 ? $vG / 12.92 : \pow((($vG + 0.055) / 1.055), 2.4);
        $lB = $vB <= 0.04045 ? $vB / 12.92 : \pow((($vB + 0.055) / 1.055), 2.4);

        $y     = 0.2126 * $lR + 0.7152 * $lG + 0.0722 * $lB;
        $lStar = $y <= 216.0 / 24389.0 ? $y * 24389.0 / 27.0 : \pow($y, (1 / 3)) * 116.0 - 16.0;

        return $lStar / 100.0;
    }
}
