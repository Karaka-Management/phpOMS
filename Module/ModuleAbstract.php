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

use phpOMS\System\File\PathException;


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
     * @var string[]
     * @since 1.0.0
     */
    protected $receiving = [];

    /**
     * Receiving modules from?
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $providing = [];

    /**
     * Module name.
     *
     * @var string
     * @since 1.0.0
     */
    const MODULE_NAME = '';

    /**
     * Module path.
     *
     * @var string
     * @since 1.0.0
     */
    const MODULE_PATH = __DIR__ . '/../../Modules';

    /**
     * Module version.
     *
     * @var string
     * @since 1.0.0
     */
    const MODULE_VERSION = '1.0.0';

    /**
     * Localization files.
     *
     * @var array
     * @since 1.0.0
     */
    protected static $localization = [];

    /**
     * Dependencies.
     *
     * @var string
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
     * @param string $language    Language key
     * @param string $destination Application destination (e.g. Backend)
     *
     * @return array
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getLocalization(string $language, string $destination) : array
    {
        $lang = [];
        if (($path = realpath($oldPath = __DIR__ . '/../../Modules/' . static::MODULE_NAME . '/Theme/' . $destination . '/Lang/' . $language . '.lang.php')) === false) {
            throw new PathException($oldPath);
        }

        /** @noinspection PhpIncludeInspection */
        $lang = include $path;

        return $lang;
    }

    /**
     * {@inheritdoc}
     */
    public function addReceiving(string $module)
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
    public function getName() : string
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return static::MODULE_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies() : array
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return static::$dependencies;
    }

    /**
     * Get event id prefix.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getEventId() : string
    {
        return static::class;
    }
}
