<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\Application
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Application;

use phpOMS\Account\AccountManager;
use phpOMS\Config\SettingsInterface;
use phpOMS\DataStorage\Cache\CachePool;
use phpOMS\DataStorage\Cookie\CookieJar;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Session\SessionAbstract;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Event\EventManager;
use phpOMS\Localization\L11nManager;
use phpOMS\Localization\Localization;
use phpOMS\Log\FileLogger;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\RouterInterface;

/**
 * Application class.
 *
 * This class contains all necessary application members. Access to them
 * is restricted to write once in order to prevent manipulation
 * and afterwards read only.
 *
 * @property string                                       $appName
 * @property int                                          $appId
 * @property int                                          $unitId
 * @property \phpOMS\DataStorage\Database\DatabasePool    $dbPool
 * @property \phpOMS\Localization\L11nManager             $l11nManager
 * @property \phpOMS\Localization\Localization            $l11nServer
 * @property \phpOMS\Router\RouterInterface               $router
 * @property \phpOMS\DataStorage\Session\SessionAbstract $sessionManager
 * @property \phpOMS\DataStorage\Cookie\CookieJar         $cookieJar
 * @property \phpOMS\Module\ModuleManager                 $moduleManager
 * @property \phpOMS\Dispatcher\Dispatcher                $dispatcher
 * @property \phpOMS\DataStorage\Cache\CachePool          $cachePool
 * @property \phpOMS\Config\SettingsInterface             $appSettings
 * @property \phpOMS\Event\EventManager                   $eventManager
 * @property \phpOMS\Account\AccountManager               $accountManager
 * @property \phpOMS\Log\FileLogger                       $logger
 *
 * @package phpOMS\Application
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
class ApplicationAbstract
{
    /**
     * App name.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $appName = '';

    /**
     * App id.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $appId = 0;

    /**
     * Organization id.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $unitId = 0;

    /**
     * App theme.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $theme = '';

    /**
     * Database object.
     *
     * @var DatabasePool
     * @since 1.0.0
     */
    protected DatabasePool $dbPool;

    /**
     * Application settings object.
     *
     * @var SettingsInterface
     * @since 1.0.0
     */
    protected SettingsInterface $appSettings;

    /**
     * Account manager instance.
     *
     * @var AccountManager
     * @since 1.0.0
     */
    protected AccountManager $accountManager;

    /**
     * Cache instance.
     *
     * @var CachePool
     * @since 1.0.0
     */
    protected CachePool $cachePool;

    /**
     * ModuleManager instance.
     *
     * @var ModuleManager
     * @since 1.0.0
     */
    protected ModuleManager $moduleManager;

    /**
     * Router instance.
     *
     * @var RouterInterface
     * @since 1.0.0
     */
    protected RouterInterface $router;

    /**
     * Dispatcher instance.
     *
     * @var Dispatcher
     * @since 1.0.0
     */
    protected Dispatcher $dispatcher;

    /**
     * Session instance.
     *
     * @var SessionAbstract
     * @since 1.0.0
     */
    protected SessionAbstract $sessionManager;

    /**
     * Cookie instance.
     *
     * @var CookieJar
     * @since 1.0.0
     */
    protected CookieJar $cookieJar;

    /**
     * Server localization.
     *
     * @var Localization
     * @since 1.0.0
     */
    protected Localization $l11nServer;

    /**
     * Server localization.
     *
     * @var FileLogger
     * @since 1.0.0
     */
    protected FileLogger $logger;

    /**
     * L11n manager.
     *
     * @var L11nManager
     * @since 1.0.0
     */
    protected L11nManager $l11nManager;

    /**
     * Event manager.
     *
     * @var EventManager
     * @since 1.0.0
     */
    protected EventManager $eventManager;

    /**
     * Application version.
     *
     * @var string
     * @since 1.0.0
     */
    public string $version = '1.0.0';

    /**
     * Set values
     *
     * @param string $name  Variable name
     * @param mixed  $value Variable value
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function __set(string $name, mixed $value) : void
    {
        if (!empty($this->{$name})) {
            return;
        }

        $this->{$name} = $value;
    }

    /**
     * Get values
     *
     * @param string $name Variable name
     *
     * @return mixed Returns the value of the application member
     *
     * @since 1.0.0
     */
    public function __get(string $name) : mixed
    {
        return isset($this->{$name}) ? $this->{$name} : null;
    }
}
