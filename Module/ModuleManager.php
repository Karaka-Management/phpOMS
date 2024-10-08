<?php
/**
 * Jingga
 *
 * PHP Version 8.2
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
use phpOMS\Application\ApplicationInfo;
use phpOMS\Autoloader;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Message\RequestAbstract;
use phpOMS\Module\Exception\InvalidModuleException;

/**
 * Module manager class.
 *
 * General module functionality such as listings and initialization.
 *
 * @package phpOMS\Module
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @todo Implement a strategy for managing optional modules (e.g., comment module within the news module).
 *      Previously, modifications to the Mapper were made (e.g., comments were removed) when the comment module was installed.
 *      However, this approach is no longer viable. One potential solution is to introduce a separate Mapper
 *      that is dynamically replaced if the comment module is installed.
 *      Instead of replacing the entire file, a differential approach should be adopted, where only the ADDED lines are merged.
 *      Consideration must be given to uninstallation scenarios, as determining precisely what to remove is currently problematic.
 *      https://github.com/Karaka-Management/Karaka/issues/155
 */
final class ModuleManager
{
    /**
     * All modules that are running on this uri.
     *
     * @var array<string, array<string, \phpOMS\Module\ModuleAbstract>>
     * @since 1.0.0
     */
    private array $running = [];

    /**
     * Application instance.
     *
     * @var ApplicationAbstract
     * @since 1.0.0
     */
    private ApplicationAbstract $app;

    /**
     * Installed modules.
     *
     * @var array<string, ModuleInfo>
     * @since 1.0.0
     */
    private array $installed = [];

    /**
     * All active modules (on all pages not just the ones that are running now).
     *
     * @var array<string, array>
     * @since 1.0.0
     */
    private array $active = [];

    /**
     * Module path.
     *
     * @var string
     * @since 1.0.0
     */
    private string $modulePath;

    /**
     * All modules in the module directory.
     *
     * @var array<string, ModuleInfo>
     * @since 1.0.0
     */
    private array $all = [];

    /**
     * To load based on request uri.
     *
     * @var array<string, array>
     * @since 1.0.0
     */
    private array $uriLoad = [];

    /**
     * Constructor.
     *
     * @param ApplicationAbstract $app        Application
     * @param string              $modulePath Path to modules (must end with '/')
     *
     * @since 1.0.0
     */
    public function __construct(ApplicationAbstract $app, string $modulePath = '')
    {
        $this->app        = $app;
        $this->modulePath = $modulePath;
    }

    /**
     * Get language files.
     *
     * @param RequestAbstract $request Request
     * @param null|string     $app     App name
     *
     * @return string[]
     *
     * @since 1.0.0
     */
    public function getLanguageFiles(RequestAbstract $request, ?string $app = null) : array
    {
        $files = $this->getUriLoad($request);
        if (!isset($files['5'])) {
            return [];
        }

        $lang = [];
        foreach ($files['5'] as $module) {
            $lang[] = '/Modules/'
                . $module['module_load_from']
                . '/Theme/'
                . ($app ?? $this->app->appName)
                . '/Lang/'
                . $module['module_load_file'];
        }

        return $lang;
    }

    /**
     * Get modules that run on this page.
     *
     * @param RequestAbstract $request Request
     *
     * @return array<int|string, array>
     *
     * @since 1.0.0
     */
    public function getUriLoad(RequestAbstract $request) : array
    {
        if (empty($this->uriLoad)) {
            $uriHash = $request->getHash();

            $query = new Builder($this->app->dbPool->get('select'));
            $sth   = $query->select('module_load.module_load_type', 'module_load.*')
                ->from('module_load')
                ->innerJoin('module')
                    ->on('module_load.module_load_from', '=', 'module.module_id')
                    ->orOn('module_load.module_load_for', '=', 'module.module_id')
                ->whereIn('module_load.module_load_pid', $uriHash)
                ->andWhere('module.module_status', '=', ModuleStatus::ACTIVE)
                ->execute();

            if ($sth === null) {
                return [];
            }

            $this->uriLoad = $sth->fetchAll(\PDO::FETCH_GROUP);
        }

        return $this->uriLoad;
    }

    /**
     * Get all installed modules that are active (not just on this uri).
     *
     * @param bool $useCache Use Cache or load new
     *
     * @return array<string, array>
     *
     * @since 1.0.0
     */
    public function getActiveModules(bool $useCache = true) : array
    {
        if (empty($this->active) || !$useCache) {
            $query = new Builder($this->app->dbPool->get('select'));
            $sth   = $query->select('module.module_path')
                ->from('module')
                ->where('module.module_status', '=', ModuleStatus::ACTIVE)
                ->execute();

            if ($sth === null) {
                return [];
            }

            $active = $sth->fetchAll(\PDO::FETCH_COLUMN);

            foreach ($active as $module) {
                $path = $this->modulePath . $module . '/info.json';

                if (!\is_file($path)) {
                    continue;
                }

                $content = \file_get_contents($path);

                $json = \json_decode($content === false ? '[]' : $content, true);
                if (!\is_array($json)) {
                    return $this->active;
                }

                /** @var array{name:array{internal:string}} $json */
                $this->active[$json['name']['internal']] = $json;
            }
        }

        return $this->active;
    }

    /**
     * Is module installed
     *
     * @param string $module Module name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isInstalled(string $module) : bool
    {
        return isset($this->getInstalledModules(false)[$module]);
    }

    /**
     * Is module active
     *
     * @param string $module Module name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isActive(string $module) : bool
    {
        return isset($this->getActiveModules(false)[$module]);
    }

    /**
     * Is module active
     *
     * @param string      $module  Module name
     * @param null|string $ctlName Controller name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isRunning(string $module, ?string $ctlName = null) : bool
    {
        $name = '\\Modules\\' . $module . '\\Controller\\' . ($ctlName ?? $this->app->appName) . 'Controller';

        return isset($this->running[$module][$name]);
    }

    /**
     * Get all modules in the module directory.
     *
     * @return array<string, ModuleInfo>
     *
     * @since 1.0.0
     */
    public function getAllModules() : array
    {
        if (empty($this->all)) {
            \chdir($this->modulePath);
            $files = \glob('*', \GLOB_ONLYDIR);

            if ($files === false) {
                return $this->all; // @codeCoverageIgnore
            }

            $c = \count($files);
            for ($i = 0; $i < $c; ++$i) {
                $info = $this->loadInfo($files[$i]);

                if ($info !== null) {
                    $this->all[$files[$i]] = $info;
                }
            }
        }

        return $this->all;
    }

    /**
     * Get modules that are available from official resources.
     *
     * @return array
     *
     * @since 1.0.0
     */
    /*
    public function getAvailableModules() : array
    {
        return [];
    }
    */

    /**
     * Get all installed modules.
     *
     * @param bool $useCache Use Cache
     *
     * @return array<string, ModuleInfo>
     *
     * @since 1.0.0
     */
    public function getInstalledModules(bool $useCache = true) : array
    {
        if (empty($this->installed) || !$useCache) {
            $query = new Builder($this->app->dbPool->get());
            $sth   = $query->select('module.module_path')
                ->from('module')
                ->where('module_status', '!=', ModuleStatus::AVAILABLE)
                ->execute();

            if ($sth === null) {
                return $this->installed;
            }

            /** @var string[] $installed */
            $installed = $sth->fetchAll(\PDO::FETCH_COLUMN);

            foreach ($installed as $module) {
                $info = $this->loadInfo($module);

                if ($info !== null) {
                    $this->installed[$module] = $info;
                }
            }
        }

        return $this->installed;
    }

    /**
     * Load info of module.
     *
     * @param string $module Module name
     *
     * @return null|ModuleInfo
     *
     * @since 1.0.0
     */
    public function loadInfo(string $module) : ?ModuleInfo
    {
        $path = \realpath($this->modulePath . $module . '/info.json');
        if ($path === false) {
            return null;
        }

        $info = new ModuleInfo($path);
        $info->load();

        return $info;
    }

    /**
     * Deactivate module.
     *
     * @param string $module Module name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function deactivate(string $module) : bool
    {
        $installed = $this->getInstalledModules(false);
        if (!isset($installed[$module])) {
            return false;
        }

        try {
            $info = $this->loadInfo($module);
            if ($info === null) {
                return false; // @codeCoverageIgnore
            }

            $this->deactivateModule($info);

            return true;
        } catch (\Exception $_) {
            return false; // @codeCoverageIgnore
        }
    }

    /**
     * Deactivate module.
     *
     * @param ModuleInfo $info Module info
     *
     * @return void
     *
     * @throws InvalidModuleException Throws this exception in case the deactiviation doesn't exist
     *
     * @since 1.0.0
     */
    private function deactivateModule(ModuleInfo $info) : void
    {
        $class = '\\Modules\\' . $info->getDirectory() . '\\Admin\\Status';
        if (!Autoloader::exists($class)) {
            throw new InvalidModuleException($info->getDirectory());
        }

        /** @var $class DeactivateAbstract */
        $class::deactivate($this->app, $info);
    }

    /**
     * Deactivate module.
     *
     * @param string $module Module name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function activate(string $module) : bool
    {
        $installed = $this->getInstalledModules(false);
        if (!isset($installed[$module])) {
            return false;
        }

        try {
            $info = $this->loadInfo($module);
            if ($info === null) {
                return false; // @codeCoverageIgnore
            }

            $this->activateModule($info);

            return true;
        } catch (\Exception $_) {
            return false; // @codeCoverageIgnore
        }
    }

    /**
     * Activate module.
     *
     * @param ModuleInfo $info Module info
     *
     * @return void
     *
     * @throws InvalidModuleException Throws this exception in case the activation doesn't exist
     *
     * @since 1.0.0
     */
    private function activateModule(ModuleInfo $info) : void
    {
        $class = '\\Modules\\' . $info->getDirectory() . '\\Admin\\Status';
        if (!Autoloader::exists($class)) {
            throw new InvalidModuleException($info->getDirectory());
        }

        /** @var $class ActivateAbstract */
        $class::activate($this->app, $info);
    }

    /**
     * Re-init module.
     *
     * @param string          $module  Module name
     * @param ApplicationInfo $appInfo Application info
     *
     * @return void
     *
     * @throws InvalidModuleException Throws this exception in case the installer doesn't exist
     *
     * @since 1.0.0
     */
    public function reInit(string $module, ?ApplicationInfo $appInfo = null) : void
    {
        $info = $this->loadInfo($module);
        if ($info === null) {
            return;
        }

        $class = '\\Modules\\' . $info->getDirectory() . '\\Admin\\Installer';

        if (!Autoloader::exists($class)) {
            throw new InvalidModuleException($info->getDirectory());
        }

        /** @var $class InstallerAbstract */
        $class::reInit($info, $appInfo);
    }

    /**
     * Install module.
     *
     * @param string $module Module name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function install(string $module) : bool
    {
        $installed = $this->getInstalledModules(false);
        if (isset($installed[$module])) {
            return true;
        }

        if (!\is_file($this->modulePath . $module . '/Admin/Installer.php')) {
            return false;
        }

        try {
            $info = $this->loadInfo($module);
            if ($info === null) {
                return false; // @codeCoverageIgnore
            }

            $this->installed[$module] = $info;
            $this->installModule($info);

            /* Install providing but only if receiving module is already installed */
            $providing = $info->getProviding();
            foreach ($providing as $key => $_) {
                if (isset($installed[$key])) {
                    $this->installProviding('/Modules/' . $module, $key);
                }
            }

            /* Install receiving and applications */
            foreach ($this->installed as $key => $_) {
                $this->installProviding('/Modules/' . $key, $module);
            }

            return true;
        } catch (\Throwable $_) {
            return false; // @codeCoverageIgnore
        }
    }

    /**
     * Uninstall module.
     *
     * @param string $module Module name
     *
     * @return bool
     *
     * @throws InvalidModuleException
     *
     * @since 1.0.0
     */
    public function uninstall(string $module) : bool
    {
        $installed = $this->getInstalledModules(false);
        if (!isset($installed[$module])) {
            return false;
        }

        if (!\is_file($this->modulePath . $module . '/Admin/Uninstaller.php')) {
            return false;
        }

        try {
            $info = $this->loadInfo($module);
            if ($info === null) {
                return false; // @codeCoverageIgnore
            }

            $this->installed[$module] = $info;
            // uninstall dependencies if not used by others
            // uninstall providing for
            // uninstall receiving from? no?
            // uninstall module

            $class = '\\Modules\\' . $info->getDirectory() . '\\Admin\\Uninstaller';
            if (!Autoloader::exists($class)) {
                throw new InvalidModuleException($info->getDirectory());
            }

            /** @var $class UninstallerAbstract */
            $class::uninstall($this->app, $info);

            if (isset($this->installed[$module])) {
                unset($this->installed[$module]);
            }

            if (isset($this->running[$module])) {
                unset($this->running[$module]);
            }

            if (isset($this->active[$module])) {
                unset($this->active[$module]);
            }

            return true;
        } catch (\Throwable $_) {
            return false; // @codeCoverageIgnore
        }
    }

    /**
     * Install module itself.
     *
     * @param ModuleInfo $info Module info
     *
     * @return void
     *
     * @throws InvalidModuleException Throws this exception in case the installer doesn't exist
     *
     * @since 1.0.0
     */
    private function installModule(ModuleInfo $info) : void
    {
        $class = '\\Modules\\' . $info->getDirectory() . '\\Admin\\Installer';
        if (!Autoloader::exists($class)) {
            throw new InvalidModuleException($info->getDirectory());
        }

        /** @var InstallerAbstract $class */
        $class::install($this->app, $info, $this->app->appSettings);
    }

    /**
     * Install providing.
     *
     * Installing additional functionality for another module
     *
     * @param string $from From path
     * @param string $for  For module
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function installProviding(string $from, string $for) : void
    {
        if (!\is_file(__DIR__ . '/../..' . $from . '/Admin/Install/' . $for . '.php')) {
            return;
        }

        $from = \strtr($from, '/', '\\');

        $class = $from . '\\Admin\\Install\\' . $for;
        $class::install($this->app, $this->modulePath);
    }

    /**
     * Initialize module.
     *
     * Also registers controller in the dispatcher
     *
     * @param string $module  Module
     * @param string $ctlName Controller name (null = current app)
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function initModuleController(string $module, ?string $ctlName = null) : void
    {
        $name = '\\Modules\\' . $module . '\\Controller\\' . ($ctlName ?? $this->app->appName) . 'Controller';
        $ctrl = $this->get($module, $ctlName);

        if ($this->app->dispatcher !== null) {
            $this->app->dispatcher->controllers[$name] = $ctrl;
        }

        // Handle providing->receiving
        foreach ($this->running as $mName => $controllers) {
            $controller = \reset($controllers);
            if ($controller === false) {
                continue;
            }

            foreach ($controller::$providing as $providing) {
                $ctrl = \reset($this->running[$providing]);
                if ($ctrl === false) {
                    continue;
                }

                if (!\in_array($mName, $ctrl->receiving)) {
                    $ctrl->receiving[] = $mName;
                }
            }
        }
    }

    /**
     * Get module instance.
     *
     * This also returns inactive or uninstalled modules if they are still in the modules directory.
     *
     * @param string $module  Module name
     * @param string $ctlName Controller name (null = current)
     *
     * @return object|\phpOMS\Module\ModuleAbstract
     *
     * @todo Remove docblock type hint hack "object".
     *      The return type object is only used to stop the annoying warning that a method doesn't exist
     *      if you chain call the methods part of the returned ModuleAbstract implementation.
     *      Remove it once alternative inline type hinting is possible for the specific returned implementation.
     *      This also causes phpstan type inspection errors, which we have to live with or ignore in the settings
     *      https://github.com/Karaka-Management/phpOMS/issues/300
     *
     * @since 1.0.0
     */
    public function get(string $module, ?string $ctlName = null) : ModuleAbstract
    {
        $class = '\\Modules\\' . $module . '\\Controller\\' . ($ctlName ?? $this->app->appName) . 'Controller';
        if (!isset($this->running[$module])) {
            $this->running[$module] = [];
        }

        if (isset($this->running[$module][$class])) {
            return $this->running[$module][$class];
        }

        if (Autoloader::exists($class)
            || Autoloader::exists($class = '\\Modules\\' . $module . '\\Controller\\Controller')
        ) {
            try {
                /** @var ModuleAbstract $obj */
                $obj                            = new $class($this->app);
                $this->running[$module][$class] = $obj;
            } catch (\Throwable $_) {
                $this->running[$module][$class] = new NullModule();
            }
        } else {
            $this->running[$module][$class] = new NullModule();
        }

        return $this->running[$module][$class];
    }

    /**
     * Initialize all modules for a request.
     *
     * @param RequestAbstract $request Request
     * @param string          $ctlName Controller name (null = current app)
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function initRequestModules(RequestAbstract $request, ?string $ctlName = null) : void
    {
        $toInit = $this->getRoutedModules($request);
        foreach ($toInit as $module) {
            $this->initModuleController($module, $ctlName);
        }
    }

    /**
     * Get modules that run on this page.
     *
     * @param RequestAbstract $request Request
     *
     * @return string[]
     *
     * @since 1.0.0
     */
    public function getRoutedModules(RequestAbstract $request) : array
    {
        $files   = $this->getUriLoad($request);
        $modules = [];

        if (isset($files['4'])) {
            foreach ($files['4'] as $module) {
                $modules[] = $module['module_load_file'];
            }
        }

        return $modules;
    }
}
