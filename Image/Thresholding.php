<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Image
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Image;

use phpOMS\Utils\ImageUtils;

/**
 * Image thresholding
 *
 * @package phpOMS\Image
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Thresholding
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

        if ($im === false) {
            return;
        }

        $dim = [\imagesx($im), \imagesy($im)];
        $out = \imagecreate($dim[0], $dim[1]);
        if ($out == false) {
            return;
        }

        $intImg = [[]];
        for ($i = 0; $i < $dim[0]; ++$i) {
            $sum = 0.0;

            for ($j = 0; $j < $dim[1]; ++$j) {
                $rgb = \imagecolorat($im, $i, $j);
                if ($rgb === false) {
                    $rgb = 0;
                }

                $sum += ImageUtils::lightness($rgb);

                $intImg[$i][$j] = $i === 0 ? $sum : $intImg[$i - 1][$j] + $sum;
            }
        }

        $s = (int) ($dim[0] / 96.0); // can be changed 8
        $t = 30; // can be changed 15

        $black = \imagecolorallocate($out, 0, 0, 0);
        $white = \imagecolorallocate($out, 255, 255, 255);

        if ($black === false || $white === false) {
            return;
        }

        for ($i = 0; $i < $dim[0]; ++$i) {
            for ($j = 0; $j < $dim[1]; ++$j) {
                $x1 = \max(1, (int) ($i - $s / 2.0));
                $x2 = \min((int) ($i + $s / 2.0), $dim[0] - 1);

                $y1 = \max(1, (int) ($j - $s / 2.0));
                $y2 = \min((int) ($j + $s / 2.0), $dim[1] - 1);

                $count = ($x2 - $x1) * ($y2 - $y1);
                $sum   = $intImg[$x2][$y2] - $intImg[$x2][$y1 - 1] - $intImg[$x1 - 1][$y2] + $intImg[$x1 - 1][$y1 - 1];

                $rgb = \imagecolorat($im, $i, $j);
                if ($rgb === false) {
                    $rgb = 0;
                }

                $brightness = ImageUtils::lightness($rgb);

                $color = $brightness * $count <= ($sum * (100.0 - $t) / 100.0) ? $black : $white;

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
