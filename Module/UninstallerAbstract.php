<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\Application\ApplicationAbstract;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;

/**
 * Uninstaller abstract class.
 *
 * @package phpOMS\Module
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
     * @param ApplicationAbstract $app  Application
     * @param ModuleInfo          $info Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function uninstall(ApplicationAbstract $app, ModuleInfo $info) : void
    {
        self::deactivate($app, $info);
        self::dropTables($app->dbPool, $info);
        self::unregisterFromDatabase($app->dbPool, $info);
    }

    /**
     * Activate after install.
     *
     * @param ApplicationAbstract $app  Application
     * @param ModuleInfo          $info Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    protected static function deactivate(ApplicationAbstract $app, ModuleInfo $info) : void
    {
        if (($path = \realpath(static::PATH)) === false) {
            return; // @codeCoverageIgnore
        }

        /** @var string $classPath */
        $classPath = \substr($path . '/Status', \strlen((string) \realpath(__DIR__ . '/../../')));

        /** @var StatusAbstract $class */
        $class = \strtr($classPath, '/', '\\');
        $class::deactivate($app, $info);
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
        $path = static::PATH . '/Install/db.json';
        if (!\is_file($path)) {
            return;
        }

        $content = \file_get_contents($path);
        if ($content === false) {
            return; // @codeCoverageIgnore
        }

        $definitions = \json_decode($content, true);
        $builder     = new SchemaBuilder($dbPool->get('schema'));

        if (!\is_array($definitions)) {
            return;
        }

        foreach ($definitions as $name => $definition) {
            $builder->dropTable($name);
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
