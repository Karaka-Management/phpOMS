<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Socket\Client;

use Model\CoreSettings;
use phpOMS\Account\AccountManager;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\Autoloader;
use phpOMS\DataStorage\Cache\CachePool;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Event\EventManager;
use phpOMS\Localization\L11nManager;
use phpOMS\Log\FileLogger;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\SocketRouter;
use phpOMS\Socket\Client\Client;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Socket\Client\Client::class)]
final class ClientTest extends \PHPUnit\Framework\TestCase
{
    protected $app;

    public static function setUpBeforeClass() : void
    {
        if (\is_file(__DIR__ . '/client.log')) {
            \unlink(__DIR__ . '/client.log');
        }

        if (\is_file(__DIR__ . '/server.log')) {
            \unlink(__DIR__ . '/server.log');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        if (!Autoloader::exists('\Model\CoreSettings')) {
            self::markTestSkipped();
        }

        $this->app = new class() extends ApplicationAbstract
        {
            protected string $appName = 'Socket';
        };

        $this->app->logger         = new FileLogger(__DIR__ . '/client.log', false);
        $this->app->dbPool         = $GLOBALS['dbpool'];
        $this->app->unitId         = 1;
        $this->app->cachePool      = new CachePool($this->app->dbPool);
        $this->app->accountManager = new AccountManager($GLOBALS['session']);
        $this->app->appSettings    = new CoreSettings();
        $this->app->moduleManager  = new ModuleManager($this->app, __DIR__ . '/../../../../Modules/');
        $this->app->dispatcher     = new Dispatcher($this->app);
        $this->app->eventManager   = new EventManager($this->app->dispatcher);
        $this->app->eventManager->importFromFile(__DIR__ . '/../../../Socket/Hooks.php');
        $this->app->l11nManager = new L11nManager();
        $this->app->router      = new SocketRouter();
    }

    protected function tearDown() : void
    {
        \unlink(__DIR__ . '/client.log');
        \unlink(__DIR__ . '/server.log');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testSetupTCPSocket() : void
    {
        self::markTestIncomplete();
        return;
        $pipes   = [];
        $process = \proc_open('php ClientTestHelper.php', [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes, __DIR__);

        \sleep(5);

        $socket = new Client($this->app);
        $socket->create('127.0.0.1', $GLOBALS['CONFIG']['socket']['master']['port']);

        $socket->addPacket("handshake\r");
        $socket->addPacket("help\r");
        $socket->addPacket("shutdown\r");

        $this->app->router->add('^shutdown$', function() use ($socket) : void { $socket->shutdown(); });

        $socket->run();

        self::assertEquals(
            "Creating socket...\n"
            . "Binding socket...\n"
            . "Start listening...\n"
            . "Is running...\n"
            . "Connecting client...\n"
            . "Connected client.\n"
            . "Doing handshake...\n"
            . "Handshake succeeded.\n"
            . "Is shutdown...\n",
            \file_get_contents(__DIR__ . '/server.log')
        );

        foreach ($pipes as $pipe) {
            \fclose($pipe);
        }

        \proc_close($process);
    }
}
