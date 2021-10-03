<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\Application\ApplicationInfo;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\System\File\Local\Directory;
use phpOMS\System\File\Local\File;
use phpOMS\System\File\PathException;
use phpOMS\System\File\PermissionException;
use phpOMS\Utils\ArrayUtils;
use phpOMS\Utils\Parser\Php\ArrayParser;

/**
 * Status abstract class.
 *
 * This abstraction can be used by modules in order to manipulate their basic status/state.
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class StatusAbstract
{
    /**
     * Path of the file
     *
     * @var string
     * @since 1.0.0
     */
    public const PATH = '';

    /**
     * Deactivate module.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function activate(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        self::activateRoutes($info);
        self::activateHooks($info);
    }

    /**
     * Init routes.
     *
     * @param ModuleInfo           $info    Module info
     * @param null|ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since 1.0.0
     */
    public static function activateRoutes(ModuleInfo $info, ApplicationInfo $appInfo = null) : void
    {
        $directories = new Directory(static::PATH . '/Routes');

        /** @var Directory|File $child */
        foreach ($directories as $child) {
            if ($child instanceof Directory) {
                foreach ($child as $file) {
                    if (!\is_dir(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php'))
                        || ($appInfo !== null && \basename($file->getName(), '.php') !== $appInfo->getInternalName())
                    ) {
                        continue;
                    }

                    self::installRoutesHooks(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php') . '/Routes.php', $file->getPath());
                }
            } elseif ($child instanceof File) {
                if (!\is_dir(__DIR__ . '/../../' . $child->getName())
                    || ($appInfo !== null && \basename($child->getName(), '.php') !== $appInfo->getInternalName())
                ) {
                    continue;
                }

                self::installRoutesHooks(__DIR__ . '/../../' . $child->getName() . '/Routes.php', $child->getPath());
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
     * @since 1.0.0
     */
    protected static function installRoutesHooks(string $destRoutePath, string $srcRoutePath) : void
    {
        if (!\is_file($srcRoutePath)) {
            return;
        }

        if (!\is_file($destRoutePath)) {
            \file_put_contents($destRoutePath, '<?php return [];');
        }

        if (!\is_file($destRoutePath)) {
            throw new PathException($destRoutePath); // @codeCoverageIgnore
        }

        if (!\is_writable($destRoutePath)) {
            throw new PermissionException($destRoutePath); // @codeCoverageIgnore
        }

        /** @noinspection PhpIncludeInspection */
        $appRoutes = include $destRoutePath;
        /** @noinspection PhpIncludeInspection */
        $moduleRoutes = include $srcRoutePath;

        $appRoutes = \array_merge_recursive($appRoutes, $moduleRoutes);

        \file_put_contents($destRoutePath, '<?php return ' . ArrayParser::serializeArray($appRoutes) . ';', \LOCK_EX);
    }

    /**
     * Init hooks.
     *
     * @param ModuleInfo           $info    Module info
     * @param null|ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since 1.0.0
     */
    public static function activateHooks(ModuleInfo $info, ApplicationInfo $appInfo = null) : void
    {
        $directories = new Directory(static::PATH . '/Hooks');

        /** @var Directory|File $child */
        foreach ($directories as $child) {
            if ($child instanceof Directory) {
                foreach ($child as $file) {
                    if (!\is_dir(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php'))
                        || ($appInfo !== null && \basename($file->getName(), '.php') !== $appInfo->getInternalName())
                    ) {
                        continue;
                    }

                    self::installRoutesHooks(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php') . '/Hooks.php', $file->getPath());
                }
            } elseif ($child instanceof File) {
                if (!\is_dir(__DIR__ . '/../../' . $child->getName())
                    || ($appInfo !== null && \basename($child->getName(), '.php') !== $appInfo->getInternalName())
                ) {
                    continue;
                }

                self::installRoutesHooks(__DIR__ . '/../../' . $child->getName() . '/Hooks.php', $child->getPath());
            }
        }
    }

    /**
     * Deactivate module.
     *
     * @param DatabasePool $dbPool Database instance
     * @param ModuleInfo   $info   Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function deactivate(DatabasePool $dbPool, ModuleInfo $info) : void
    {
        self::deactivateRoutes($info);
        self::deactivateHooks($info);
    }

    /**
     * Deactivate routes.
     *
     * @param ModuleInfo           $info    Module info
     * @param null|ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function deactivateRoutes(ModuleInfo $info, ApplicationInfo $appInfo = null) : void
    {
        $directories = new Directory(static::PATH . '/Routes');

        /** @var Directory|File $child */
        foreach ($directories as $child) {
            if ($child instanceof Directory) {
                foreach ($child as $file) {
                    if (!\is_dir(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php'))
                        || ($appInfo !== null && \basename($file->getName(), '.php') !== $appInfo->getInternalName())
                    ) {
                        continue;
                    }

                    self::uninstallRoutesHooks(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php') . '/Routes.php', $file->getPath());
                }
            } elseif ($child instanceof File) {
                if (!\is_dir(__DIR__ . '/../../' . $child->getName())
                    || ($appInfo !== null && \basename($child->getName(), '.php') !== $appInfo->getInternalName())
                ) {
                    continue;
                }

                self::uninstallRoutesHooks(__DIR__ . '/../../' . $child->getName() . '/Routes.php', $child->getPath());
            }
        }
    }

    /**
     * Uninstall routes.
     *
     * @param string $destRoutePath Destination route path
     * @param string $srcRoutePath  Source route path
     *
     * @return void
     *
     * @throws PermissionException
     *
     * @since 1.0.0
     */
    public static function uninstallRoutesHooks(string $destRoutePath, string $srcRoutePath) : void
    {
        if (!\is_file($destRoutePath)
            || !\is_file($srcRoutePath)
        ) {
            return;
        }

        if (!\is_file($destRoutePath)) {
            throw new PathException($destRoutePath); // @codeCoverageIgnore
        }

        if (!\is_writable($destRoutePath)) {
            throw new PermissionException($destRoutePath); // @codeCoverageIgnore
        }

        /** @noinspection PhpIncludeInspection */
        $appRoutes = include $destRoutePath;
        /** @noinspection PhpIncludeInspection */
        $moduleRoutes = include $srcRoutePath;

        $appRoutes = ArrayUtils::array_diff_assoc_recursive($appRoutes, $moduleRoutes);

        \file_put_contents($destRoutePath, '<?php return ' . ArrayParser::serializeArray($appRoutes) . ';', \LOCK_EX);
    }

    /**
     * Deactivate hooks.
     *
     * @param ModuleInfo           $info    Module info
     * @param null|ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function deactivateHooks(ModuleInfo $info, ApplicationInfo $appInfo = null) : void
    {
        $directories = new Directory(static::PATH . '/Hooks');

        /** @var Directory|File $child */
        foreach ($directories as $child) {
            if ($child instanceof Directory) {
                foreach ($child as $file) {
                    if (!\is_dir(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php'))
                        || ($appInfo !== null && \basename($file->getName(), '.php') !== $appInfo->getInternalName())
                    ) {
                        continue;
                    }

                    self::uninstallRoutesHooks(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php') . '/Hooks.php', $file->getPath());
                }
            } elseif ($child instanceof File) {
                if (!\is_dir(__DIR__ . '/../../' . $child->getName())
                    || ($appInfo !== null && \basename($child->getName(), '.php') !== $appInfo->getInternalName())
                ) {
                    continue;
                }

                self::uninstallRoutesHooks(__DIR__ . '/../../' . $child->getName() . '/Hooks.php', $child->getPath());
            }
        }
    }
}
