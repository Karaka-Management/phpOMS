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
 * String utils.
 *
 * @package phpOMS\Utils
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Permutation
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
     * Create all permutations.
     *
     * @param array $toPermute data to permute
     * @param array $result    existing permutations
     *
     * @return array<array|string>
     *
     * @since 1.0.0
     */
    public static function permuteAll(array $toPermute, array $result = [], bool $concat = true) : array
    {
        $permutations = [];

        if (empty($toPermute)) {
            $permutations[] = $concat ? \implode('', $result) : $result;
        } else {
            foreach ($toPermute as $key => $val) {
                $newArr   = $toPermute;
                $newres   = $result;
                $newres[] = $val;

                unset($newArr[$key]);

                $permutations = \array_merge($permutations, self::permuteAll($newArr, $newres, $concat));
            }
        }

        return $permutations;
    }

    /**
     * Check if two strings are permutations of each other.
     *
     * @param string $a String a
     * @param string $b String b
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isPermutation(string $a, string $b) : bool
    {
        return \count_chars($a, 1) === \count_chars($b, 1);
    }

    /**
     * Check if a string is a palindrome.
     *
     * @param string $a      String a
     * @param string $filter Characters to filter
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isPalindrome(string $a, string $filter = 'a-zA-Z0-9') : bool
    {
        $a = \strtolower(\preg_replace('/[^' . $filter . ']/', '', $a) ?? '');

        return $a === \strrev($a);
    }

    /**
     * Permute based on transposition key.
     *
     * @param array|string $toPermute To permute
     * @param int[]        $key       Permutation keys
     *
     * @return string|array
     *
     * @throws \OutOfBoundsException This exception is thrown if the permutation key is larger than the data to permute
     *
     * @since 1.0.0
     */
    public static function permuteByKey(string | array $toPermute, array $key) : string | array
    {
        $length = \is_array($toPermute) ? \count($toPermute) : \strlen($toPermute);

        if (\count($key) > $length) {
            throw new \OutOfBoundsException('There must not be more keys than permutation elements.');
        }

        $i = 0;
        foreach ($key as $pos) {
            $temp                = $toPermute[$i];
            $toPermute[$i]       = $toPermute[$pos - 1];
            $toPermute[$pos - 1] = $temp;
            ++$i;
        }

        return $toPermute;
    }
}
