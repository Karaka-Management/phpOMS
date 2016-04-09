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
namespace phpOMS\Utils\Converter;

/**
 * File converter.
 *
 * @category   Framework
 * @package    phpOMS\Utils\Converter
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class File
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
     * Get file size string.
     *
     * @param int $bytes Amount of bytes
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function byteSizeToString(int $bytes) : string
    {
        if ($bytes < 1000) {
            return $bytes . 'b';
        } elseif ($bytes > 999 && $bytes < 1000000) {
            return $bytes / 1000 . 'kb';
        } elseif ($bytes > 999999 && $bytes < 1000000000) {
            return $bytes / 1000000 . 'mb';
        } else {
            return $bytes / 1000000000 . 'gb';
        }
    }

    /**
     * Get file size string.
     *
     * @param int $kilobytes Amount of kilobytes
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function kilobyteSizeToString(int $kilobytes) : string
    {
        if ($kilobytes < 1000) {
            return round($kilobytes, 2) . 'kb';
        } elseif ($kilobytes > 999 && $kilobytes < 1000000) {
            return round($kilobytes / 1000, 2) . 'mb';
        } else {
            return round($kilobytes / 1000000, 2) . 'gb';
        }
    }
}
