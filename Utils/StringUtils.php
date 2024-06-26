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

use phpOMS\Contract\RenderableInterface;
use phpOMS\Contract\SerializableInterface;

/**
 * String utils class.
 *
 * This class provides static helper functionalities for strings.
 *
 * @package phpOMS\Utils
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 */
final class StringUtils
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
     * @example StringUtils::contains('This string', ['This', 'test']); // true
     *
     * @return bool the function returns true if any of the needles is part of the haystack, false otherwise
     *
     * @since 1.0.0
     */
    public static function contains(string $haystack, array $needles) : bool
    {
        foreach ($needles as $needle) {
            if (\strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Tests if a string ends with a certain string (case sensitive).
     *
     * The validation is done case sensitive. The function takes strings or an array of strings for the validation.
     * In case of an array the function will test if any of the needles is at the end of the haystack string.
     *
     * @param string       $haystack Haystack
     * @param array|string $needles  needles to check if they are at the end of the haystack
     *
     * @example StringUtils::endsWith('Test string', ['test1', 'string']); // true
     *
     * @return bool the function returns true if any of the needles is at the end of the haystack, false otherwise
     *
     * @since 1.0.0
     */
    public static function endsWith(string $haystack, string | array $needles) : bool
    {
        if (\is_string($needles)) {
            $needles = [$needles];
        }

        foreach ($needles as $needle) {
            if ($needle === '' || (($temp = \strlen($haystack) - \strlen($needle)) >= 0 && \strpos($haystack, $needle, $temp) !== false)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Tests if a string starts with a certain string (case sensitive).
     *
     * The validation is done case sensitive. The function takes strings or an array of strings for the validation.
     * In case of an array the function will test if any of the needles is at the beginning of the haystack string.
     *
     * @param string       $haystack Haystack
     * @param array|string $needles  needles to check if they are at the beginning of the haystack
     *
     * @example StringUtils::startsWith('Test string', ['Test', 'something']); // true
     * @example StringUtils::startsWith('Test string', 'string'); // false
     * @example StringUtils::startsWith('Test string', 'Test'); // true
     *
     * @return bool the function returns true if any of the needles is at the beginning of the haystack, false otherwise
     *
     * @since 1.0.0
     */
    public static function startsWith(string $haystack, string | array $needles) : bool
    {
        if (\is_string($needles)) {
            $needles = [$needles];
        }

        foreach ($needles as $needle) {
            if ($needle === '' || \strrpos($haystack, $needle, -\strlen($haystack)) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Count occurrences of character at the beginning of a string.
     *
     * @param string $string    string to analyze
     * @param string $character character to count at the beginning of the string
     *
     * @example StringUtils::countCharacterFromStart('    Test string', ' '); // 4
     * @example StringUtils::countCharacterFromStart('    Test string', 's'); // 0
     *
     * @return int the amount of repeating occurrences at the beginning of the string
     *
     * @since 1.0.0
     */
    public static function countCharacterFromStart(string $string, string $character) : int
    {
        $count  = 0;
        $length = \strlen($string);

        for ($i = 0; $i < $length; ++$i) {
            if ($string[$i] !== $character) {
                break;
            }

            ++$count;
        }

        return $count;
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
    public static function entropy(string $value) : float
    {
        $entropy = 0.0;
        $size    = \strlen($value);

        /** @var array $countChars */
        $countChars = \count_chars($value, 1);

        /** @var int $v */
        foreach ($countChars as $v) {
            $p = $v / $size;
            $entropy -= $p * \log($p) / \log(2);
        }

        return $entropy;
    }

    /**
     * Turn value into string
     *
     * @param mixed $element value to stringify
     * @param mixed $option  Stringify option
     *
     * @return null|string
     *
     * @since 1.0.0
     */
    public static function stringify(mixed $element, mixed $option = null) : ?string
    {
        if ($element instanceof \JsonSerializable || \is_array($element)) {
            if ($option !== null && !\is_int($option)) {
                return null;
            }

            $encoded = \json_encode($element, $option !== null ? $option : 0);

            return $encoded ? $encoded : null;
        } elseif ($element instanceof SerializableInterface) {
            return $element->serialize();
        } elseif (\is_string($element)) {
            return $element;
        } elseif (\is_int($element) || \is_float($element)) {
            return (string) $element;
        } elseif (\is_bool($element)) {
            return $element ? '1' : '0';
        } elseif ($element === null) {
            return null;
        } elseif ($element instanceof \DateTimeInterface) {
            return $element->format('Y-m-d H:i:s');
        } elseif ($element instanceof RenderableInterface) {
            return $element->render();
        } elseif (\is_object($element) && \method_exists($element, '__toString')) {
            return $element->__toString();
        }

        return null;
    }

    /**
     * Create string difference markup
     *
     * @param string $old   Old strings
     * @param string $new   New strings
     * @param string $delim Delim (e.g '' = compare by character, ' ' = compare by words)
     *
     * @return string Markup using <del> and <ins> tags
     *
     * @since 1.0.0
     */
    public static function createDiffMarkup(string $old, string $new, string $delim = '') : string
    {
        $splitOld = empty($delim) ? \str_split($old) : \explode($delim, $old);
        $splitNew = empty($delim) ? \str_split($new) : \explode($delim, $new);

        if ($splitOld === false
            || (empty($old) && !empty($new))
            || (!empty($delim) && \count($splitOld) === 1 && $splitOld[0] === '')
        ) {
            return '<ins>' . $new . '</ins>';
        }

        if ($splitNew === false
            || (!empty($old) && empty($new))
            || (!empty($delim) && \count($splitNew) === 1 && $splitNew[0] === '')
        ) {
            return '<del>' . $old . '</del>';
        }

        $diff = self::computeLCSDiff($splitOld, $splitNew);

        $n      = \count($diff['values']);
        $result = '';
        $mc     = 0;

        for ($i = 0; $i < $n; ++$i) {
            $mc = $diff['mask'][$i];

            $previousMC = $diff['mask'][$i - 1] ?? 0;
            $nextMC     = $diff['mask'][$i + 1] ?? 0;

            switch ($mc) {
                case -1:
                    $result .= ($previousMC === -1 ? '' : '<del>')
                        . $diff['values'][$i]
                        . ($nextMC === -1 ? '' : '</del>')
                        . $delim;

                    break;
                case 1:
                    $result .= ($previousMC === 1 ? '' : '<ins>')
                        . $diff['values'][$i]
                        . ($nextMC === 1 ? '' : '</ins>')
                        . $delim;

                    break;
                default:
                    $result .= $diff['values'][$i] . $delim;
            }
        }

        return \rtrim($result, $delim);
    }

    /**
     * Create LCS diff masks
     *
     * @param string[] $from From/old strings
     * @param string[] $to   To/new strings
     *
     * @return array
     *
     * @throws \Exception This exception is thrown if one of the parameters is empty
     *
     * @since 1.0.0
     */
    private static function computeLCSDiff(array $from, array $to) : array
    {
        $diffValues = [];
        $diffMask   = [];

        $dm = [];
        $n1 = \count($from);
        $n2 = \count($to);

        for ($j = -1; $j < $n2; ++$j) {
            $dm[-1][$j] = 0;
        }

        for ($i = -1; $i < $n1; ++$i) {
            $dm[$i][-1] = 0;
        }

        for ($i = 0; $i < $n1; ++$i) {
            for ($j = 0; $j < $n2; ++$j) {
                $dm[$i][$j] = $from[$i] === $to[$j]
                    ? $dm[$i - 1][$j - 1] + 1
                    : \max($dm[$i - 1][$j], $dm[$i][$j - 1]);
            }
        }

        $i = $n1 - 1;
        $j = $n2 - 1;
        while ($i > -1 || $j > -1) {
            if ($j > -1 && $dm[$i][$j - 1] === $dm[$i][$j]) {
                $diffValues[] = $to[$j];
                $diffMask[]   = 1;
                --$j;

                continue;
            }

            if ($i > -1 && $dm[$i - 1][$j] === $dm[$i][$j]) {
                $diffValues[] = $from[$i];
                $diffMask[]   = -1;
                --$i;

                continue;
            }

            $diffValues[] = $from[$i];
            $diffMask[]   = 0;
            --$i;
            --$j;
        }

        $diffValues = \array_reverse($diffValues);
        $diffMask   = \array_reverse($diffMask);

        return ['values' => $diffValues, 'mask' => $diffMask];
    }

    /**
     * Create a int hash from a string
     *
     * @param string $str String to hash
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function intHash(string $str) : int
    {
        $res = 0;
        $pow = 1;
        $len = \strlen($str);

        for ($i = 0; $i < $len; ++$i) {
            $res = ($res + (\ord($str[$i]) - \ord('a') + 1) * $pow) % (1e9 + 9);
            $pow = ($pow * 31) % (1e9 + 9);
        }

        return (int) $res;
    }

    /**
     * Turn ints into spreadsheet column names
     *
     * @param int $num Column number (1 = A)
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function intToAlphabet(int $num) : string
    {
        if ($num < 0) {
            return '';
        }

        $result = '';
        while ($num >= 0) {
            $remainder = $num % 26;
            $result    = \chr(64 + $remainder) . $result;

            if ($num < 26) {
                break;
            }

            $num = (int) \floor($num / 26);
        }

        return $result;
    }
}
