<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Utils\Converter
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Utils\Converter;

/**
 * File converter.
 *
 * @package phpOMS\Utils\Converter
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class File
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
     * Get file size string.
     *
     * @param int    $bytes     Amount of bytes
     * @param string $decimal   Decimal char
     * @param string $thousands Thousands char
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function byteSizeToString(int $bytes, string $decimal = '.', string $thousands = ',') : string
    {
        if ($bytes < 1000) {
            return $bytes . 'b';
        } elseif ($bytes > 999 && $bytes < 1000000) {
            return \rtrim(\rtrim(\number_format($bytes / 1000, 1, $decimal, $thousands), '0'), $decimal) . 'kb';
        } elseif ($bytes > 999999 && $bytes < 1000000000) {
            return \rtrim(\rtrim(\number_format($bytes / 1000000, 1, $decimal, $thousands), '0'), $decimal) . 'mb';
        } else {
            return \rtrim(\rtrim(\number_format($bytes / 1000000000, 1, $decimal, $thousands), '0'), $decimal) . 'gb';
        }
    }

    /**
     * Get file size string.
     *
     * @param int    $kilobytes Amount of kilobytes
     * @param string $decimal   Decimal char
     * @param string $thousands Thousands char
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function kilobyteSizeToString(int $kilobytes, string $decimal = '.', string $thousands = ',') : string
    {
        if ($kilobytes < 1000) {
            return \rtrim(\rtrim(\number_format($kilobytes, 1, $decimal, $thousands), '0'), $decimal) . 'kb';
        } elseif ($kilobytes > 999 && $kilobytes < 1000000) {
            return \rtrim(\rtrim(\number_format($kilobytes / 1000, 1, $decimal, $thousands), '0'), $decimal) . 'mb';
        } else {
            return \rtrim(\rtrim(\number_format($kilobytes / 1000000, 1, $decimal, $thousands), '0'), $decimal) . 'gb';
        }
    }
}
