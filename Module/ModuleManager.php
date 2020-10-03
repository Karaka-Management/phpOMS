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

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Application\ApplicationInfo;
use phpOMS\Application\ApplicationManager;
use phpOMS\Autoloader;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\RequestAbstract;
use phpOMS\Module\Exception\InvalidModuleException;
use phpOMS\System\File\PathException;

/**
 * Module manager class.
 *
 * General module functionality such as listings and initialization.
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 *
 * @todo Orange-Management/Modules#113
 *  Don't use name but id for identification
 */
final class ModuleManager
{
    /**
     * All modules that are running on this uri.
     *
     * @var \phpOMS\Module\ModuleAbstract[]
     * @since 1.0.0
     */
    private array $running = [];

    /**
     * All modules another module is providing for.
     *
     * This is important to inform other modules what kind of information they can receive from other modules.
     *
     * @var array<string, string[]>
     * @since 1.0.0
     */
    private array $providing = [];

    /**
     * Application instance.
     *
     * @var ApplicationAbstract
     * @since 1.0.0
     */
    private ApplicationAbstract $app;

    /**
     * Application manager.
     *
     * @var ApplicationManager
     * @since 1.0.0
     */
    private ApplicationManager $appManager;

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
     * @var array<string, array>
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
     * @param string              $modulePath Path to modules
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
    public function getLanguageFiles(RequestAbstract $request, string $app = null) : array
    {
        $files = $this->getUriLoad($request);

        $lang = [];
        if (isset($files['5'])) {
            foreach ($files['5'] as $module) {
                $lang[] = '/Modules/' . $module['module_load_from'] . '/Theme/' . ($app ?? $this->app->appName) . '/Lang/' . $module['module_load_file'];
            }
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
                ->innerJoin('module')->on('module_load.module_load_from', '=', 'module.module_id')->orOn('module_load.module_load_for', '=', 'module.module_id')
                ->whereIn('module_load.module_load_pid', $uriHash)
                ->andWhere('module.module_active', '=', 1)
                ->execute();

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
                ->where('module.module_active', '=', 1)
                ->execute();

            $active = $sth->fetchAll(\PDO::FETCH_COLUMN);

            foreach ($active as $module) {
                $path = $this->modulePath . '/' . $module . '/info.json';

                if (!\file_exists($path)) {
                    continue;
                    // throw new PathException($path);
                }

                $content = \file_get_contents($path);
                $json    = \json_decode($content === false ? '[]' : $content, true);

                $this->active[$json['name']['internal']] = $json === false ? [] : $json;
            }
        }

        return $this->active;
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
     * @param string $module Module name
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public function isRunning(string $module) : bool
    {
        $name = $this->generateModuleName($module);

        return isset($this->running[$name]);
    }

    /**
     * Get all modules in the module directory.
     *
     * @return array<string, array>
     *
     * @since 1.0.0
     */
    public function getAllModules() : array
    {
        if (empty($this->all)) {
            \chdir($this->modulePath);
            $files = \glob('*', \GLOB_ONLYDIR);

            if ($files === false) {
                return [];
            }

            $c = $files === false ? 0 : \count($files);
            for ($i = 0; $i < $c; ++$i) {
                $path = $this->modulePath . '/' . $files[$i] . '/info.json';

                if (!\file_exists($path)) {
                    continue;
                    // throw new PathException($path);
                }

                $content = \file_get_contents($path);
                $json    = \json_decode($content === false ? '[]' : $content, true);

                $this->all[(string) ($json['name']['internal'] ?? '?')] = $json === false ? [] : $json;
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
    public function getAvailableModules() : array
    {
        return [];
    }

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
            $query = new Builder($this->app->dbPool->get('select'));
            $sth   = $query->select('module.module_path')
                ->from('module')
                ->execute();

            $installed = $sth->fetchAll(\PDO::FETCH_COLUMN);

            foreach ($installed as $module) {
                $path = $this->modulePath . '/' . $module . '/info.json';

                if (!\file_exists($path)) {
                    continue;
                    // throw new PathException($path);
                }

                $this->installed[$module] = $this->loadInfo($module);
            }
        }

        return $this->installed;
    }

    /**
     * Load info of module.
     *
     * @param string $module Module name
     *
     * @return ModuleInfo
     *
     * @since 1.0.0
     */
    private function loadInfo(string $module) : ModuleInfo
    {
        $path = \realpath($oldPath = $this->modulePath . '/' . $module . '/info.json');

        if ($path === false) {
            throw new PathException($oldPath);
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

            $this->deactivateModule($info);

            return true;
        } catch (\Exception $e) {
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
        $class::deactivate($this->app->dbPool, $info);
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

            $this->activateModule($info);

            return true;
        } catch (\Exception $e) {
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
        $class::activate($this->app->dbPool, $info);
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
    public function reInit(string $module, ApplicationInfo $appInfo = null) : void
    {
        $info  = $this->loadInfo($module);
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
            return false;
        }

        /**
         * @todo Orange-Management/Modules#193
         *  Implement online database and downloading api for modules and updates
         */
        if (!\file_exists($this->modulePath . '/' . $module . '/Admin/Installer.php')) {
            return false;
        }

        try {
            $info = $this->loadInfo($module);

            $this->installed[$module] = $info;
            $this->installDependencies($info->getDependencies());
            $this->installModule($info);

            /* Install providing but only if receiving module is already installed */
            $providing = $info->getProviding();
            foreach ($providing as $key => $version) {
                if (isset($installed[$key])) {
                    $this->installProviding($module, $key);
                }
            }

            /* Install receiving and applications */
            foreach ($this->installed as $key => $value) {
                $this->installProviding($key, $module);
            }

            $this->appManager = new ApplicationManager($this);
            $this->installApplications($module);

            return true;
        } catch (\Throwable $t) {
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
     * @since 1.0.0
     */
    public function uninstall(string $module) : bool
    {
        $installed = $this->getInstalledModules(false);

        if (!isset($installed[$module])) {
            return false;
        }

        if (!\file_exists($this->modulePath . '/' . $module . '/Admin/Uninstaller.php')) {
            return false;
        }

        try {
            $info = $this->loadInfo($module);

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
            $class::uninstall($this->app->dbPool, $info);

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
        } catch (\Throwable $t) {
            return false;
        }
    }

    /**
     * Install module dependencies.
     *
     * @param array<string, string> $dependencies Module dependencies
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function installDependencies(array $dependencies) : void
    {
        foreach ($dependencies as $key => $version) {
            $this->install($key);
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
        $class::install($this->app->dbPool, $info, $this->app->appSettings);
    }

    /**
     * Install providing.
     *
     * Installing additional functionality for another module
     *
     * @param string $from From module
     * @param string $for  For module
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function installProviding(string $from, string $for) : void
    {
        if (\file_exists($this->modulePath . '/' . $from . '/Admin/Install/' . $for . '.php')) {
            $class = '\\Modules\\' . $from . '\\Admin\\Install\\' . $for;
            $class::install($this->modulePath, $this->app->dbPool);
        }
    }

    /**
     * Install applications.
     *
     * Installing additional functionality for another module
     *
     * @param string $from From module
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function installApplications(string $from) : void
    {
        if (!\file_exists($this->modulePath . '/' . $from . '/Application')) {
            return;
        }

        $dirs = \scandir($this->modulePath . '/' . $from . '/Application');

        if ($dirs === false) {
            return;
        }

        foreach ($dirs as $dir) {
            if ($dir === '.' || $dir === '..') {
                continue;
            }

            $this->appManager->install($dir, __DIR__ . '/../../Web/' . \basename($dir));
        }
    }

    /**
     * Get module instance.
     *
     * @param string $module Module name
     *
     * @return \phpOMS\Module\ModuleAbstract
     *
     * @throws \Exception
     *
     * @since 1.0.0
     */
    public function get(string $module) : ModuleAbstract
    {
        $name = $this->generateModuleName($module);
        if (!\array_key_exists($name, $this->running)) {
            $this->initModule($module);
        }

        return $this->running[$name] ?? new NullModule();
    }

    /**
     * Initialize module.
     *
     * @param array|string $modules Module name
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     *
     * @since 1.0.0
     */
    public function initModule($modules) : void
    {
        $modules = (array) $modules;
        foreach ($modules as $module) {
            $this->initModuleController($module);
        }
    }

    /**
     * Initialize module.
     *
     * Also registers controller in the dispatcher
     *
     * @param string $module Module
     *
     * @return void
     *
     * @throws \Exception
     *
     * @since 1.0.0
     */
    private function initModuleController(string $module) : void
    {
        $name                 = $this->generateModuleName($module);
        $this->running[$name] = $this->getModuleInstance($module);

        if ($this->app->dispatcher !== null) {
            $this->app->dispatcher->set($this->running[$name], '\Modules\\Controller\\' . $module . '\\' . $this->app->appName . 'Controller');
        }
    }

    /**
     * Generate internal module name for caching
     *
     * @param string $module Module
     *
     * @return string Application and module name dependant name
     *
     * @since 1.0.0
     */
    private function generateModuleName(string $module) : string
    {
        return '\\Modules\\' . $module . '\\Controller\\' . $this->app->appName . 'Controller';
    }

    /**
     * Gets and initializes modules.
     *
     * @param string $module Module ID
     *
     * @return ModuleAbstract
     *
     * @since 1.0.0
     */
    public function getModuleInstance(string $module) : ModuleAbstract
    {
        $class = '\\Modules\\' . $module . '\\Controller\\' . $this->app->appName . 'Controller';
        $name  = $this->generateModuleName($module);

        if (!isset($this->running[$class])) {
            if (Autoloader::exists($class) !== false) {
                try {
                    $obj                  = new $class($this->app);
                    $this->running[$name] = $obj;
                    $this->registerRequesting($obj);
                    $this->registerProvided($obj);
                } catch (\Throwable $e) {
                    $this->running[$name] = new NullModule();
                }
            } else {
                $this->running[$name] = new NullModule();
            }
        }

        return $this->running[$name];
    }

    /**
     * Load modules this module is requesting from
     *
     * @param ModuleAbstract $obj Current module
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function registerRequesting(ModuleAbstract $obj) : void
    {
        $providings = $obj->getProviding();
        $name       = '';

        foreach ($providings as $providing) {
            $name = $this->generateModuleName($providing);

            if (isset($this->running[$name])) {
                $this->running[$name]->addReceiving($obj->getName());
            } else {
                $this->providing[$name][] = $obj->getName();
            }
        }
    }

    /**
     * Register modules this module is receiving from
     *
     * @param ModuleAbstract $obj Current module
     *
     * @return void
     *
     * @since 1.0.0
     */
    private function registerProvided(ModuleAbstract $obj) : void
    {
        $name = $this->generateModuleName($obj->getName());
        if (isset($this->providing[$name])) {
            foreach ($this->providing[$name] as $providing) {
                $obj->addReceiving($providing);
            }
        }
    }

    /**
     * Initialize all modules for a request.
     *
     * @param HttpRequest $request Request
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function initRequestModules(HttpRequest $request) : void
    {
        $toInit = $this->getRoutedModules($request);
        foreach ($toInit as $module) {
            $this->initModuleController($module);
        }
    }

    /**
     * Get modules that run on this page.
     *
     * @param HttpRequest $request Request
     *
     * @return string[]
     *
     * @since 1.0.0
     */
    public function getRoutedModules(HttpRequest $request) : array
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
