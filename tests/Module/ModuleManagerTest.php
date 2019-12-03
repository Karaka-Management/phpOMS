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

namespace phpOMS\tests\Module;

use phpOMS\ApplicationAbstract;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\WebRouter;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Module\ModuleManagerTest: Manager for the module system
 *
 * @internal
 */
class ModuleManagerTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationAbstract $app;
    protected ModuleManager $moduleManager;

    protected function setUp() : void
    {
        $this->app             = new class() extends ApplicationAbstract { protected string $appName = 'Api'; };
        $this->app->appName    = 'Api';
        $this->app->dbPool     = $GLOBALS['dbpool'];
        $this->app->router     = new WebRouter();
        $this->app->dispatcher = new Dispatcher($this->app);

        $this->moduleManager = new ModuleManager($this->app, __DIR__ . '/../../../Modules');
    }

    /**
     * @testdox The module manager has the expected attributes
     * @covers phpOMS\Module\ModuleManager
     * @group framework
     */
    public function testAttributes() : void
    {
        self::assertInstanceOf('\phpOMS\Module\ModuleManager', $this->moduleManager);

        self::assertObjectHasAttribute('running', $this->moduleManager);
        self::assertObjectHasAttribute('installed', $this->moduleManager);
        self::assertObjectHasAttribute('active', $this->moduleManager);
        self::assertObjectHasAttribute('all', $this->moduleManager);
        self::assertObjectHasAttribute('uriLoad', $this->moduleManager);
    }

    /**
     * @testdox Invalid module initializations returns a null module
     * @covers phpOMS\Module\ModuleManager
     * @group framework
     */
    public function testUnknownModuleInit() : void
    {
        $this->moduleManager->initModule('doesNotExist');
        self::assertInstanceOf('\phpOMS\Module\NullModule', $this->moduleManager->get('doesNotExist'));
    }

    /**
     * @testdox Unknown modules return a null module
     * @covers phpOMS\Module\ModuleManager
     * @group framework
     */
    public function testUnknownModuleGet() : void
    {
        self::assertInstanceOf('\phpOMS\Module\NullModule', $this->moduleManager->get('doesNotExist2'));
    }

    /**
     * @testdox Unknown modules cannot get activested, deactivated
     * @covers phpOMS\Module\ModuleManager
     * @group framework
     */
    public function testUnknwonModuleStatusChange() : void
    {
        self::assertFalse($this->moduleManager->activate('randomErrorTest1'));
        self::assertFalse($this->moduleManager->deactivate('randomErrorTest1'));
    }

    /**
     * @testdox Active modules can be returned
     * @covers phpOMS\Module\ModuleManager
     * @group framework
     */
    public function testActiveModules() : void
    {
        $active = $this->moduleManager->getActiveModules();

        self::assertNotEmpty($active);
    }

    /**
     * @testdox All available modules can be returned
     * @covers phpOMS\Module\ModuleManager
     * @group framework
     */
    public function testAllModules() : void
    {
        $all = $this->moduleManager->getAllModules();

        self::assertNotEmpty($all);
    }

    /**
     * @testdox Installed modules can be returned
     * @covers phpOMS\Module\ModuleManager
     * @group framework
     */
    public function testInstalledModules() : void
    {
        $installed = $this->moduleManager->getInstalledModules();

        self::assertNotEmpty($installed);
    }

    /**
     * @testdox The valid module can be returned
     * @covers phpOMS\Module\ModuleManager
     * @group framework
     */
    public function testAdminModule() : void
    {
        self::assertInstanceOf('\phpOMS\Module\ModuleAbstract', $this->moduleManager->get('Admin'));
        self::assertInstanceOf('\Modules\Admin\Controller\ApiController', $this->moduleManager->get('Admin'));
    }

}
