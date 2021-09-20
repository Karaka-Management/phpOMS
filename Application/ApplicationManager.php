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

use phpOMS\System\File\Local\Directory;
use phpOMS\System\File\PathException;

/**
 * Application manager class.
 *
 * General application managing functionality.
 *
 * @package phpOMS\Application
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class ApplicationManager
{
    /**
     * Application instance.
     *
     * @var ApplicationAbstract
     * @since 1.0.0
     */
    private ApplicationAbstract $app;

    /**
     * Applications
     *
     * @var ApplicationInfo[]
     * @since 1.0.0
     */
    private array $applications = [];

    /**
     * Constructor.
     *
     * @param ApplicationAbstract $app Application
     *
     * @since 1.0.0
     */
    public function __construct(ApplicationAbstract $app)
    {
        $this->app = $app;
    }

    /**
     * Load info of application.
     *
     * @param string $appPath Application path
     *
     * @return ApplicationInfo
     *
     * @since 1.0.0
     */
    private function loadInfo(string $appPath) : ApplicationInfo
    {
        $path = \realpath($appPath);

        if ($path === false) {
            throw new PathException($appPath);
        }

        $info = new ApplicationInfo($path);
        $info->load();

        return $info;
    }

    /**
     * Install the application
     *
     * @param string $source      Source of the application
     * @param string $destination Destination of the application
     * @param string $theme       Theme
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function install(string $source, string $destination, string $theme = 'Default') : bool
    {
        $destination = \rtrim($destination, '\\/');
        $source      = \rtrim($source, '/\\');

        if (!\is_dir($source) || \is_dir($destination)) {
            return false;
        }

        if (!\is_file($source . '/Admin/Installer.php')) {
            return false;
        }

        try {
            $info                                         = $this->loadInfo($source . '/info.json');
            $this->applications[$info->getInternalName()] = $info;

            $this->installFiles($source, $destination);
            $this->replacePlaceholder($destination);

            $class = '\\Web\\' . $info->getInternalName() . '\\Admin\\Installer';
            $class::install($this->app->dbPool, $info, $this->app->appSettings);

            return true;
        } catch (\Throwable $t) {
            return false; // @codeCoverageIgnore
        }
    }

    /**
     * Install the files to the destination
     *
     * @param string $source      Source path
     * @param string $destination Destination of the application
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function installFiles(string $source, string $destination) : void
    {
        Directory::copy($source, $destination);
    }

    /**
     * Replace placeholder string (application placeholder name)
     *
     * @param string $destination Destination of the application
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function replacePlaceholder(string $destination) : void
    {
        $files = Directory::list($destination, '*', true);
        foreach ($files as $file) {
            if (!\is_file($destination . '/' . $file)) {
                continue;
            }

            $content = \file_get_contents($destination . '/' . $file);
            if ($content === false) {
                continue; // @codeCoverageIgnore
            }

            \file_put_contents($destination . '/' . $file, \str_replace('{APPNAME}', \basename($destination), $content));
        }
    }
}
