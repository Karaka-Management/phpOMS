<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
declare(strict_types=1);

namespace phpOMS\Module;

use phpOMS\ApplicationAbstract;
use phpOMS\Autoloader;
use phpOMS\DataStorage\Database\DatabaseType;
use phpOMS\Message\Http\Request;
use phpOMS\System\File\PathException;

/**
 * Modules class.
 *
 * General module functionality such as listings and initialization.
 *
 * @category   Framework
 * @package    phpOMS\Module
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class ModuleManager
{

    /**
     * Module path.
     *
     * @var string
     * @since 1.0.0
     */
    /* public */ const MODULE_PATH = __DIR__ . '/../../Modules';

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
    private $installed = null;

    /**
     * All active modules (on all pages not just the ones that are running now).
     *
     * @var array
     * @since 1.0.0
     */
    private $active = null;

    /**
     * All modules in the module directory.
     *
     * @var array
     * @since 1.0.0
     */
    private static $all = null;

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
     * @param ApplicationAbstract $app Application
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(ApplicationAbstract $app)
    {
        $this->app = $app;
    }

    /**
     * Get modules that run on this page.
     *
     * @param Request $request Request
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getRoutedModules(Request $request) : array
    {
        $files = $this->getUriLoad($request);

        $modules = [];
        if (isset($files[4])) {
            foreach ($files[4] as $module) {
                $modules[] = $module['module_load_file'];
            }
        }

        return $modules;
    }

    /**
     * Get modules that run on this page.
     *
     * @param Request $request Request
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getUriLoad(Request $request) : array
    {
        if (!isset($this->uriLoad)) {
            switch ($this->app->dbPool->get('core')->getType()) {
                case DatabaseType::MYSQL:
                    $uriHash = $request->getHash();
                    $uriPdo  = '';

                    $i = 1;
                    $c = count($uriHash);
                    for ($k = 0; $k < $c; $k++) {
                        $uriPdo .= ':pid' . $i . ',';
                        $i++;
                    }

                    $uriPdo = rtrim($uriPdo, ',');

                    /* TODO: make join in order to see if they are active */
                    $sth = $this->app->dbPool->get('core')->con->prepare(
                        'SELECT
                    `' . $this->app->dbPool->get('core')->prefix . 'module_load`.`module_load_type`, `' . $this->app->dbPool->get('core')->prefix . 'module_load`.*
                    FROM
                    `' . $this->app->dbPool->get('core')->prefix . 'module_load`
                    WHERE
                    `module_load_pid` IN(' . $uriPdo . ')'
                    );

                    $i = 1;
                    foreach ($uriHash as $hash) {
                        $sth->bindValue(':pid' . $i, $hash, \PDO::PARAM_STR);
                        $i++;
                    }

                    $sth->execute();

                    $this->uriLoad = $sth->fetchAll(\PDO::FETCH_GROUP);
            }
        }

        return $this->uriLoad;
    }

    /**
     * Get language files.
     *
     * @param Request $request Request
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getLanguageFiles(Request $request) : array
    {
        $files = $this->getUriLoad($request);

        $lang = [];
        if (isset($files[5])) {
            foreach ($files[5] as $module) {
                $lang[] = '/Modules/' . $module['module_load_from'] . '/Theme/' . $this->app->appName . '/Lang/' . $module['module_load_file'];
            }
        }

        return $lang;
    }

    /**
     * Get all installed modules that are active (not just on this uri).
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getActiveModules() : array
    {
        if ($this->active === null) {
            switch ($this->app->dbPool->get('core')->getType()) {
                case DatabaseType::MYSQL:
                    $sth = $this->app->dbPool->get('core')->con->prepare('SELECT `module_path` FROM `' . $this->app->dbPool->get('core')->prefix . 'module` WHERE `module_active` = 1');
                    $sth->execute();
                    $this->active = $sth->fetchAll(\PDO::FETCH_COLUMN);
                    break;
            }
        }

        return $this->active;
    }

    /**
     * Get all modules in the module directory.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public static function getAllModules() : array
    {
        if (!isset(self::$all)) {
            chdir(self::MODULE_PATH);
            $files = glob('*', GLOB_ONLYDIR);
            $c     = count($files);

            for ($i = 0; $i < $c; $i++) {
                $path = self::MODULE_PATH . '/' . $files[$i] . '/info.json';

                if (!file_exists($path)) {
                    continue;
                    // throw new PathException($path);
                }

                $json                                 = json_decode(file_get_contents($path), true);
                self::$all[$json['name']['internal']] = $json;
            }
        }

        return self::$all;
    }

    /**
     * Get modules that are available from official resources.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getAvailableModules() : array
    {
    }

    /**
     * Deactivate module.
     *
     * @param string $module Module name
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function deactivate(string $module) : bool
    {
        $installed = $this->getInstalledModules();

        if (isset($installed[$module])) {
            return false;
        }

        try {
            $info = $this->loadInfo($module);

            $this->deactivateModule($info);

            return true;
        } catch (PathException $e) {
            // todo: handle module doesn't exist or files are missing
            //echo $e->getMessage();

            return false;
        } catch (\Exception $e) {
            //echo $e->getMessage();

            return false;
        }
    }

    /**
     * Deactivate module.
     *
     * @param string $module Module name
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function activate(string $module) : bool
    {
        $installed = $this->getInstalledModules();

        if (isset($installed[$module])) {
            return false;
        }

        try {
            $info = $this->loadInfo($module);

            $this->activateModule($info);

            return true;
        } catch (PathException $e) {
            // todo: handle module doesn't exist or files are missing
            //echo $e->getMessage();

            return false;
        } catch (\Exception $e) {
            //echo $e->getMessage();

            return false;
        }
    }

    /**
     * Re-init module.
     *
     * @param string $module Module name
     *
     * @return bool
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function reInit(string $module) : bool
    {
        $info = $this->loadInfo($module);
        /** @var $class InstallerAbstract */
        $class = '\\Modules\\' . $info->getDirectory() . '\\Admin\\Installer';

        if (!Autoloader::exists($class)) {
            throw new \Exception('Module installer does not exist');
        }

        $class::reInit(ROOT_PATH, $info);
    }

    /**
     * Install module.
     *
     * @param string $module Module name
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function install(string $module) : bool
    {
        $installed = $this->getInstalledModules();

        if (isset($installed[$module])) {
            return false;
        }

        if (!file_exists(self::MODULE_PATH . '/' . $module . '/Admin/Installer.php')) {
            // todo download;
            return false;
        }

        try {
            $info = $this->loadInfo($module);

            $this->installed[$module] = $info;
            $this->installDependencies($info->getDependencies());
            $this->installModule($info);

            /* Install providing */
            $providing = $info->getProviding();
            foreach ($providing as $key => $version) {
                $this->installProviding($module, $key);
            }

            /* Install receiving */
            foreach ($installed as $key => $value) {
                $this->installProviding($key, $module);
            }

            return true;
        } catch (PathException $e) {
            // todo: handle module doesn't exist or files are missing
            //echo $e->getMessage();

            return false;
        } catch (\Exception $e) {
            //echo $e->getMessage();

            return false;
        }
    }

    /**
     * Install module dependencies.
     *
     * @param array $dependencies Module dependencies
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    private function installDependencies(array $dependencies) /* : void */
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
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    private function installModule(InfoManager $info) /* : void */
    {
        /** @var $class InstallerAbstract */
        $class = '\\Modules\\' . $info->getDirectory() . '\\Admin\\Installer';

        if (!Autoloader::exists($class)) {
            throw new \Exception('Module installer does not exist');
        }

        $class::install(ROOT_PATH, $this->app->dbPool, $info);
    }

    /**
     * Deactivate module.
     *
     * @param InfoManager $info Module info
     *
     * @return void
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    private function deactivateModule(InfoManager $info) /* : void */
    {
        $class = '\\Modules\\' . $info->getDirectory() . '\\Admin\\Deactivate';

        if (!Autoloader::exists($class)) {
            throw new \Exception('Module deactivation does not exist');
        }

        /** @var $class DeactivateAbstract */
        $class::deactivate($this->app->dbPool, $info);
    }

    /**
     * Activate module.
     *
     * @param InfoManager $info Module info
     *
     * @return void
     *
     * @throws \Exception
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    private function activateModule(InfoManager $info) /* : void */
    {
        $class = '\\Modules\\' . $info->getDirectory() . '\\Admin\\Deactivate';

        if (!Autoloader::exists($class)) {
            throw new \Exception('Module deactivation does not exist');
        }

        /** @var $class ActivateAbstract */
        $class::activate($this->app->dbPool, $info);
    }

    /**
     * Load info of module.
     *
     * @param string $module Module name
     *
     * @return InfoManager
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    private function loadInfo(string $module) : InfoManager
    {
        $path = realpath($oldPath = self::MODULE_PATH . '/' . $module . '/' . 'info.json');

        if ($path === false) {
            throw new PathException($oldPath);
        }

        $info = new InfoManager($path);
        $info->load();

        return $info;
    }

    /**
     * Get all installed modules.
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function getInstalledModules() : array
    {
        if ($this->installed === null) {
            switch ($this->app->dbPool->get('core')->getType()) {
                case DatabaseType::MYSQL:
                    $sth = $this->app->dbPool->get('core')->con->prepare('SELECT `module_id`,`module_theme`,`module_version`,`module_id` FROM `' . $this->app->dbPool->get('core')->prefix . 'module`');
                    $sth->execute();
                    $this->installed = $sth->fetchAll(\PDO::FETCH_GROUP);
                    break;
            }
        }

        return $this->installed;
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
     * @author Dennis Eichhorn
     */
    public function installProviding(string $from, string $for) /* : void */
    {
        if (file_exists(self::MODULE_PATH . '/' . $from . '/Admin/Install/' . $for . '.php')) {
            $class = '\\Modules\\' . $from . '\\Admin\\Install\\' . $for;
            /** @var $class InstallerAbstract */
            $class::install(ROOT_PATH, $this->app->dbPool, null);
        }
    }

    /**
     * Initialize module.
     *
     * @param string|array $modules Module name
     *
     * @return void
     *
     * @throws \InvalidArgumentException
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function initModule($modules) /* : void */
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
     * @author Dennis Eichhorn
     */
    private function initModuleController(string $module) /* : void */
    {
        try {
            $this->running[$module] = ModuleFactory::getInstance($module, $this->app);
            $this->app->dispatcher->set($this->running[$module], '\Modules\\' . $module . '\\Controller');
        } catch (\Exception $e) {
            throw $e;
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
     * @author Dennis Eichhorn
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
     * Initialize all modules for a request.
     *
     * @param Request $request Request
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function initRequestModules(Request $request) /* : void */
    {
        $toInit = $this->getRoutedModules($request);

        foreach($toInit as $module) {
            $this->initModuleController($module);
        }
    }
}
