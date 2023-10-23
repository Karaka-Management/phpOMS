<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Math\Functions
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Math\Functions;

use phpOMS\Math\Matrix\Exception\InvalidDimensionException;

/**
 * Algebra functions
 *
 * @package phpOMS\Math\Functions
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Algebra
{
    /**
     * Get the dot product of two arrays
     *
     * @param array $value1 Value 1 is a matrix or a vector
     * @param array $value2 Value 2 is a matrix or vector (cannot be a matrix if value1 is a vector)
     *
     * @return array
     *
     * @throws InvalidDimensionException
     * @throws \InvalidArgumentException
     *
     * @since 1.0.0
     */
    public static function dot(array $value1, array $value2) : int|float|array
    {
        $m1 = \count($value1);
        $n1 = ($isMatrix1 = \is_array($value1[0])) ? \count($value1[0]) : 1;

        $m2 = \count($value2);
        $n2 = ($isMatrix2 = \is_array($value2[0])) ? \count($value2[0]) : 1;

        $result = null;

        if ($isMatrix1 && $isMatrix2) {
            if ($m2 !== $n1) {
                throw new InvalidDimensionException($m2 . 'x' . $n2 . ' not compatible with ' . $m1 . 'x' . $n1);
            }

            $result = [[]];
            for ($i = 0; $i < $m1; ++$i) { // Row of 1
                for ($c = 0; $c < $n2; ++$c) { // Column of 2
                    $temp = 0;

                    for ($j = 0; $j < $m2; ++$j) { // Row of 2
                        $temp += $value1[$i][$j] * $value2[$j][$c];
                    }

                    $result[$i][$c] = $temp;
                }
            }
        } elseif (!$isMatrix1 && !$isMatrix2) {
            if ($m1 !== $m2) {
                throw new InvalidDimensionException($m1 . ' vs. ' . $m2);
            }

            $result = 0;
            for ($i = 0; $i < $m1; ++$i) {
                $result += $value1[$i] * $value2[$i];
            }
        } elseif ($isMatrix1 && !$isMatrix2) {
            $result = [];
            for ($i = 0; $i < $m1; ++$i) { // Row of 1
                $temp = 0;

                for ($c = 0; $c < $m2; ++$c) { // Row of 2
                    $temp += $value1[$i][$c] * $value2[$c];
                }

                $result[$i] = $temp;
            }
        } else {
            throw new \InvalidArgumentException();
        }

        return $result;
    }

    /**
     * Calculate the vector corss product
     *
     * @param array $vector1 First 3 vector
     * @param array $vector2 Second 3 vector
     *
     * @return array<int, int|float>
     *
     * @since 1.0.0
     */
    public static function cross3(array $vector1, array $vector2) : array
    {
        return [
            $vector1[1] * $vector2[2] - $vector1[2] * $vector2[1],
            $vector1[2] * $vector2[0] - $vector1[0] * $vector2[2],
            $vector1[0] * $vector2[1] - $vector1[1] * $vector2[0],
        ];
    }
}
