<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Utils
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils;

use phpOMS\System\CharsetType;

/**
 * String utils class.
 *
 * This class provides static helper functionalities for strings.
 *
 * @package phpOMS\Utils
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
final class MbStringUtils
{
    /**
     * Constructor.
     *
     * This class is purely static and is preventing any initialization
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    /**
     * Check if a string contains any of the provided needles (case sensitive).
     *
     * The validation is done case sensitive.
     *
     * @param string   $haystack Haystack
     * @param string[] $needles  Needles to check if any of them are part of the haystack
     *
     * @example MbStringUtils::mb_contains('This string', ['This', 'test']); // true
     *
     * @return bool the function returns true if any of the needles is part of the haystack, false otherwise
     *
     * @since 1.0.0
     */
    public static function mb_contains(string $haystack, array $needles) : bool
    {
        foreach ($needles as $needle) {
            if (\mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Tests if a multi byte string starts with a certain string (case sensitive).
     *
     * The validation is done case sensitive. The function takes strings or an array of strings for the validation.
     * In case of an array the function will test if any of the needles is at the beginning of the haystack string.
     *
     * @param string       $haystack Haystack
     * @param array|string $needles  needles to check if they are at the beginning of the haystack
     *
     * @return bool the function returns true if any of the needles is at the beginning of the haystack, false otherwise
     *
     * @since 1.0.0
     */
    public static function mb_startsWith(string $haystack, $needles) : bool
    {
        if (\is_string($needles)) {
            $needles = [$needles];
        }

        foreach ($needles as $needle) {
            if ($needle === '' || \mb_strrpos($haystack, $needle, -\mb_strlen($haystack)) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Tests if a multi byte string ends with a certain string (case sensitive).
     *
     * The validation is done case sensitive. The function takes strings or an array of strings for the validation.
     * In case of an array the function will test if any of the needles is at the end of the haystack string.
     *
     * @param string       $haystack Haystack
     * @param array|string $needles  needles to check if they are at the end of the haystack
     *
     * @example StringUtils::endsWith('Test string', ['test1', 'string']); // true
     * @example StringUtils::endsWith('Test string', 'string'); // true
     * @example StringUtils::endsWith('Test string', String); // false
     *
     * @return bool the function returns true if any of the needles is at the end of the haystack, false otherwise
     *
     * @since 1.0.0
     */
    public static function mb_endsWith(string $haystack, string|array $needles) : bool
    {
        if (\is_string($needles)) {
            $needles = [$needles];
        }

        foreach ($needles as $needle) {
            if ($needle === '' || (($temp = \mb_strlen($haystack) - \mb_strlen($needle)) >= 0 && \mb_strpos($haystack, $needle, $temp) !== false)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Makes first letter of a multi byte string upper case.
     *
     * @param string $string string to upper case first letter
     *
     * @return string multi byte string with first character as upper case
     *
     * @since 1.0.0
     */
    public static function mb_ucfirst(string $string) : string
    {
        $strlen    = \mb_strlen($string);
        $firstChar = \mb_substr($string, 0, 1);
        $then      = \mb_substr($string, 1, $strlen - 1);

        return \mb_strtoupper($firstChar) . $then;
    }

    /**
     * Makes first letter of a multi byte string lower case.
     *
     * @param string $string string to lower case first letter
     *
     * @return string multi byte string with first character as lower case
     *
     * @since 1.0.0
     */
    public static function mb_lcfirst(string $string) : string
    {
        $strlen    = \mb_strlen($string);
        $firstChar = \mb_substr($string, 0, 1);
        $then      = \mb_substr($string, 1, $strlen - 1);

        return \mb_strtolower($firstChar) . $then;
    }

    /**
     * Trim multi byte characters from a multi byte string.
     *
     * @param string $string   multi byte string to trim multi byte characters from
     * @param string $charlist Multi byte character list used for trimming
     *
     * @return string trimmed multi byte string
     *
     * @since 1.0.0
     */
    public static function mb_trim(string $string, string $charlist = ' ') : string
    {
        if ($charlist === ' ') {
            return \trim($string);
        } else {
            $charlist = \str_replace('/', '\/', \preg_quote($charlist));

            return \preg_replace('/(^[' . $charlist . ']+)|([ ' . $charlist . ']+$)/us', '', $string) ?? '';
        }
    }

    /**
     * Trim multi byte characters from the right of a multi byte string.
     *
     * @param string $string   multi byte string to trim multi byte characters from
     * @param string $charlist Multi byte character list used for trimming
     *
     * @return string trimmed multi byte string
     *
     * @since 1.0.0
     */
    public static function mb_rtrim(string $string, string $charlist = ' ') : string
    {
        if ($charlist === ' ') {
            return \rtrim($string);
        } else {
            $charlist = \str_replace('/', '\/', \preg_quote($charlist));

            return \preg_replace('/([' . $charlist . ']+$)/us', '', $string) ?? '';
        }
    }

    /**
     * Trim multi byte characters from the left of a multi byte string.
     *
     * @param string $string   multi byte string to trim multi byte characters from
     * @param string $charlist Multi byte character list used for trimming
     *
     * @return string trimmed multi byte string
     *
     * @since 1.0.0
     */
    public static function mb_ltrim(string $string, string $charlist = ' ') : string
    {
        if ($charlist === ' ') {
            return \ltrim($string);
        } else {
            $charlist = \str_replace('/', '\/', \preg_quote($charlist));

            return \preg_replace('/(^[' . $charlist . ']+)/us', '', $string) ?? '';
        }
    }

    /**
     * Calculate string entropy
     *
     * @param string $value string to analyze
     *
     * @return float
     *
     * @since 1.0.0
     */
    public static function mb_entropy(string $value) : float
    {
        $entropy    = 0.0;
        $size       = \mb_strlen($value);
        $countChars = self::mb_count_chars($value);

        foreach ($countChars as $v) {
            $p        = $v / $size;
            $entropy -= $p * \log($p) / \log(2);
        }

        return $entropy;
    }

    /**
     * Count chars of utf-8 string.
     *
     * @param string $input string to count chars
     *
     * @return array<string, int>
     *
     * @since 1.0.0
     */
    public static function mb_count_chars(string $input) : array
    {
        $l      = \mb_strlen($input, 'UTF-8');
        $unique = [];

        for ($i = 0; $i < $l; ++$i) {
            $char = \mb_substr($input, $i, 1, 'UTF-8');

            if (!\array_key_exists($char, $unique)) {
                $unique[$char] = 0;
            }

            ++$unique[$char];
        }

        return $unique;
    }

    /**
     * Get the utf-8 boundary of a string
     *
     * @param string $text   QP text to search for utf-8 boundary
     * @param int    $length Last character boundary prior to this length
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function utf8CharBoundary(string $text, int $length = 0) : int
    {
        $reset = 3;

        do {
            $lastChunk  = \substr($text, $length - $reset, $reset);
            $encodedPos = \strpos($lastChunk, '=');

            if ($encodedPos === false) {
                break; // @codeCoverageIgnore
            }

            $hex = \substr($text, $length - $reset + $encodedPos + 1, 2);
            $dec = \hexdec($hex);

            if ($dec < 128) {
                if ($encodedPos > 0) {
                    $length -= $reset - $encodedPos;
                }

                break;
            } elseif ($dec >= 192) {
                $length -= $reset - $encodedPos;
                break;
            } else { /* $dec < 192 */
                $reset += 3;
            }
        } while (true);

        return $length;
    }

    /**
     * Test if a string has multibytes
     *
     * @param string $text    Text to check
     * @param string $charset Charset to check
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function hasMultiBytes(string $text, string $charset = CharsetType::UTF_8) : bool
    {
        return \strlen($text) > \mb_strlen($text, $charset);
    }
}
