<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    phpOMS
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS;

use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\Localization\L11nManager;
use phpOMS\Router\Router;
use phpOMS\DataStorage\Session\SessionInterface;
use phpOMS\DataStorage\Cookie\CookieJar;
use phpOMS\Module\ModuleManager;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\DataStorage\Cache\CachePool;
use Model\CoreSettings;
use phpOMS\Event\EventManager;
use phpOMS\Account\AccountManager;
use phpOMS\Log\FileLogger;

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
 * @property \phpOMS\Router\Router $router
 * @property \phpOMS\DataStorage\Session\SessionInterface $sessionManager
 * @property \phpOMS\DataStorage\Cookie\CookieJar $cookieJar
 * @property \phpOMS\Module\ModuleManager $moduleManager
 * @property \phpOMS\Dispatcher\Dispatcher $dispatcher
 * @property \phpOMS\DataStorage\Cache\CachePool $cachePool
 * @property \Model\CoreSettings $appSettings
 * @property \phpOMS\Event\EventManager $eventManager
 * @property \phpOMS\Account\AccountManager $accountManager
 * @property \phpOMS\Log\FileLogger $logger
 *
 * @package    phpOMS
 * @license    OMS License 1.0
 * @link       https://orange-management.org
 * @since      1.0.0
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
     * Organization id.
     *
     * @var int
     * @since 1.0.0
     */
    protected int $orgId = 0;

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
     * @var null|DatabasePool
     * @since 1.0.0
     */
    protected ?DatabasePool $dbPool = null;

    /**
     * Application settings object.
     *
     * @var null|CoreSettings
     * @since 1.0.0
     */
    protected ?CoreSettings $appSettings = null;

    /**
     * Account manager instance.
     *
     * @var null|AccountManager
     * @since 1.0.0
     */
    protected ?AccountManager $accountManager = null;

    /**
     * Cache instance.
     *
     * @var null|CachePool
     * @since 1.0.0
     */
    protected ?CachePool $cachePool = null;

    /**
     * ModuleManager instance.
     *
     * @var null|ModuleManager
     * @since 1.0.0
     */
    protected ?ModuleManager $moduleManager = null;

    /**
     * Router instance.
     *
     * @var null|Router
     * @since 1.0.0
     */
    protected ?Router $router = null;

    /**
     * Dispatcher instance.
     *
     * @var null|Dispatcher
     * @since 1.0.0
     */
    protected ?Dispatcher $dispatcher = null;

    /**
     * Session instance.
     *
     * @var null|SessionInterface
     * @since 1.0.0
     */
    protected ?SessionInterface $sessionManager = null;

    /**
     * Cookie instance.
     *
     * @var null|CookieJar
     * @since 1.0.0
     */
    protected ?CookieJar $cookieJar = null;

    /**
     * Server localization.
     *
     * @var null|Localization
     * @since 1.0.0
     */
    protected ?Localization $l11nServer = null;

    /**
     * Server localization.
     *
     * @var null|FileLogger
     * @since 1.0.0
     */
    protected ?FileLogger $logger = null;

    /**
     * L11n manager.
     *
     * @var null|L11nManager
     * @since 1.0.0
     */
    protected ?L11nManager $l11nManager = null;

    /**
     * Event manager.
     *
     * @var null|EventManager
     * @since 1.0.0
     */
    protected ?EventManager $eventManager = null;

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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function __get($name)
    {
        return $this->{$name};
    }
}
