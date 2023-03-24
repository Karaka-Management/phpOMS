<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\System\Search
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\System\Search;

/**
 * Basic string search algorithms.
 *
 * @package phpOMS\System\Search
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class StringSearch
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Find pattern in string
     *
     * @param string $pattern Pattern
     * @param string $text    Text to search in
     *
     * @return int Match position
     *
     * @since 1.0.0
     */
    public static function knuthMorrisPrattSearch(string $pattern, string $text) : int
    {
        $patternSize = \strlen($pattern);
        $textSize    = \strlen($text);

        $shift = self::knuthMorrisPrattShift($pattern);

        $i = 1;
        $j = 0;
        while ($i + $patternSize <= $textSize) {
            while ($text[$i + $j] === $pattern[$j]) {
                ++$j;
                if ($j >= $patternSize) {
                    return $i;
                }
            }

            if ($j > 0) {
                $i += $shift[$j - 1];
                $j  = \max($j - $shift[$j - 1], 0);
            } else {
                ++$i;
                $j = 0;
            }
        }

        return -1;
    }

    /**
     * Create shift array
     *
     * @param string $pattern Pattern
     *
     * @return int[]
     *
     * @since 1.0.0
     */
    private static function knuthMorrisPrattShift(string $pattern) : array
    {
        $patternSize = \strlen($pattern);
        $shift       = [];
        $shift[]     = 1;

        $i = 1;
        $j = 0;
        while ($i + $j < $patternSize) {
            if ($pattern[$i + $j] === $pattern[$j]) {
                $shift[$i + $j] = $i;
                ++$j;
            } else {
                if ($j === 0) {
                    $shift[$i] = $i + 1;
                }

                if ($j > 0) {
                    $i += $shift[$j - 1];
                    $j  = \max($j - $shift[$j - 1], 0);
                } else {
                    ++$i;
                    $j = 0;
                }
            }
        }

        return $shift;
    }

    /**
     * Find pattern in string
     *
     * @param string $pattern Pattern
     * @param string $text    Text to search in
     *
     * @return int Match position
     *
     * @since 1.0.0
     */
    public static function boyerMooreHorspoolSimpleSearch(string $pattern, string $text) : int
    {
        $patternSize = \strlen($pattern);
        $textSize    = \strlen($text);

        $i = 0;
        $j = 0;
        while ($i + $patternSize <= $textSize) {
            $j = $patternSize - 1;

            while ($text[$i + $j] === $pattern[$j]) {
                --$j;
                if ($j < 0) {
                    return $i;
                }
            }

            ++$i;
        }

        return -1;
    }

    /**
     * Find pattern in string
     *
     * @param string $pattern Pattern
     * @param string $text    Text to search in
     *
     * @return int Match position
     *
     * @since 1.0.0
     */
    public static function boyerMooreHorspoolSearch(string $pattern, string $text) : int
    {
        $patternSize = \strlen($pattern);
        $textSize    = \strlen($text);

        $shift = [];
        for ($k = 0; $k < 256; ++$k) {
            $shift[$k] = $patternSize;
        }

        for ($k = 0; $k < $patternSize - 1; ++$k) {
            $shift[\ord($pattern[$k])] = $patternSize - 1 - $k;
        }

        $i = 0;
        $j = 0;
        while ($i + $patternSize <= $textSize) {
            $j = $patternSize - 1;

            while ($text[$i + $j] === $pattern[$j]) {
                --$j;
                if ($j < 0) {
                    return $i;
                }
            }

            $i += $shift[\ord($text[$i + $patternSize - 1])];
        }

        return -1;
    }
}
