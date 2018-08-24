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

/**
 * Module abstraction class.
 *
 * @package    phpOMS\Module
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
abstract class ModuleAbstract
{

    /**
     * Module name.
     *
     * @var string
     * @since 1.0.0
     */
    public const MODULE_NAME = '';

    /**
     * Module path.
     *
     * @var string
     * @since 1.0.0
     */
    public const MODULE_PATH = __DIR__ . '/../../Modules';

    /**
     * Module version.
     *
     * @var string
     * @since 1.0.0
     */
    public const MODULE_VERSION = '1.0.0';

    /**
     * Module id.
     *
     * @var string
     * @since 1.0.0
     */
    public const MODULE_ID = 0;

    /**
     * Receiving modules from?
     *
     * @var string[]
     * @since 1.0.0
     */
    protected static $providing = [];
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
     * @var string[]
     * @since 1.0.0
     */
    protected static $dependencies = [];
    /**
     * Receiving modules from?
     *
     * @var string[]
     * @since 1.0.0
     */
    protected $receiving = [];
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
     */
    public function __construct($app)
    {
        $this->app = $app;
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
     */
    public static function getLocalization(string $language, string $destination) : array
    {
        $lang = [];
        if (file_exists($oldPath = __DIR__ . '/../../Modules/' . static::MODULE_NAME . '/Theme/' . $destination . '/Lang/' . $language . '.lang.php')) {
            /** @noinspection PhpIncludeInspection */
            return include $oldPath;
        }

        return $lang;
    }

    /**
     * Add modules this module receives from
     *
     * @param string $module Module name
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function addReceiving(string $module) : void
    {
        $this->receiving[] = $module;
    }

    /**
     * Get modules this module is providing for
     *
     * @return array<int, string>
     *
     * @since  1.0.0
     */
    public function getProviding() : array
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return static::$providing;
    }

    /**
     * Get the name of the module
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function getName() : string
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return static::MODULE_NAME;
    }

    /**
     * Get module dependencies
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getDependencies() : array
    {
        /** @noinspection PhpUndefinedFieldInspection */
        return static::$dependencies;
    }
}
