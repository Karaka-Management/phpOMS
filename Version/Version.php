<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Version
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Version;

/**
 * Version class.
 *
 * Responsible for handling versions
 *
 * @package phpOMS\Version
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
final class Version
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
     * Comparing two versions.
     *
     * @param string $ver1 Version
     * @param string $ver2 Version
     *
     * @return int Returns the version comparison (0 = equals; -1 = lower; 1 = higher)
     *
     * @since 1.0.0
     */
    public static function compare(string $ver1, string $ver2) : int
    {
        return \version_compare($ver1, $ver2);
    }
}
