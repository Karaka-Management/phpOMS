<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Image
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\Image;

use phpOMS\Utils\NumericUtils;

/**
 * Kernel - image sharpening/blurring
 *
 * @package phpOMS\Image
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class Kernel
{
    public const KERNEL_RIDGE_1 = [
        [0, -1, 0],
        [-1, 4, -1],
        [0, -1, 0],
    ];

    public const KERNEL_RIDGE_2 = [
        [-1, -1, -1],
        [-1, 8, -1],
        [-1, -1, -1],
    ];

    public const KERNEL_SHARPEN = [
        [0, -1, 0],
        [-1, 5, -1],
        [0, -1, 0],
    ];

    public const KERNEL_BOX_BLUR = [
        [1 / 9, 1 / 9, 1 / 9],
        [1 / 9, 1 / 9, 1 / 9],
        [1 / 9, 1 / 9, 1 / 9],
    ];

    public const KERNEL_GAUSSUAN_BLUR_3 = [
        [1 / 16, 2 / 16, 1 / 16],
        [2 / 16, 4 / 16, 2 / 16],
        [1 / 16, 2 / 16, 1 / 16],
    ];

    public const KERNEL_EMBOSS = [
        [-2, -1, 0],
        [-1, 1, 1],
        [0, 1, 2],
    ];

    public const KERNEL_UNSHARP_MASKING = [
        [-1 / 256,  -4 / 256, -6 / 256, -4 / 256, -1 / 256],
        [-4 / 256,  -16 / 256, -24 / 256, -16 / 256, -4 / 256],
        [-6 / 256,  -24 / 256, 476 / 256, -24 / 256, -6 / 256],
        [-4 / 256,  -16 / 256, -24 / 256, -16 / 256, -4 / 256],
        [-1 / 256,  -4 / 256, -6 / 256, -4 / 256, -1 / 256],
    ];

    /**
     * @see https://en.wikipedia.org/wiki/Kernel_(image_processing)
     * @see https://towardsdatascience.com/image-processing-with-python-blurring-and-sharpening-for-beginners-3bcebec0583a
     * @see https://web.eecs.umich.edu/~jjcorso/t/598F14/files/lecture_0924_filtering.pdf
     */
    public static function convolve(string $inPath, string $outPath, array $kernel) : void
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

        if (\count($kernel) === 3) {
            \imageconvolution($im, $kernel, 1, 0);
        } else {
            // @todo: implement @see https://rosettacode.org/wiki/Image_convolution
            // @todo: not working yet
            $dim  = [\imagesx($im), \imagesy($im)];
            $kDim = [\count($kernel[1]), \count($kernel)]; // @todo: is the order correct? mhh...

            $kWidthRadius  = NumericUtils::uRightShift($kDim[0], 1);
            $kHeightRadius = NumericUtils::uRightShift($kDim[1], 1);

            for ($i = $dim[0] - 1; $i >= 0; --$i) {
                for ($j = $dim[1] - 1; $j >= 0; --$j) {
                    $newPixel = 0;

                    for ($ki = $kDim[0] - 1; $ki >= 0; --$ki) {
                        for ($kj = $kDim[1] - 1; $kj >= 0; --$kj) {
                            $newPixel += $kernel[$ki][$kj] * \imagecolorat($im,
                                \min(\max($i + $ki - $kWidthRadius, 0), $dim[0] - 1),
                                \min(\max($j + $kj - $kHeightRadius, 0), $dim[1] - 1)
                            );
                        }
                    }

                    \imagesetpixel($im, $i, $j, (int) $newPixel);
                }
            }
        }

        if (\strripos($outPath, 'png') !== false) {
            \imagepng($im, $outPath);
        } elseif (\strripos($outPath, 'jpg') !== false || \strripos($outPath, 'jpeg') !== false) {
            \imagejpeg($im, $outPath);
        } else {
            \imagegif($im, $outPath);
        }

        \imagedestroy($im);
    }
}
