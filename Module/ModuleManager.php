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

use phpOMS\ApplicationAbstract;
use phpOMS\Autoloader;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Message\Http\Request;
use phpOMS\Message\RequestAbstract;
use phpOMS\Module\Exception\InvalidModuleException;
use phpOMS\System\File\PathException;

/**
 * Modules class.
 *
 * General module functionality such as listings and initialization.
 *
 * @package    phpOMS\Module
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class ModuleManager
{

    /**
     * All modules that are running on this uri.
     *
     * @var \phpOMS\Module\ModuleAbstract[]
     * @since 1.0.0
     */
    private $running = [];

    /**
     * Application instance.
     *
     * @var ApplicationAbstract
     * @since 1.0.0
     */
    private $app = null;

    /**
     * Installed modules.
     *
     * @var array
     * @since 1.0.0
     */
    private $installed = [];

    /**
     * All active modules (on all pages not just the ones that are running now).
     *
     * @var array
     * @since 1.0.0
     */
    private $active = [];

    /**
     * Module path.
     *
     * @var string
     * @since 1.0.0
     */
    private $modulePath = __DIR__ . '/../../Modules';

    /**
     * All modules in the module directory.
     *
     * @var array
     * @since 1.0.0
     */
    private $all = [];

    /**
     * To load based on request uri.
     *
     * @var array
     * @since 1.0.0
     */
    private $uriLoad = null;

    /**
     * Constructor.
     *
     * @param ApplicationAbstract $app        Application
     * @param string              $modulePath Path to modules
     *
     * @since  1.0.0
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
     *
     * @return array<string>
     *
     * @since  1.0.0
     */
    public function getLanguageFiles(RequestAbstract $request) : array
    {
        $files = $this->getUriLoad($request);

        $lang = [];
        if (isset($files['5'])) {
            foreach ($files['5'] as $module) {
                $lang[] = '/Modules/' . $module['module_load_from'] . '/Theme/' . $this->app->appName . '/Lang/' . $module['module_load_file'];
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
     * @since  1.0.0
     */
    public function getUriLoad(RequestAbstract $request) : array
    {
        if ($this->uriLoad === null) {
            $uriHash = $request->getHash();

            $query = new Builder($this->app->dbPool->get('select'));
            $query->prefix($this->app->dbPool->get('select')->prefix);
            $sth = $query->select('module_load.module_load_type', 'module_load.*')
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
     * @since  1.0.0
     */
    public function getActiveModules(bool $useCache = true) : array
    {
        if (empty($this->active) || !$useCache) {
            $query = new Builder($this->app->dbPool->get('select'));
            $query->prefix($this->app->dbPool->get('select')->prefix);
            $sth = $query->select('module.module_path')
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

                $content                                 = \file_get_contents($path);
                $json                                    = \json_decode($content === false ? '[]' : $content, true);
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
     * @since  1.0.0
     */
    public function isActive(string $module) : bool
    {
        return isset($this->getActiveModules(false)[$module]);
    }

    /**
     * Get all modules in the module directory.
     *
     * @return array<string, array>
     *
     * @since  1.0.0
     */
    public function getAllModules() : array
    {
        if (empty($this->all)) {
            \chdir($this->modulePath);
            $files = \glob('*', GLOB_ONLYDIR);
            $c     = \count($files);

            for ($i = 0; $i < $c; ++$i) {
                $path = $this->modulePath . '/' . $files[$i] . '/info.json';

                if (!\file_exists($path)) {
                    continue;
                    // throw new PathException($path);
                }

                $content                              = \file_get_contents($path);
                $json                                 = \json_decode($content === false ? '[]' : $content, true);
                $this->all[$json['name']['internal']] = $json === false ? [] : $json;
            }
        }

        return $this->all;
    }

    /**
     * Get modules that are available from official resources.
     *
     * @return array
     *
     * @since  1.0.0
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
     * @return array<string, array>
     *
     * @since  1.0.0
     */
    public function getInstalledModules(bool $useCache = true) : array
    {
        if (empty($this->installed) || !$useCache) {
            $query = new Builder($this->app->dbPool->get('select'));
            $query->prefix($this->app->dbPool->get('select')->prefix);
            $sth = $query->select('module.module_path')
                ->from('module')
                ->execute();

            $installed = $sth->fetchAll(\PDO::FETCH_COLUMN);

            foreach ($installed as $module) {
                $path = $this->modulePath . '/' . $module . '/info.json';

                if (!\file_exists($path)) {
                    continue;
                    // throw new PathException($path);
                }

                $content                                    = \file_get_contents($path);
                $json                                       = \json_decode($content === false ? '[]' : $content, true);
                $this->installed[$json['name']['internal']] = $json === false ? [] : $json;
            }
        }

        return $this->installed;
    }

    /**
     * Load info of module.
     *
     * @param string $module Module name
     *
     * @return InfoManager
     *
     * @since  1.0.0
     */
    private function loadInfo(string $module) : InfoManager
    {
        $path = \realpath($oldPath = $this->modulePath . '/' . $module . '/info.json');

        if ($path === false) {
            throw new PathException($oldPath);
        }

        $info = new InfoManager($path);
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
     * @since  1.0.0
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
            return false;
        }
    }

    /**
     * Deactivate module.
     *
     * @param InfoManager $info Module info
     *
     * @return void
     *
     * @throws InvalidModuleException Throws this exception in case the deactiviation doesn't exist
     *
     * @since  1.0.0
     */
    private function deactivateModule(InfoManager $info) : void
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
     * @since  1.0.0
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
            return false;
        }
    }

    /**
     * Activate module.
     *
     * @param InfoManager $info Module info
     *
     * @return void
     *
     * @throws InvalidModuleException Throws this exception in case the activation doesn't exist
     *
     * @since  1.0.0
     */
    private function activateModule(InfoManager $info) : void
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
     * @param string $module Module name
     *
     * @return void
     *
     * @throws InvalidModuleException Throws this exception in case the installer doesn't exist
     *
     * @since  1.0.0
     */
    public function reInit(string $module) : void
    {
        $info  = $this->loadInfo($module);
        $class = '\\Modules\\' . $info->getDirectory() . '\\Admin\\Installer';

        if (!Autoloader::exists($class)) {
            throw new InvalidModuleException($info->getDirectory());
        }

        /** @var $class InstallerAbstract */
        $class::reInit($info);
    }

    /**
     * Install module.
     *
     * @param string $module Module name
     *
     * @return bool
     *
     * @since  1.0.0
     */
    public function install(string $module) : bool
    {
        $installed = $this->getInstalledModules(false);

        if (isset($installed[$module])) {
            return false;
        }

        if (!\file_exists($this->modulePath . '/' . $module . '/Admin/Installer.php')) {
            // todo download;
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

            /* Install receiving */
            foreach ($this->installed as $key => $value) {
                $this->installProviding($key, $module);
            }

            return true;
        } catch (PathException $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Uninstall module.
     *
     * @param string $module Module name
     *
     * @return bool
     *
     * @since  1.0.0
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

            return true;
        } catch (PathException $e) {
            return false;
        } catch (\Exception $e) {
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
     * @since  1.0.0
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
     * @param InfoManager $info Module info
     *
     * @return void
     *
     * @throws InvalidModuleException Throws this exception in case the installer doesn't exist
     *
     * @since  1.0.0
     */
    private function installModule(InfoManager $info) : void
    {
        $class = '\\Modules\\' . $info->getDirectory() . '\\Admin\\Installer';

        if (!Autoloader::exists($class)) {
            throw new InvalidModuleException($info->getDirectory());
        }

        /** @var $class InstallerAbstract */
        $class::install($this->app->dbPool, $info);
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
     * @since  1.0.0
     */
    public function installProviding(string $from, string $for) : void
    {
        if (\file_exists($this->modulePath . '/' . $from . '/Admin/Install/' . $for . '.php')) {
            $class = '\\Modules\\' . $from . '\\Admin\\Install\\' . $for;
            $class::install($this->modulePath, $this->app->dbPool);
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
     * @since  1.0.0
     */
    public function get(string $module) : ModuleAbstract
    {
        try {
            if (!isset($this->running[$module])) {
                $this->initModule($module);
            }

            return $this->running[$module];
        } catch (\Exception $e) {
            throw $e;
        }
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
     * @since  1.0.0
     */
    public function initModule($modules) : void
    {
        $modules = (array) $modules;

        foreach ($modules as $module) {
            try {
                $this->initModuleController($module);
            } catch (\InvalidArgumentException $e) {
                throw $e;
            }
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
     * @since  1.0.0
     */
    private function initModuleController(string $module) : void
    {
        try {
            $this->running[$module] = ModuleFactory::getInstance($module, $this->app);
            $this->app->dispatcher->set($this->running[$module], '\Modules\\Controller\\' . $module . '\\' . $this->app->appName . 'Controller');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Initialize all modules for a request.
     *
     * @param Request $request Request
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function initRequestModules(Request $request) : void
    {
        $toInit = $this->getRoutedModules($request);

        foreach ($toInit as $module) {
            $this->initModuleController($module);
        }
    }

    /**
     * Get modules that run on this page.
     *
     * @param Request $request Request
     *
     * @return array<string>
     *
     * @since  1.0.0
     */
    public function getRoutedModules(Request $request) : array
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
