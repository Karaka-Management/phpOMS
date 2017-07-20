<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Version;

/**
 * Version class.
 *
 * Responsible for handling versions
 *
 * @category   Version
 * @package    Framework
 * @author     OMS Development Team <dev@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Version
{

    /**
     * Constructor.
     *
     * @since  1.0.0
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
     * @return int
     *
     * @since  1.0.0
     */
    public static function compare(string $ver1, string $ver2) : int
    {
        return version_compare($ver1, $ver2);
    }
}
