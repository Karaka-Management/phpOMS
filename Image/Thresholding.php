<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Image
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Image;

use phpOMS\Utils\ImageUtils;

/**
 * Image thresholding
 *
 * @package phpOMS\Image
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class Thresholding
{
    /**
     * Perform integral thresholding
     *
     * @param string $inPath  Image path to process
     * @param string $outPath Output path to store the processed image
     *
     * @return void
     *
     * @see https://people.scs.carleton.ca/~roth/iit-publications-iti/docs/gerh-50002.pdf
     * @see http://citeseerx.ist.psu.edu/viewdoc/download?doi=10.1.1.817.6856&rep=rep1&type=pdf
     *
     * @since 1.0.0
     */
    public static function integralThresholding(string $inPath, string $outPath) : void
    {
        $im = null;
        if (\strripos($inPath, 'png') !== false) {
            $im = \imagecreatefrompng($inPath);
        } elseif (\strripos($inPath, 'jpg') !== false || \strripos($inPath, 'jpeg') !== false) {
            $im = \imagecreatefromjpeg($inPath);
        } else {
            $im = \imagecreatefromgif($inPath);
        }

        $dim = [\imagesx($im), \imagesy($im)];
        $out = \imagecreate($dim[0], $dim[1]);

        $intImg = [[]];

        $s = (int) $dim[0] / 96; // can be changed 8
        $t = 30; // can be changed 15

        for ($i = 0; $i < $dim[0]; ++$i) {
            $sum = 0;

            for ($j = 0; $j < $dim[1]; ++$j) {
                $rgb = \imagecolorat($im, $i, $j);
                $sum += ImageUtils::lightness($rgb);

                $intImg[$i][$j] = $i === 0 ? $sum : $intImg[$i - 1][$j] + $sum;
            }
        }

        $black = \imagecolorallocate($out, 0, 0, 0);
        $white = \imagecolorallocate($out, 255, 255, 255);

        for ($i = 0; $i < $dim[0]; ++$i) {
            for ($j = 0; $j < $dim[1]; ++$j) {
                $x1 = \max(1, (int) ($i - $s / 2));
                $x2 = \min((int) ($i + $s / 2), $dim[0] - 1);

                $y1 = \max(1, (int) ($j - $s / 2));
                $y2 = \min((int) ($j + $s / 2), $dim[1] - 1);

                $count = ($x2 - $x1) * ($y2 - $y1);
                $sum   = $intImg[$x2][$y2] - $intImg[$x2][$y1 - 1] - $intImg[$x1 - 1][$y2] + $intImg[$x1 - 1][$y1 - 1];

                $rgb        = \imagecolorat($im, $i, $j);
                $brightness = ImageUtils::lightness($rgb);

                $color = $brightness * $count <= ($sum * (100 - $t) / 100) ? $black : $white;

                \imagesetpixel($out, $i, $j, $color);
            }
        }

        if (\strripos($outPath, 'png') !== false) {
            \imagepng($out, $outPath);
        } elseif (\strripos($outPath, 'jpg') !== false || \strripos($outPath, 'jpeg') !== false) {
            \imagejpeg($out, $outPath);
        } else {
            \imagegif($out, $outPath);
        }

        \imagedestroy($im);
        \imagedestroy($out);
    }
}
