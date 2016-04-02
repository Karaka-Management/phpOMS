<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Module;

use phpOMS\ApplicationAbstract;
use phpOMS\DataStorage\Database\DatabaseType;
use phpOMS\Log\FileLogger;
use phpOMS\Message\Http\Request;
use phpOMS\System\File\PathException;
use phpOMS\Utils\IO\Json\InvalidJsonException;

/**
 * Modules class.
 *
 * General module functionality such as listings and initialization.
 *
 * @category   Module
 * @package    Framework
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
    const MODULE_PATH = ROOT_PATH . DIRECTORY_SEPARATOR . 'Modules';

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
                $path = realpath($oldPath = self::MODULE_PATH . '/' . $files[$i] . '/info.json');

                if (file_exists($path)) {
                    if (strpos($path, self::MODULE_PATH) === false) {
                        throw new PathException($oldPath);
                    }

                    $json                                 = json_decode(file_get_contents($path), true);
                    self::$all[$json['name']['internal']] = $json;
                }
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
     * Install module.
     *
     * @param string $module Module name
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function install(string $module)
    {
        $installed = $this->getInstalledModules();

        if (isset($installed[$module])) {
            return;
        }

        if (!file_exists(self::MODULE_PATH . '/' . $module . '/Admin/Install.php')) {
            // todo download;
        }

        $path = realpath($oldPath = self::MODULE_PATH . '/' . $module . '/' . 'info.json');

        if ($path === false || strpos($path, self::MODULE_PATH) === false) {
            throw new PathException($module);
        }

        $info = json_decode(file_get_contents($path), true);

        if (!isset($info)) {
            throw new InvalidJsonException($path);
        }

        switch ($this->app->dbPool->get('core')->getType()) {
            case DatabaseType::MYSQL:
            $this->app->dbPool->get('core')->con->beginTransaction();

            $sth = $this->app->dbPool->get('core')->con->prepare(
                'INSERT INTO `' . $this->app->dbPool->get('core')->prefix . 'module` (`module_id`, `module_theme`, `module_path`, `module_active`, `module_version`) VALUES
                (:internal, :theme, :path, :active, :version);'
                );

            $sth->bindValue(':internal', $info['name']['internal'], \PDO::PARAM_INT);
            $sth->bindValue(':theme', 'Default', \PDO::PARAM_STR);
            $sth->bindValue(':path', $info['directory'], \PDO::PARAM_STR);
            $sth->bindValue(':active', 1, \PDO::PARAM_INT);
            $sth->bindValue(':version', $info['version'], \PDO::PARAM_STR);

            $sth->execute();

            $sth = $this->app->dbPool->get('core')->con->prepare(
                'INSERT INTO `' . $this->app->dbPool->get('core')->prefix . 'module_load` (`module_load_pid`, `module_load_type`, `module_load_from`, `module_load_for`, `module_load_file`) VALUES
                (:pid, :type, :from, :for, :file);'
                );

            foreach ($info['load'] as $val) {
                foreach ($val['pid'] as $pid) {
                    $sth->bindValue(':pid', $pid, \PDO::PARAM_STR);
                    $sth->bindValue(':type', $val['type'], \PDO::PARAM_INT);
                    $sth->bindValue(':from', $val['from'], \PDO::PARAM_STR);
                    $sth->bindValue(':for', $val['for'], \PDO::PARAM_STR);
                    $sth->bindValue(':file', $val['file'], \PDO::PARAM_STR);

                    $sth->execute();
                }
            }

            $this->app->dbPool->get('core')->con->commit();

            break;
        }

        foreach ($info['dependencies'] as $key => $version) {
            $this->install($key);
        }

        $class = '\\Modules\\' . $module . '\\Admin\\Installer';
        /** @var $class InstallerAbstract */
        $class::install($this->app->dbPool, $info);

            // TODO: change this
        $this->installed[$module] = true;

        foreach ($info['providing'] as $key => $version) {
            $this->installProviding($module, $key);
        }

        /* Install receiving */
        foreach ($installed as $key => $value) {
            $this->installProviding($key, $module);
        }
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
    public function installProviding(string $from, string $for)
    {
        if (file_exists(self::MODULE_PATH . '/' . $from . '/Admin/Install/' . $for . '.php')) {
            $class = '\\Modules\\' . $from . '\\Admin\\Install\\' . $for;
            /** @var $class InstallerAbstract */
            $class::install($this->app->dbPool, null);
        }
    }

    /**
     * Initialize module.
     *
     * @param string|array $module Module name
     *
     * @throws \InvalidArgumentException
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function initModule($module)
    {
        if (is_array($module)) {
            $this->initModuleArray($module);
        } elseif (is_string($module) && realpath(self::MODULE_PATH . '/' . $module . '/Controller.php') !== false) {
            $this->initModuleController($module);
        } else {
            throw new \InvalidArgumentException('Invalid Module');
        }
    }

    private function initModuleArray(array $modules)
    {
        foreach ($modules as $module) {
            try {
                $this->initModule($module);
            } catch (\InvalidArgumentException $e) {
                $this->app->logger->warning(FileLogger::MSG_FULL, [
                    'message' => 'Trying to initialize ' . $module . ' without controller.',
                    'line'    => $e->getLine(),
                    'file'    => $e->getFile(),
                    ]);
            }
        }
    }

    private function initModuleController(string $module) 
    {
        $this->running[$module] = ModuleFactory::getInstance($module, $this->app);
        $this->app->dispatcher->set($this->running[$module], '\Modules\\' . $module . '\\Controller');
    }

    /**
     * Get module instance.
     *
     * @param string $module Module name
     *
     * @return \phpOMS\Module\ModuleAbstract
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function get(string $module)
    {
        if (!isset($this->running[$module])) {
            $this->initModule($module);
        }

        return $this->running[$module];
    }

    /**
     * Load module language.
     *
     * @param string $language    Langauge
     * @param string $destination Destination
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function loadLanguage(string $language, string $destination)
    {
        foreach ($this->running as $name => $module) {
            /** @var ModuleAbstract $module */
            $file = $module->getLocalization($language, $destination);
            if (!empty($file)) {
                $this->app->l11nManager->loadLanguage($language, $name, $file);
            }
        }
    }
}
