<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Module
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Application\ApplicationInfo;
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
 * @license OMS License 2.2
 * @link    https://jingga.app
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
     * Routes.
     *
     * Include consideres the state of the file during script execution.
     * This means setting it to empty has no effect if it was not empty before.
     * There are also other merging bugs that can happen.
     *
     * @var array<string, array>
     * @since 1.0.0
     */
    public static array $routes = [];

    public static array $hooks = [];

    private static array $cache = [];

    /**
     * Deactivate module.
     *
     * @param ApplicationAbstract $app  Application
     * @param ModuleInfo          $info Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function activate(ApplicationAbstract $app, ModuleInfo $info) : void
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
     * @since 1.0.0
     */
    public static function activateRoutes(ModuleInfo $info, ?ApplicationInfo $appInfo = null) : void
    {
        self::activateRoutesHooks($info, 'Routes', $appInfo);
    }

    /**
     * Install routes.
     *
     * @param string $destRoutePath Destination route path
     * @param string $srcRoutePath  Source route path
     *
     * @return void
     *
     * @throws PathException
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

        if (!isset(self::$cache[$destRoutePath])) {
            /** @noinspection PhpIncludeInspection */
            self::$cache[$destRoutePath] = include $destRoutePath;
        }

        /** @noinspection PhpIncludeInspection */
        $moduleCache = include $srcRoutePath;

        self::$cache[$destRoutePath] = \array_merge_recursive(self::$cache[$destRoutePath], $moduleCache);

        \file_put_contents($destRoutePath, '<?php return ' . ArrayParser::serializeArray(self::$cache[$destRoutePath]) . ';', \LOCK_EX);
    }

    /**
     * Init hooks.
     *
     * @param ModuleInfo           $info    Module info
     * @param null|ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function activateHooks(ModuleInfo $info, ?ApplicationInfo $appInfo = null) : void
    {
        self::activateRoutesHooks($info, 'Hooks', $appInfo);
    }

    /**
     * Init routes and hooks.
     *
     * @param ModuleInfo           $info    Module info
     * @param string               $type    Is 'Routes' or 'Hooks'
     * @param null|ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function activateRoutesHooks(ModuleInfo $info, string $type, ?ApplicationInfo $appInfo = null) : void
    {
        self::$cache = $type === 'Routes'
            ? self::$routes
            : self::$hooks;

            $directories = new Directory(static::PATH . '/' . $type);

        /** @var Directory|File $child */
        foreach ($directories as $child) {
            if ($child instanceof Directory) {
                /** @var File $file */
                foreach ($child as $file) {
                    $appName = \basename($file->getName(), '.php');

                    if (!\is_dir(__DIR__ . '/../../' . $child->getName() . '/' . $appName)
                        || ($appInfo !== null && $appName !== $appInfo->getInternalName())
                    ) {
                        continue;
                    }

                    self::installRoutesHooks(
                        __DIR__ . '/../../' . $child->getName() . '/' . $appName . '/' . $type . '.php',
                        $file->getPath()
                    );
                }
            } elseif ($child instanceof File) {
                $appName = \basename($child->getName(), '.php');
                if (!\is_dir(__DIR__ . '/../../' . $child->getName())
                    || ($appInfo !== null && $appName !== $appInfo->getInternalName())
                ) {
                    continue;
                }

                self::installRoutesHooks(
                    __DIR__ . '/../../' . $child->getName() . '/' . $type . '.php',
                    $child->getPath()
                );
            }
        }

        if ($type === 'Routes') {
            self::$routes = self::$cache;
        } else {
            self::$hooks = self::$cache;
        }
    }

    /**
     * Deactivate module.
     *
     * @param ApplicationAbstract $app  Application
     * @param ModuleInfo          $info Module info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function deactivate(ApplicationAbstract $app, ModuleInfo $info) : void
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
    public static function deactivateRoutes(ModuleInfo $info, ?ApplicationInfo $appInfo = null) : void
    {
        self::deactivateRoutesHooks($info, 'Routes', $appInfo);
    }

    /**
     * Deactivate routes and hooks.
     *
     * @param ModuleInfo           $info    Module info
     * @param string               $type    Is 'Routes' or 'Hooks'
     * @param null|ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public static function deactivateRoutesHooks(ModuleInfo $info, string $type, ?ApplicationInfo $appInfo = null) : void
    {
        $directories = new Directory(static::PATH . '/'. $type);

        /** @var Directory|File $child */
        foreach ($directories as $child) {
            if ($child instanceof Directory) {
                /** @var File $file */
                foreach ($child as $file) {
                    if (!\is_dir(__DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php'))
                        || ($appInfo !== null && \basename($file->getName(), '.php') !== $appInfo->getInternalName())
                    ) {
                        continue;
                    }

                    self::uninstallRoutesHooks(
                        __DIR__ . '/../../' . $child->getName() . '/' . \basename($file->getName(), '.php') . '/'. $type . '.php',
                        $file->getPath()
                    );
                }
            } elseif ($child instanceof File) {
                if (!\is_dir(__DIR__ . '/../../' . $child->getName())
                    || ($appInfo !== null && \basename($child->getName(), '.php') !== $appInfo->getInternalName())
                ) {
                    continue;
                }

                self::uninstallRoutesHooks(
                    __DIR__ . '/../../' . $child->getName() . '/'. $type . '.php',
                    $child->getPath()
                );
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
    protected static function uninstallRoutesHooks(string $destRoutePath, string $srcRoutePath) : void
    {
        if (!\is_file($destRoutePath)
            || !\is_file($srcRoutePath)
        ) {
            return;
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
    public static function deactivateHooks(ModuleInfo $info, ?ApplicationInfo $appInfo = null) : void
    {
        self::deactivateRoutesHooks($info, 'Hooks', $appInfo);
    }
}
