<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Security
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Security;

use phpOMS\System\File\FileUtils;

/**
 * Php code security class.
 *
 * This can be used to guard against certain vulnerabilities
 *
 * @package phpOMS\Security
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Guard
{
    /**
     * Base path for the application
     *
     * @var string
     * @since 1.0.0
     */
    public static string $BASE_PATH = __DIR__ . '/../../';

    /**
     * Make sure a path is part of a base path
     *
     * This can be used to verify if a path goes outside of the allowed path bounds
     *
     * @param string $path Path to check
     * @param string $base Base path
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isSafePath(string $path, string $base = '') : bool
    {
        return \str_starts_with(
            FileUtils::absolute($path),
            FileUtils::absolute(empty($base) ? self::$BASE_PATH : $base)
        );
    }

    /**
     * Remove slashes from a string or array
     *
     * @template T of string|array
     *
     * @param T $data Data to unslash
     *
     * @return (T is string ? string : array)
     *
     * @since 1.0.0
     */
    public static function unslash(string | array $data) : string|array
    {
        if (\is_array($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = \is_string($value) || \is_array($value)
                    ? self::unslash($value)
                    : $value;
            }

            return $result;
        } elseif (\is_string($data)) {
            return \stripslashes($data);
        }

        return $data;
    }

    /**
     * Fix CVE-2016-10033 and CVE-2016-10045 by disallowing potentially unsafe shell characters.
     *
     * @param string $string String to check
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isShellSafe(string $string) : bool
    {
        if (\escapeshellcmd($string) !== $string
            || !\in_array(\escapeshellarg($string), ["'{$string}'", "\"{$string}\""])
        ) {
            return false;
        }

        $length = \strlen($string);

        for ($i = 0; $i < $length; ++$i) {
            $c = $string[$i];

            if (!\ctype_alnum($c) && \strpos('@_-.', $c) === false) {
                return false;
            }
        }

        return true;
    }
}
