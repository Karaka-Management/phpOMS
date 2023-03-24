<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Application
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Application;

use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;

/**
 * Uninstaller abstract class.
 *
 * @package phpOMS\Application
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class UninstallerAbstract
{
    /**
     * Path of the file
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = '';

    /**
     * Install module.
     *
     * @param DatabasePool    $dbPool Database instance
     * @param ApplicationInfo $info   App info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function uninstall(DatabasePool $dbPool, ApplicationInfo $info) : void
    {
        //self::deactivate($dbPool, $info);
        self::dropTables($dbPool, $info);
        self::unregisterFromDatabase($dbPool, $info);
    }

    /**
     * Activate after install.
     *
     * @param DatabasePool    $dbPool Database instance
     * @param ApplicationInfo $info   App info
     *
     * @return void
     *
     * @since 1.0.0
     */
    /*
    protected static function deactivate(DatabasePool $dbPool, ApplicationInfo $info) : void
    {
        $classPath = \substr(\realpath(static::PATH) . '/Status', \strlen(\realpath(__DIR__ . '/../../')));

        $class = \str_replace('/', '\\', $classPath);
        $class::deactivate($dbPool, $info);
    }*/

    /**
     * Drop tables of app.
     *
     * @param DatabasePool    $dbPool Database instance
     * @param ApplicationInfo $info   App info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function dropTables(DatabasePool $dbPool, ApplicationInfo $info) : void
    {
        $path = static::PATH . '/Install/db.json';
        if (!\is_file($path)) {
            return;
        }

        $content = \file_get_contents($path);
        if ($content === false) {
            return; // @codeCoverageIgnore
        }

        /** @var array<string, string> $definitions */
        $definitions = \json_decode($content, true);
        $builder     = new SchemaBuilder($dbPool->get('schema'));

        foreach ($definitions as $name => $definition) {
            $builder->dropTable($name);
        }

        $builder->execute();
    }

    /**
     * Unregister app from database.
     *
     * @param DatabasePool    $dbPool Database instance
     * @param ApplicationInfo $info   App info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function unregisterFromDatabase(DatabasePool $dbPool, ApplicationInfo $info) : void
    {
        $queryApp = new Builder($dbPool->get('delete'));
        $queryApp->delete()
            ->from('app')
            ->where('app_name', '=', $info->getInternalName())
            ->execute();
    }
}
