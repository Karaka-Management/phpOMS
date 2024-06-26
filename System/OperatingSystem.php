<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\System
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\System;

/**
 * Operating system class.
 *
 * @package phpOMS\System
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class OperatingSystem
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
     * Get OS.
     *
     * @return int
     *
     * @since 1.0.0
     */
    public static function getSystem() : int
    {
        if (\stristr(\PHP_OS, 'DAR') !== false) {
            return SystemType::OSX;
        } elseif (\stristr(\PHP_OS, 'WIN') !== false) {
            return SystemType::WIN;
        } elseif (\stristr(\PHP_OS, 'LINUX') !== false) {
            return SystemType::LINUX;
        }

        return SystemType::UNKNOWN;
    }
}
