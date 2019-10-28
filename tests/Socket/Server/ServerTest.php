<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Socket\Server;

use Model\CoreSettings;
use Modules\Admin\Models\AccountPermission;
use phpOMS\Account\Account;
use phpOMS\Account\AccountManager;
use phpOMS\Account\PermissionType;
use phpOMS\ApplicationAbstract;
use phpOMS\DataStorage\Cache\CachePool;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Event\EventManager;
use phpOMS\Localization\L11nManager;
use phpOMS\Log\FileLogger;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\WebRouter;
use phpOMS\Socket\Server\Server;

/**
 * @internal
 */
class ServerTest extends \PHPUnit\Framework\TestCase
{
    protected $app = null;

    protected function setUp() : void
    {
        $this->app = new class() extends ApplicationAbstract
        {
            protected string $appName = 'Socket';
        };

        $this->app->logger         = FileLogger::getInstance(__DIR__ . '/server.log', true);
        $this->app->dbPool         = $GLOBALS['dbpool'];
        $this->app->orgId          = 1;
        $this->app->cachePool      = new CachePool($this->app->dbPool);
        $this->app->accountManager = new AccountManager($GLOBALS['session']);
        $this->app->appSettings    = new CoreSettings($this->app->dbPool->get());
        $this->app->moduleManager  = new ModuleManager($this->app, __DIR__ . '/../../../../Modules');
        $this->app->dispatcher     = new Dispatcher($this->app);
        $this->app->eventManager   = new EventManager($this->app->dispatcher);
        $this->app->eventManager->importFromFile(__DIR__ . '/../../../Socket/Hooks.php');
        $this->app->l11nManager    = new L11nManager($this->app->appName);
        $this->app->router         = new WebRouter();
    }

    protected function tearDown() : void
    {
        /*
        \delete(__DIR__ . '/client.log');
        \delete(__DIR__ . '/server.log');*/
    }

    public function testSetupTCPSocket() : void
    {
        self::markTestIncomplete();
        return;
        $pipes = [];
        $process = \proc_open('php ServerTestHelper.php 127.0.0.1', [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, __DIR__);

        $socket = new Server($this->app);
        $socket->create('127.0.0.1', $GLOBALS['CONFIG']['socket']['master']['port']);
        $socket->setLimit(1);
        $socket->run();

        // todo: assert content of server.log
        // todo: assert content of client.log
    }
}
