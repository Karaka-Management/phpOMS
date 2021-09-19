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
     * Applications
     *
     * @var ApplicationInfo[]
     * @since 1.0.0
     */
    private array $applications = [];

    /**
     * Constructor.
     *
     * @since. 1.0.0
     */
    public function __construct()
    {
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
        if (!\is_dir($source) || \is_dir($destination)) {
            return false;
        }

        $app                                         = $this->loadInfo(\rtrim($source, '/\\') . '/info.json');
        $this->applications[$app->getInternalName()] = $app;

        $this->installFiles($source, $destination);
        $this->installTheme($destination, $theme);

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

        return true;
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
     * Install the theme
     *
     * @param string $destination Destination of the application
     * @param string $theme       Theme name
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function installTheme(string $destination, string $theme) : void
    {
        if (!\is_dir($path = $destination . '/Themes/' . $theme)) {
            return;
        }

        $dirs = \scandir($path);
        foreach ($dirs as $dir) {
            if (!\is_dir($path. '/' . $dir) || $dir === '.' || $dir === '..') {
                continue;
            }

            if (\is_dir($destination . '/' . $dir)) {
                Directory::delete($destination . '/' . $dir);
            }

            Directory::copy(
                $destination . '/Themes/' . $theme . '/' . $dir,
                $destination . '/' . $dir,
                true
            );
        }
    }
}
