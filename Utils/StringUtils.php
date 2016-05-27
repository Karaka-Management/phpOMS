<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Utils;

/**
 * String utils.
 *
 * @category   Framework
 * @package    phpOMS\Utils
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class StringUtils
{

    /**
     * Constructor.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    private function __construct()
    {
    }

    /**
     * String ends with?
     *
     * @param string       $haystack Haystack
     * @param string|array $needles  Needles
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function endsWith(string $haystack, $needles) : bool
    {
        if (is_string($needles)) {
            self::endsWith($haystack, [$needles]);
        }

        foreach ($needles as $needle) {
            if ($needle === '' || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false)) {
                return true;
            }
        }

        return false;
    }

    /**
     * String starts with?
     *
     * @param string       $haystack Haystack
     * @param string|array $needles  Needles
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function startsWith(string $haystack, $needles) : bool
    {
        if (is_string($needles)) {
            self::startsWith($haystack, [$needles]);
        }

        foreach ($needles as $needle) {
            if ($needle === '' || strrpos($haystack, $needle, -strlen($haystack)) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * String starts with?
     *
     * @param string       $haystack Haystack
     * @param string|array $needles  Needles
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function mb_startsWith(string $haystack, $needles) : bool
    {
        if (is_string($needles)) {
            self::mb_startsWith($haystack, [$needles]);
        }

        foreach ($needles as $needle) {
            if ($needle === '' || mb_strrpos($haystack, $needle, -mb_strlen($haystack)) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * String ends with?
     *
     * @param string       $haystack Haystack
     * @param string|array $needles  Needles
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function mb_endsWith(string $haystack, $needles) : bool
    {
        if (is_string($needles)) {
            self::mb_endsWith($haystack, [$needles]);
        }

        foreach ($needles as $needle) {
            if ($needle === '' || (($temp = mb_strlen($haystack) - mb_strlen($needle)) >= 0 && mb_strpos($haystack, $needle, $temp) !== false)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Uppercase first letter.
     *
     * @param string $string String to manipulate
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function mb_ucfirst(string $string) : string
    {
        $strlen    = mb_strlen($string);
        $firstChar = mb_substr($string, 0, 1);
        $then      = mb_substr($string, 1, $strlen - 1);

        return mb_strtoupper($firstChar) . $then;
    }

    /**
     * Lowercase first letter.
     *
     * @param string $string String to manipulate
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function mb_lcfirst(string $string) : string
    {
        $strlen    = mb_strlen($string);
        $firstChar = mb_substr($string, 0, 1);
        $then      = mb_substr($string, 1, $strlen - 1);

        return mb_strtolower($firstChar) . $then;
    }

    /**
     * Trim string.
     *
     * @param string $string   String to manipulate
     * @param string $charlist String to trim
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function mb_trim(string $string, string $charlist = ' ') : string
    {
        if (is_null($charlist)) {
            return trim($string);
        } else {
            $charlist = str_replace('/', '\/', preg_quote($charlist));

            return preg_replace('/(^[' . $charlist . ']+)|([ ' . $charlist . ']+$)/us', '', $string);
        }
    }

    /**
     * Trim right part of string.
     *
     * @param string $string   String to manipulate
     * @param string $charlist String to trim
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function mb_rtrim(string $string, string $charlist = ' ') : string
    {
        if ($charlist === ' ') {
            return rtrim($string);
        } else {
            $charlist = str_replace('/', '\/', preg_quote($charlist));

            return preg_replace('/([' . $charlist . ']+$)/us', '', $string);
        }
    }

    /**
     * Trim left part of string.
     *
     * @param string $string   String to manipulate
     * @param string $charlist String to trim
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function mb_ltrim(string $string, string $charlist = ' ') : string
    {
        if ($charlist === ' ') {
            return ltrim($string);
        } else {
            $charlist = str_replace('/', '\/', preg_quote($charlist));

            return preg_replace('/(^[' . $charlist . ']+)/us', '', $string);
        }
    }
}
