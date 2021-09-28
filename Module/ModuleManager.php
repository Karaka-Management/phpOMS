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

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Application\ApplicationInfo;
use phpOMS\Autoloader;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\RequestAbstract;
use phpOMS\Module\Exception\InvalidModuleException;

/**
 * Module manager class.
 *
 * General module functionality such as listings and initialization.
 *
 * @package phpOMS\Module
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
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
    public function getLanguageFiles(RequestAbstract $request, string $app = null) : array
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
                ->innerJoin('module')->on('module_load.module_load_from', '=', 'module.module_id')->orOn('module_load.module_load_for', '=', 'module.module_id')
                ->whereIn('module_load.module_load_pid', $uriHash)
                ->andWhere('module.module_status', '=', ModuleStatus::ACTIVE)
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
                ->where('module.module_status', '=', ModuleStatus::ACTIVE)
                ->execute();

            $active = $sth->fetchAll(\PDO::FETCH_COLUMN);

            foreach ($active as $module) {
                $path = $this->modulePath . $module . '/info.json';

                if (!\is_file($path)) {
                    continue;
                }

                $content = \file_get_contents($path);
                $json    = \json_decode($content === false ? '[]' : $content, true);

                $this->active[$json['name']['internal']] = $json === false ? [] : $json;
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
                return [];
            }

            $c = $files === false ? 0 : \count($files);
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
                ->where('module_status', '!=', ModuleStatus::AVAILABLE)
                ->execute();

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
        $path = \realpath($oldPath = $this->modulePath . $module . '/info.json');
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
                return false;
            }

            $this->deactivateModule($info);

            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
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
            if ($info === null) {
                return false;
            }

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
                return false;
            }

            $this->installed[$module] = $info;
            $this->installModule($info);

            /* Install providing but only if receiving module is already installed */
            $providing = $info->getProviding();
            foreach ($providing as $key => $version) {
                if (isset($installed[$key])) {
                    $this->installProviding('/Modules/' . $module, $key);
                }
            }

            /* Install receiving and applications */
            foreach ($this->installed as $key => $value) {
                $this->installProviding('/Modules/' . $key, $module);
            }

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

        if (!\is_file($this->modulePath . $module . '/Admin/Uninstaller.php')) {
            return false;
        }

        try {
            $info = $this->loadInfo($module);
            if ($info === null) {
                return false;
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

        $from = \str_replace('/', '\\', $from);

        $class = $from . '\\Admin\\Install\\' . $for;
        $class::install($this->modulePath, $this->app);
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
    public function initModule(string | array $modules) : void
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
