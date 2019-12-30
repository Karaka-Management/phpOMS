<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   phpOMS
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS;

use phpOMS\Router\RouterInterface;
use phpOMS\Log\FileLogger;
use phpOMS\Event\EventManager;
use phpOMS\Module\ModuleManager;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Account\AccountManager;
use phpOMS\Localization\L11nManager;
use phpOMS\Localization\Localization;
use phpOMS\DataStorage\Cache\CachePool;
use phpOMS\DataStorage\Cookie\CookieJar;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Session\SessionInterface;
use phpOMS\Config\SettingsAbstract;

/**
 * Application class.
 *
 * This class contains all necessary application members. Access to them
 * is restricted to write once in order to prevent manipulation
 * and afterwards read only.
 *
 * @property string $appName
 * @property int $orgId
 * @property \phpOMS\DataStorage\Database\DatabasePool $dbPool
 * @property \phpOMS\Localization\L11nManager $l11nManager
 * @property \phpOMS\Router\RouterInterface $router
 * @property \phpOMS\DataStorage\Session\SessionInterface $sessionManager
 * @property \phpOMS\DataStorage\Cookie\CookieJar $cookieJar
 * @property \phpOMS\Module\ModuleManager $moduleManager
 * @property \phpOMS\Dispatcher\Dispatcher $dispatcher
 * @property \phpOMS\DataStorage\Cache\CachePool $cachePool
 * @property \phpOMS\Config\SettingsAbstract $appSettings
 * @property \phpOMS\Event\EventManager $eventManager
 * @property \phpOMS\Account\AccountManager $accountManager
 * @property \phpOMS\Log\FileLogger $logger
 *
 * @package phpOMS
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class ApplicationAbstract
{
    /**
     * App name.
     *
     * @var   string
     * @since 1.0.0
     */
    protected string $appName = '';

    /**
     * Organization id.
     *
     * @var   int
     * @since 1.0.0
     */
    protected int $orgId = 0;

    /**
     * App theme.
     *
     * @var   string
     * @since 1.0.0
     */
    protected string $theme = '';

    /**
     * Database object.
     *
     * @var   DatabasePool
     * @since 1.0.0
     */
    protected DatabasePool $dbPool;

    /**
     * Application settings object.
     *
     * @var   SettingsAbstract
     * @since 1.0.0
     */
    protected SettingsAbstract $appSettings;

    /**
     * Account manager instance.
     *
     * @var   AccountManager
     * @since 1.0.0
     */
    protected AccountManager $accountManager;

    /**
     * Cache instance.
     *
     * @var   CachePool
     * @since 1.0.0
     */
    protected CachePool $cachePool;

    /**
     * ModuleManager instance.
     *
     * @var   ModuleManager
     * @since 1.0.0
     */
    protected ModuleManager $moduleManager;

    /**
     * Router instance.
     *
     * @var   RouterInterface
     * @since 1.0.0
     */
    protected RouterInterface $router;

    /**
     * Dispatcher instance.
     *
     * @var   Dispatcher
     * @since 1.0.0
     */
    protected Dispatcher $dispatcher;

    /**
     * Session instance.
     *
     * @var   SessionInterface
     * @since 1.0.0
     */
    protected SessionInterface $sessionManager;

    /**
     * Cookie instance.
     *
     * @var   CookieJar
     * @since 1.0.0
     */
    protected CookieJar $cookieJar;

    /**
     * Server localization.
     *
     * @var   Localization
     * @since 1.0.0
     */
    protected Localization $l11nServer;

    /**
     * Server localization.
     *
     * @var   FileLogger
     * @since 1.0.0
     */
    protected FileLogger $logger;

    /**
     * L11n manager.
     *
     * @var   L11nManager
     * @since 1.0.0
     */
    protected L11nManager $l11nManager;

    /**
     * Event manager.
     *
     * @var   EventManager
     * @since 1.0.0
     */
    protected EventManager $eventManager;

    /**
     * Set values
     *
     * @param string $name  Variable name
     * @param string $value Variable value
     *
     * @return void
     *
     * @todo replace with proper setter (faster)
     *
     * @since 1.0.0
     */
    public function __set($name, $value) : void
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
     * @todo replace with proper getter (faster)
     *
     * @since 1.0.0
     */
    public function __get($name)
    {
        return isset($this->{$name}) ? $this->{$name} : null;
    }
}
