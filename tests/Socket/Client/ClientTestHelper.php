<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package    test
 * @copyright  Dennis Eichhorn
 * @license    OMS License 2.0
 * @version    1.0.0
 * @link       https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Socket\Client;

use Model\CoreSettings;
use phpOMS\Account\AccountManager;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\DataStorage\Cache\CachePool;
use phpOMS\DataStorage\Database\DatabasePool;
use phpOMS\DataStorage\Database\Mapper\DataMapperFactory;
use phpOMS\DataStorage\Session\HttpSession;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Event\EventManager;
use phpOMS\Localization\L11nManager;
use phpOMS\Log\FileLogger;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\SocketRouter;
use phpOMS\Socket\Server\Server;

require_once __DIR__ . '/../../../Autoloader.php';
$config = require_once __DIR__ . '/../../../../config.php';

$GLOBALS['dbpool'] = new DatabasePool();
$GLOBALS['dbpool']->create('admin', $config['db']['core']['masters']['admin']);
$GLOBALS['dbpool']->create('select', $config['db']['core']['masters']['select']);
$GLOBALS['dbpool']->create('update', $config['db']['core']['masters']['update']);
$GLOBALS['dbpool']->create('insert', $config['db']['core']['masters']['insert']);
$GLOBALS['dbpool']->create('schema', $config['db']['core']['masters']['schema']);

$httpSession        = new HttpSession();
$GLOBALS['session'] = $httpSession;

DataMapperFactory::db($GLOBALS['dbpool']->get());

$app = new class() extends ApplicationAbstract
{
    protected string $appName = 'Socket';
};

$app->logger         = FileLogger::getInstance(__DIR__ . '/server.log', true);
$app->dbPool         = $GLOBALS['dbpool'];
$app->unitId         = 1;
$app->cachePool      = new CachePool($app->dbPool);
$app->accountManager = new AccountManager($GLOBALS['session']);
$app->appSettings    = new CoreSettings($app->dbPool->get());
$app->moduleManager  = new ModuleManager($app, __DIR__ . '/../../../../Modules/');
$app->dispatcher     = new Dispatcher($app);
$app->eventManager   = new EventManager($app->dispatcher);
$app->eventManager->importFromFile(__DIR__ . '/../../../Socket/Hooks.php');
$app->l11nManager = new L11nManager();
$app->router      = new SocketRouter();

$socket = new Server($app);
$socket->create('127.0.0.1', $config['socket']['master']['port']);
$socket->setLimit(1);

$app->router->add('^shutdown$', function($app, $request) use ($socket) : void { $socket->shutdown($request); });

$socket->run();
