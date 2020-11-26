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
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;

/**
 * Uninstaller abstract class.
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class UninstallerAbstract
{
    /**
     * Install module.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function uninstall(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        self::dropTables($dbPool, $info);
        self::unregisterFromDatabase($dbPool, $info);
    }

    /**
     * Drop tables of module.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function dropTables(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        $path = \dirname($info->getPath()) . '/Admin/Install/db.json';

        if (!\is_file($path)) {
            return;
        }

        $content = \file_get_contents($path);
        if ($content === false) {
            return; // @codeCoverageIgnore
        }

        $definitions = \json_decode($content, true);
        $builder     = new SchemaBuilder($dbPool->get('schema'));

        foreach ($definitions as $definition) {
            $builder->dropTable($definition['table'] ?? '');
        }

        $builder->execute();
    }

    /**
     * Unregister module from database.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function unregisterFromDatabase(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        $queryLoad = new Builder($dbPool->get('delete'));
        $queryLoad->delete()
            ->from('module_load')
            ->where('module_load_from', '=', $info->getInternalName())
            ->execute();

        $queryModule = new Builder($dbPool->get('delete'));
        $queryModule->delete()
            ->from('module')
            ->where('module_id', '=', $info->getInternalName())
            ->execute();
    }
}
