<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    TBD
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types = 1);

namespace phpOMS\System;

/**
 * Operating system class.
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class OperatingSystem
{
    /**
     * Get OS.
     *
     * @return int|SystemType
     *
     * @since  1.0.0
     */
    public static function getSystem() : int
    {
        if (stristr(PHP_OS, 'DAR') !== false) {
            return SystemType::OSX;
        } elseif (stristr(PHP_OS, 'WIN') !== false) {
            return SystemType::WIN;
        } elseif (stristr(PHP_OS, 'LINUX') !== false) {
            return SystemType::LINUX;
        } 

        return SystemType::UNKNOWN;
    }
}