<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Security
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
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
 * @license OMS License 2.2
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

    /**
     * Checks if a file is "safe"
     *
     * @param string $path File path
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isSafeFile(string $path) : bool
    {
        if (!\str_ends_with($path, '.exe') && !self::isSafeNoneExecutable($path)) {
            return false;
        }

        $tmp = \strtolower($path);
        if (\str_ends_with($tmp, '.csv')) {
            return self::isSafeCsv($path);
        } elseif (\str_ends_with($tmp, '.xml')) {
            return self::isSafeXml($path);
        }

        return true;
    }

    /**
     * Checks if a xml file is "safe"
     *
     * @param string $path File path
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isSafeXml(string $path) : bool
    {
        $maxEntityDepth = 7;
        $xml            = \file_get_contents($path);

        if ($xml === false) {
            return true;
        }

        // Detect injections
        $injectionPatterns = [
            '/<!ENTITY\s+%(\w+)\s+"(.+)">/',
            '/<!ENTITY\s+%(\w+)\s+SYSTEM\s+"(.+)">/',
            '/<!ENTITY\s+(\w+)\s+"(.+)">/',
            '/<!DOCTYPE\s+(.+)\s+SYSTEM\s+"(.+)">/',
        ];

        foreach ($injectionPatterns as $pattern) {
            if (\preg_match($pattern, $xml) === 1) {
                return false;
            }
        }

        $reader = new \XMLReader();

        $reader->XML($xml);
        $reader->setParserProperty(\XMLReader::SUBST_ENTITIES, true);

        $foundBillionLaughsAttack = false;
        $entityCount              = 0;

        while ($reader->read()) {
            if ($reader->nodeType === \XMLReader::ENTITY_REF) {
                ++$entityCount;

                if ($entityCount > $maxEntityDepth) {
                    $foundBillionLaughsAttack = true;
                    break;
                }
            }
        }

        return !$foundBillionLaughsAttack;
    }

    /**
     * Checks if a CSV file is "safe"
     *
     * @param string $path File path
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isSafeCsv(string $path) : bool
    {
        $input = \fopen($path, 'r');
        if (!$input) {
            return true;
        }

        static $specialChars = ['=', '+', '-', '@'];

        while (($row = \fgetcsv($input)) !== false) {
            foreach ($row as &$cell) {
                if (\preg_match('/^[\x00-\x08\x0B\x0C\x0E-\x1F]+/', $cell) === 1) {
                    return false;
                }

                if (\in_array($cell[0] ?? '', $specialChars)) {
                    return false;
                }
            }
        }

        \fclose($input);

        return true;
    }

    /**
     * Checks if a file that shouldn't be executable is not executable
     *
     * @param string $path File path
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isSafeNoneExecutable(string $path) : bool
    {
        $input = \fopen($path, 'r');
        if (!$input) {
            return true;
        }

        static $specialSignatures = [
            "\x4D\x5A",
            "\x7F\x45\x4C\x46",
        ];

        $line = \fgets($input, 256);
        \fclose($input);

        if ($line === false) {
            return true;
        }

        foreach ($specialSignatures as $sig) {
            if (\mb_stripos($line, $sig) !== false) {
                return false;
            }
        }

        return true;
    }
}
