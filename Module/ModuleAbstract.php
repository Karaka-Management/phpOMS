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
use phpOMS\System\FilePathException;


/**
 * Module abstraction class.
 *
 * @category   Framework
 * @package    phpOMS\Module
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
abstract class ModuleAbstract
{

    /**
     * Receiving modules from?
     *
     * @var \string[]
     * @since 1.0.0
     */
    protected $receiving = [];

    /**
     * Receiving modules from?
     *
     * @var \string[]
     * @since 1.0.0
     */
    protected static $providing = [];

    /**
     * Module name.
     *
     * @var \string
     * @since 1.0.0
     */
    protected static $module = '';

    /**
     * Localization files.
     *
     * @var array
     * @since 1.0.0
     */
    protected static $localization = [];

    /**
     * Routes.
     *
     * @var array
     * @since 1.0.0
     */
    protected static $routes = [];

    /**
     * Dependencies.
     *
     * @var \string
     * @since 1.0.0
     */
    protected static $dependencies = [];

    /**
     * Application instance.
     *
     * @var \phpOMS\ApplicationAbstract
     * @since 1.0.0
     */
    protected $app = null;

    /**
     * Constructor.
     *
     * @param \phpOMS\ApplicationAbstract $app Application instance
     *
     * @since  1.0.0
     * @author Dennis Eichhorn
     */
    public function __construct($app)
    {
        $this->app = $app;

        foreach (static::$routes as $route => $destinations) {
            foreach ($destinations as $destination) {
                $this->app->router->add($route, $destination['dest'], $destination['method'], $destination['type']);
            }
        }
    }

    /**
     * Install external.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function installExternal()
    {
        return false;
    }

    /**
     * Get language files.
     *
     * @param \string $language    Language key
     * @param \string $destination Application destination (e.g. Backend)
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getLocalization(\string $language, \string $destination) : array
    {
        $lang = [];
        if (isset(static::$localization[$destination])) {
            foreach (static::$localization[$destination] as $file) {
                if(($path = $path = realpath(__DIR__ . '/../../Modules/' . static::$module . '/Theme/lang/' . $file . '.' . $language . '.lang.php')) === false) {
                    throw new FilePathException(__DIR__ . '/../../Modules/' . static::$module . '/Theme/lang/' . $file . '.' . $language . '.lang.php');
                }

                /** @noinspection PhpIncludeInspection */
                include realpath(__DIR__ . '/../../Modules/' . static::$module . '/Theme/lang/' . $file . '.' . $language . '.lang.php');
                /** @var array $MODLANG */
                $lang += $MODLANG;
            }
        }

        return $lang;
    }

    /**
     * {@inheritdoc}
     */
    public function addReceiving(\string $module)
    {
        $this->receiving[] = $module;
    }

    /**
     * {@inheritdoc}
     */
    public function getProviding() : array
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return static::$providing;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() : \string
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return static::$module;
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() : array
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return static::$dependencies;
    }
}
