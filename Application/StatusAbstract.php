<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Application
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Application;

use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\Query\Builder;

/**
 * Status abstract class.
 *
 * This abstraction can be used by modules in order to manipulate their basic status/state.
 *
 * @package phpOMS\Application
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class StatusAbstract
{
    /**
     * Deactivate module in database.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function deactivateInDatabase(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        $query = new Builder($dbPool->get('update'));
        $query->update('app')
            ->sets('app.app_active', ModuleStatus::INACTIVE)
            ->where('app.app_id', '=', $info->getInternalName())
            ->execute();
    }
}
