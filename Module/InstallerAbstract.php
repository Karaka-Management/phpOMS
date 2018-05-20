<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Module
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\DataStorage\Database\DatabaseType;
use phpOMS\DataStorage\Database\Exception\InvalidDatabaseTypeException;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\System\File\Local\Directory;
use phpOMS\System\File\PathException;
use phpOMS\System\File\PermissionException;
use phpOMS\Utils\Parser\Php\ArrayParser;

/**
 * Installer Abstract class.
 *
 * @package    phpOMS\Module
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class InstallerAbstract
{
    /**
     * Register module in database.
     *
     * @param DatabasePool $dbPool Database instance
     * @param InfoManager  $info   Module info
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function registerInDatabase(DatabasePool $dbPool, InfoManager $info) : void
    {
        switch ($dbPool->get()->getType()) {
            case DatabaseType::MYSQL:
                $dbPool->get()->con->beginTransaction();

                $sth = $dbPool->get()->con->prepare(
                    'INSERT INTO `' . $dbPool->get()->prefix . 'module` (`module_id`, `module_theme`, `module_path`, `module_active`, `module_version`) VALUES
                (:internal, :theme, :path, :active, :version);'
                );

                $sth->bindValue(':internal', $info->getInternalName(), \PDO::PARAM_INT);
                $sth->bindValue(':theme', 'Default', \PDO::PARAM_STR);
                $sth->bindValue(':path', $info->getDirectory(), \PDO::PARAM_STR);
                $sth->bindValue(':active', 0, \PDO::PARAM_INT);
                $sth->bindValue(':version', $info->getVersion(), \PDO::PARAM_STR);
                $sth->execute();

                $sth = $dbPool->get()->con->prepare(
                    'INSERT INTO `' . $dbPool->get()->prefix . 'module_load` (`module_load_pid`, `module_load_type`, `module_load_from`, `module_load_for`, `module_load_file`) VALUES
                (:pid, :type, :from, :for, :file);'
                );

                $load = $info->getLoad();
                foreach ($load as $val) {
                    foreach ($val['pid'] as $pid) {
                        $sth->bindValue(':pid', sha1(str_replace('/', '', $pid)), \PDO::PARAM_STR);
                        $sth->bindValue(':type', $val['type'], \PDO::PARAM_INT);
                        $sth->bindValue(':from', $val['from'], \PDO::PARAM_STR);
                        $sth->bindValue(':for', $val['for'], \PDO::PARAM_STR);
                        $sth->bindValue(':file', $val['file'], \PDO::PARAM_STR);

                        $sth->execute();
                    }
                }

                $dbPool->get()->con->commit();
                break;
            default: 
                throw new InvalidDatabaseTypeException($dbPool->get()->getType());
        }
    }

    /**
     * Install module.
     *
     * @param DatabasePool $dbPool Database instance
     * @param InfoManager  $info   Module info
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function install(DatabasePool $dbPool, InfoManager $info) : void
    {
        self::registerInDatabase($dbPool, $info);
        self::initRoutes($info);
        self::initHooks($info);
        self::activate($dbPool, $info);
    }

    /**
     * Activate after install.
     *
     * @param DatabasePool $dbPool Database instance
     * @param InfoManager  $info   Module info
     *
     * @return void
     *
     * @since  1.0.0
     */
    private static function activate(DatabasePool $dbPool, InfoManager $info) : void
    {
        /** @var ActivateAbstract $class */
        $class = '\Modules\\' . $info->getDirectory() . '\Admin\Status';
        $class::activate($dbPool, $info);
    }

    /**
     * Re-init module.
     *
     * @param InfoManager $info Module info
     *
     * @return void
     *
     * @since  1.0.0
     */
    public static function reInit(InfoManager $info) : void
    {
        self::initRoutes($info);
        self::initHooks($info);
    }

    /**
     * Init routes.
     *
     * @param InfoManager $info Module info
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since  1.0.0
     */
    private static function initRoutes(InfoManager $info) : void
    {
        $directories = new Directory(dirname($info->getPath()) . '/Admin/Routes');

        foreach ($directories as $key => $subdir) {
            if ($subdir instanceof Directory) {
                foreach ($subdir as $key2 => $file) {
                    self::installRoutes(__DIR__ . '/../../' . $subdir->getName() . '/' . basename($file->getName(), '.php') . '/Routes.php', $file->getPath());
                }
            }
        }
    }

    /**
     * Install routes.
     *
     * @param string $destRoutePath Destination route path
     * @param string $srcRoutePath  Source route path
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since  1.0.0
     */
    private static function installRoutes(string $destRoutePath, string $srcRoutePath) : void
    {
        if (!file_exists($destRoutePath)) {
            file_put_contents($destRoutePath, '<?php return [];');
        }

        if (!file_exists($srcRoutePath)) {
            return;
        }

        if (!file_exists($destRoutePath)) {
            throw new PathException($destRoutePath);
        }

        if (!is_writable($destRoutePath)) {
            throw new PermissionException($destRoutePath);
        }

        /** @noinspection PhpIncludeInspection */
        $appRoutes = include $destRoutePath;
        /** @noinspection PhpIncludeInspection */
        $moduleRoutes = include $srcRoutePath;

        $appRoutes = array_merge_recursive($appRoutes, $moduleRoutes);

        file_put_contents($destRoutePath, '<?php return ' . ArrayParser::serializeArray($appRoutes) . ';', LOCK_EX);
    }

    /**
     * Init hooks.
     *
     * @param InfoManager $info Module info
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since  1.0.0
     */
    private static function initHooks(InfoManager $info) : void
    {
        $directories = new Directory(dirname($info->getPath()) . '/Admin/Hooks');

        foreach ($directories as $key => $subdir) {
            if ($subdir instanceof Directory) {
                foreach ($subdir as $key2 => $file) {
                    self::installHooks(__DIR__ . '/../../' . $subdir->getName() . '/' . basename($file->getName(), '.php') . '/Hooks.php', $file->getPath());
                }
            }
        }
    }

    /**
     * Install hooks.
     *
     * @param string $destHookPath Destination hook path
     * @param string $srcHookPath  Source hook path
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since  1.0.0
     */
    private static function installHooks(string $destHookPath, string $srcHookPath) : void
    {
        if (!file_exists($destHookPath)) {
            file_put_contents($destHookPath, '<?php return [];');
        }

        if (!file_exists($srcHookPath)) {
            return;
        }

        if (!file_exists($destHookPath)) {
            throw new PathException($destHookPath);
        }

        if (!is_writable($destHookPath)) {
            throw new PermissionException($destHookPath);
        }

        /** @noinspection PhpIncludeInspection */
        $appHooks = include $destHookPath;
        /** @noinspection PhpIncludeInspection */
        $moduleHooks = include $srcHookPath;

        $appHooks = array_merge_recursive($appHooks, $moduleHooks);

        file_put_contents($destHookPath, '<?php return ' . ArrayParser::serializeArray($appHooks) . ';', LOCK_EX);
    }
}
