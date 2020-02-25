<?php

/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS\Application
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */

declare(strict_types=1);

namespace phpOMS\Application;

use phpOMS\Module\ModuleManager;
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
     * Module manager
     *
     * @var ModuleManager
     * @since 1.0.0
     */
    private ModuleManager $moduleManager;

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
     * @param ModuleManager $moduleManager Module manager
     *
     * @since. 1.0.0
     */
    public function __construct(ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
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
    private function loadInfo(string $appPath): ApplicationInfo
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
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function install(string $source, string $destination) : void
    {
        if (!\file_exists($source) || \file_exists($destination)) {
            return;
        }

        $app                                         = $this->loadInfo(\rtrim($source, '/\\') . '/info.json');
        $this->applications[$app->getInternalName()] = $app;

        $this->installFiles($source, $destination);
        $this->installFromModules($app);
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
     * Install routes and hooks from modules for application
     *
     * @param ApplicationInfo $info Application info
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function installFromModules(ApplicationInfo $info) : void
    {
        $installed = $this->moduleManager->getInstalledModules();
        foreach ($installed as $module => $moduleInfo) {
            $this->moduleManager->reInit($module, $info);
        }
    }
}
