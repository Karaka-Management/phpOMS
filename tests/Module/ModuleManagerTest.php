<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Module;

use phpOMS\ApplicationAbstract;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\Router;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
class ModuleManagerTest extends \PHPUnit\Framework\TestCase
{
    protected $app = null;

    protected function setUp() : void
    {
        $this->app             = new class() extends ApplicationAbstract { protected $appName = 'Api'; };
        $this->app->appName    = 'Api';
        $this->app->dbPool     = $GLOBALS['dbpool'];
        $this->app->dispatcher = new Dispatcher($this->app);
    }

    public function testAttributes() : void
    {
        $moduleManager = new ModuleManager($this->app, __DIR__ . '/../../../Modules');
        self::assertInstanceOf('\phpOMS\Module\ModuleManager', $moduleManager);

        self::assertObjectHasAttribute('running', $moduleManager);
        self::assertObjectHasAttribute('installed', $moduleManager);
        self::assertObjectHasAttribute('active', $moduleManager);
        self::assertObjectHasAttribute('all', $moduleManager);
        self::assertObjectHasAttribute('uriLoad', $moduleManager);
    }

    public function testUnknownModuleInit() : void
    {
        $moduleManager = new ModuleManager($this->app, __DIR__ . '/../../../Modules');
        $moduleManager->initModule('doesNotExist');
        self::assertInstanceOf('\phpOMS\Module\NullModule', $moduleManager->get('doesNotExist'));
    }

    public function testUnknownModuleGet() : void
    {
        $moduleManager = new ModuleManager($this->app, __DIR__ . '/../../../Modules');
        self::assertInstanceOf('\phpOMS\Module\NullModule', $moduleManager->get('doesNotExist2'));
    }

    public function testUnknwonModuleModification() : void
    {
        $moduleManager = new ModuleManager($this->app, __DIR__ . '/../../../Modules');

        self::assertFalse($moduleManager->activate('randomErrorTest1'));
        self::assertFalse($moduleManager->deactivate('randomErrorTest1'));
    }

    public function testGetSet() : void
    {
        $this->app->router     = new Router();
        $this->app->dispatcher = new Dispatcher($this->app);

        $moduleManager = new ModuleManager($this->app, __DIR__ . '/../../../Modules');

        $active    = $moduleManager->getActiveModules();
        $all       = $moduleManager->getAllModules();
        $installed = $moduleManager->getInstalledModules();

        self::assertNotEmpty($active);
        self::assertNotEmpty($all);
        self::assertNotEmpty($installed);

        self::assertInstanceOf('\phpOMS\Module\ModuleAbstract', $moduleManager->get('Admin'));
        self::assertInstanceOf('\Modules\Admin\Controller\ApiController', $moduleManager->get('Admin'));
    }
}
