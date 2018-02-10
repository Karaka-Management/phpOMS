<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @package    Framework
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types = 1);

namespace phpOMS\Module;

use phpOMS\DataStorage\Database\DatabasePool;

/**
 * Installer Abstract class.
 *
 * @package    Framework
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class UpdaterAbstract
{

    /**
     * Install module.
     *
     * @param DatabasePool        $dbPool Database instance
     * @param InfoManager $info   Module info
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function update(DatabasePool $dbPool, InfoManager $info) /* : void */
    {
    }
}
