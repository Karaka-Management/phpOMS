<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\DataStorage\Database\DatabasePool;

/**
 * Updater abstract class.
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class UpdaterAbstract
{
    /**
     * Install module.
     *
     * @param DatabasePool $dbPool Database instance
     * @param InfoManager  $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public static function update(DatabasePool $dbPool, InfoManager $info) : void
    {
    }
}
