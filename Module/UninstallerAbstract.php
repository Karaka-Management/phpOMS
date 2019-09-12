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
     * @param InfoManager  $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function uninstall(DatabasePool $dbPool, InfoManager $info) : void
    {
        self::dropTables($dbPool, $info);
    }

    /**
     * Drop tables of module.
     *
     * @param DatabasePool $dbPool Database instance
     * @param InfoManager  $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function dropTables(DatabasePool $dbPool, InfoManager $info) : void
    {
        $path = \dirname($info->getPath()) . '/Admin/Install/db.json';

        if (!\file_exists($path)) {
            return;
        }

        $content = \file_get_contents($path);
        if ($content === false) {
            return;
        }

        $definitions = \json_decode($content, true);

        $builder = new SchemaBuilder($dbPool->get('schema'));
        $builder->prefix($dbPool->get('schema')->prefix);

        foreach ($definitions as $definition) {
            $builder->dropTable($definition['table'] ?? '');
        }

        $builder->execute();
    }
}
