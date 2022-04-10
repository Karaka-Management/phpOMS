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

/**
 * Skew image
 *
 * @package phpOMS\Image
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 */
final class Skew
{
    /**
     * Automatically rotate image based on projection profile
     *
     * @param string $inPath    Binary input image (black/white)
     * @param string $outPath   Output image
     * @param int    $maxDegree Max degree to consider for rotation
     * @param array  $start     Start coordinates for analysis (e.g. ignore top/border of image)
     * @param array  $end       End coordinates for analysis (e.g. ignore bottom/border of image)
     */
    public static function autoRotate(string $inPath, string $outPath, int $maxDegree = 45, array $start = [], array $end = []) : void
    {
        $im = null;
        if (\strripos($inPath, 'png') !== false) {
            $im = \imagecreatefrompng($inPath);
        } elseif (\strripos($inPath, 'jpg') !== false || \strripos($inPath, 'jpeg') !== false) {
            $im = \imagecreatefromjpeg($inPath);
        } else {
            $im = \imagecreatefromgif($inPath);
        }

        if ($im == false) {
            return;
        }

        $dim = [\imagesx($im), \imagesy($im)];

        $start = [\max(0, $start[0] ?? 0), \max(0, $start[1] ?? 0)];
        $end   = [\min($dim[0], $end[0] ?? $dim[0]), \min($dim[1], $end[1] ?? $dim[1])];

        // Pixelmatrix [width][height]
        // This is important since it makes the hist calculation further down easier
        $imMatrix = [[]];

        $avg = 0;

        for ($i = $start[0]; $i < $end[0]; ++$i) {
            for ($j = $start[1]; $j < $end[1]; ++$j) {
                $imMatrix[$j - $start[1]][$i - $start[0]] = \imagecolorat($im, $i, $j) < 0.5 ? 1 : 0;
                $avg                                     += $imMatrix[$j - $start[1]][$i - $start[0]];
            }
        }

        $avg /= $start[1] - $end[1];

        $dimImMatrix = [\count($imMatrix), \count($imMatrix[0])];
        $bestScore   = 0;
        $bestDegree  = 0;

        for ($i = -$maxDegree; $i < $maxDegree; ++$i) {
            if ($i === 0) {
                continue;
            }

            $rotated = self::rotatePixelMatrix($imMatrix, $dimImMatrix, $i);
            $hist    = [];

            for ($j = 0; $j < $dimImMatrix[0]; ++$j) {
                $hist[$j] = \array_sum($rotated[$j]);

                // cleanup for score function
                // we want to see how many lines are above avg. and how much they are above avg.
                // a different score function may not need this line
                $hist[$j] = $hist[$j] > $avg ? $hist[$j] : 0;
            }

            $score = \array_sum($hist);
            if ($bestScore < $score) {
                $bestScore  = $score;
                $bestDegree = $i;
            }
        }

        $im = \imagerotate($im, $bestDegree, 1);
        if ($im == false) {
            return;
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

    /**
     * Rotate the pixel matrix by a certain degree
     *
     * @param array $pixel Pixel matrix (0 index = y, 1 index = x)
     * @param array $dim   Matrix dimension (0 index = y, 1 index = x)
     * @param int   $deg   Degree to rotate
     *
     * @return array
     *
     * @since 1.0.0
     */
    public static function rotatePixelMatrix(array $pixel, array $dim, int $deg) : array
    {
        $rad = \deg2rad($deg);

        $sin = \sin(-$rad);
        $cos = \cos(-$rad);

        $rotated = [[]];

        for ($i = 0; $i < $dim[0]; ++$i) {
            $cY = $i - $dim[0] / 2.0; // center

            for ($j = 0; $j < $dim[1]; ++$j) {
                $cX = $j - $dim[1] / 2.0; // center

                $x = $cos * $cX + $sin * $cY + $dim[1] / 2.0;
                $y = -$sin * $cX + $cos * $cY + $dim[0] / 2.0;

                $rotated[$i][$j] = self::getNearestValue($pixel, $dim, $x, $y);
            }
        }

        return $rotated;
    }

    /**
     * Find the closes pixel based on floating points
     *
     * @param array $pixel Pixel matrix (0 index = y, 1 index = x)
     * @param array $dim   Matrix dimension (0 index = y, 1 index = x)
     * @param float $x     X coordinate
     * @param float $y     Y coordinate
     *
     * @return int
     *
     * @since 1.0.0
     */
    private static function getNearestValue(array $pixel, array $dim, float $x, float $y) : int
    {
        $xLow  = \min((int) $x, $dim[1] - 1);
        $xHigh = \min((int) \ceil($x), $dim[1] - 1);

        $yLow  = \min((int) $y, $dim[0] - 1);
        $yHigh = \min((int) \ceil($y), $dim[0] - 1);

        $points = [
            [$xLow, $yLow],
            [$xLow, $yHigh],
            [$xHigh, $yLow],
            [$xHigh, $yHigh],
        ];

        $minDistance = \PHP_FLOAT_MAX;
        $minValue    = 0;

        foreach ($points as $point) {
            $distance = ($point[0] - $x) * ($point[0] - $x) + ($point[1] - $y) * ($point[1] - $y);

            if ($distance < $minDistance) {
                $minDistance = $distance;

                $minValue = $point[0] >= 0 && $point[0] < $dim[0] && $point[1] >= 0 && $point[1] < $dim[1]
                    ? $pixel[$point[1]][$point[0]]
                    : 0;
            }
        }

        return $minValue;
    }
}
