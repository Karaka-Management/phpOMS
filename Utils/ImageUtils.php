<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
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
 * @link    https://jingga.app
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

    /**
     * Calculate the lightness from an RGB value as integer
     *
     * @param int $rgb RGB value represented as integer
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function lightness(int $rgb) : float
    {
        $sR = ($rgb >> 16) & 0xFF;
        $sG = ($rgb >> 8) & 0xFF;
        $sB = $rgb & 0xFF;

        return self::lightnessFromRgb($sR, $sG, $sB);
    }

    /**
     * Calculate lightess from rgb values
     *
     * @param int $r Red
     * @param int $g Green
     * @param int $b Blue
     *
     * @return float
     *
     * @since 1.0.0
     */
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

    /**
     * Resize image file
     *
     * @param string $srcPath Source path
     * @param string $dstPath Destination path
     * @param int    $width   New width
     * @param int    $height  New image width
     * @param bool   $crop    Crop image
     *
     * @return void
     * @since 1.0.0
     */
    public static function resize(string $srcPath, string $dstPath, int $width, int $height, bool $crop = false) : void
    {
        /** @var array $imageDim */
        $imageDim = \getimagesize($srcPath);

        if (($imageDim[0] ?? -1) >= $width && ($imageDim[1] ?? -1) >= $height) {
            return;
        }

        $ratio = $imageDim[0] / $imageDim[1];
        if ($crop) {
            if ($imageDim[0] > $imageDim[1]) {
                $imageDim[0] = (int) \ceil($imageDim[0] - ($imageDim[0] * \abs($ratio - $width / $height)));
            } else {
                $imageDim[1] = (int) \ceil($imageDim[1] - ($imageDim[1] * \abs($ratio - $width / $height)));
            }
        } else {
            if ($width / $height > $ratio) {
                $width = (int) ($height * $ratio);
            } else {
                $height = (int) ($width / $ratio);
            }
        }

        $src = null;
        if (\stripos($srcPath, '.jpg') !== false || \stripos($srcPath, '.jpeg') !== false) {
            $src = \imagecreatefromjpeg($srcPath);
        } elseif (\stripos($srcPath, '.png') !== false) {
            $src = \imagecreatefrompng($srcPath);
        } elseif (\stripos($srcPath, '.gif') !== false) {
            $src = \imagecreatefromgif($srcPath);
        }

        $dst = \imagecreatetruecolor($width, $height);

        if ($src === null || $src === false || $dst === null || $dst === false) {
            throw new \InvalidArgumentException();
        }

        \imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $imageDim[0], $imageDim[1]);

        if (\stripos($srcPath, '.jpg') || \stripos($srcPath, '.jpeg')) {
            \imagejpeg($dst, $dstPath);
        } elseif (\stripos($srcPath, '.png')) {
            \imagepng($dst, $dstPath);
        } elseif (\stripos($srcPath, '.gif')) {
            \imagegif($dst, $dstPath);
        }
    }
}
