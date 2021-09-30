<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Module;

use Model\CoreSettings;
use Modules\Admin\Models\Module;
use Modules\Admin\Models\ModuleMapper;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Module\ModuleManager;
use phpOMS\Module\ModuleStatus;
use phpOMS\Router\WebRouter;
use phpOMS\Uri\HttpUri;

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

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->app                    = new class() extends ApplicationAbstract {
            protected string $appName = 'Api';
        };

        $this->app->appName     = 'Api';
        $this->app->dbPool      = $GLOBALS['dbpool'];
        $this->app->router      = new WebRouter();
        $this->app->dispatcher  = new Dispatcher($this->app);
        $this->app->appSettings = new CoreSettings($this->app->dbPool->get('admin'));
        $this->moduleManager    = new ModuleManager($this->app, __DIR__ . '/../../../Modules/');
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
     * @testdox Unknown modules cannot get activated, deactivated
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
    public function testAllActiveModules() : void
    {
        $active = $this->moduleManager->getActiveModules();

        self::assertNotEmpty($active);
    }

    /**
     * @testdox Modules can be checked to be active
     * @covers phpOMS\Module\ModuleManager
     * @group framework
     */
    public function testActiveModule() : void
    {
        $active = $this->moduleManager->getActiveModules();

        /** @var string $last */
        $last = \end($active);

        self::assertTrue($this->moduleManager->isActive($last['name']['internal']));
        self::assertFalse($this->moduleManager->isActive('Invalid'));
    }

    /**
     * @testdox Modules can be checked to be running
     * @covers phpOMS\Module\ModuleManager
     * @group framework
     */
    public function testRunningModule() : void
    {
        $module = $this->moduleManager->get('TestModule');
        self::assertTrue($this->moduleManager->isRunning('TestModule'));
        self::assertFalse($this->moduleManager->isRunning('Invalid'));
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
     * @testdox A module can be installed and its status can be changed
     * @covers phpOMS\Module\InstallerAbstract
     * @covers phpOMS\Module\ModuleManager
     * @covers phpOMS\Module\StatusAbstract
     * @group framework
     */
    public function testStatus() : void
    {
        $this->moduleManager->install('TestModule');

        $module       = new Module();
        $module->id   = 'TestModule';
        $module->name = 'TestModule';
        $module->path = 'TestModule';
        ModuleMapper::create($module);

        self::assertTrue($this->moduleManager->deactivate('TestModule'));
        self::assertFalse($this->moduleManager->isActive('TestModule'));

        self::assertTrue($this->moduleManager->activate('TestModule'));

        // this is normally done in the ApiController
        $module->setStatus(ModuleStatus::ACTIVE);
        ModuleMapper::update($module);

        $queryLoad = new Builder($this->app->dbPool->get('insert'));
        $queryLoad->insert('module_load_pid', 'module_load_type', 'module_load_from', 'module_load_for', 'module_load_file')
            ->into('module_load');

        $moduleInfo = $this->moduleManager->loadInfo('TestModule');

        $load = $moduleInfo->getLoad();
        foreach ($load as $val) {
            foreach ($val['pid'] as $pid) {
                $queryLoad->values(
                    \sha1(\str_replace('/', '', $pid)),
                    (int) $val['type'],
                    $val['from'],
                    $val['for'],
                    $val['file']
                );
            }
        }

        if (!empty($queryLoad->getValues())) {
            $queryLoad->execute();
        }

        self::assertTrue($this->moduleManager->isActive('TestModule'));
    }

    /**
     * @testdox A module can be re-initialized
     * @covers phpOMS\Module\ModuleManager
     * @covers phpOMS\Module\StatusAbstract
     * @group framework
     */
    public function testReInit() : void
    {
        $this->moduleManager->reInit('TestModule');
        self::assertTrue($this->moduleManager->isActive('TestModule'));
    }

    /**
     * @testdox A module is automatically loaded for its URIs
     * @covers phpOMS\Module\ModuleManager
     * @group framework
     */
    public function testRequestLoad() : void
    {
        $request = new HttpRequest(new HttpUri('http://127.0.0.1/en/backend/testmodule'));
        $request->createRequestHashs(2);

        $loaded = $this->moduleManager->getUriLoad($request);

        $found = false;
        foreach ($loaded[4] as $module) {
            if ($module['module_load_file'] === 'TestModule') {
                $found = true;
                break;
            }
        }

        self::assertTrue($found);

        self::assertGreaterThan(0, \count($this->moduleManager->getLanguageFiles($request)));
        self::assertTrue(\in_array('TestModule', $this->moduleManager->getRoutedModules($request)));

        $this->moduleManager->initRequestModules($request);
        self::assertTrue($this->moduleManager->isRunning('TestModule'));
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

    /**
     * @testdox A module can be uninstalled
     * @covers phpOMS\Module\ModuleManager
     * @covers phpOMS\Module\UninstallerAbstract
     * @group framework
     */
    public function testUninstall() : void
    {
        $this->moduleManager->uninstall('TestModule');

        self::assertFalse($this->moduleManager->uninstall('TestModule'));

        $module = ModuleMapper::get('TestModule');
        ModuleMapper::delete($module);
        ModuleMapper::clearCache();

        self::assertFalse($this->moduleManager->isActive('TestModule'));
        self::assertFalse($this->moduleManager->isRunning('TestModule'));
    }

    /**
     * @covers phpOMS\Module\ModuleManager
     * @group framework
     */
    public function testInvalidModulePath() : void
    {
        $moduleManager = new ModuleManager($this->app, __DIR__ . '/Testmodule/');

        self::assertEquals([], $moduleManager->getAllModules());
        self::assertEquals([], $moduleManager->getInstalledModules());
    }

    /**
     * @covers phpOMS\Module\ModuleManager
     * @group framework
     */
    public function testInvalidModuleInstall() : void
    {
        self::assertFalse($this->moduleManager->install('Invalid'));
    }

    /**
     * @covers phpOMS\Module\ModuleManager
     * @group framework
     */
    public function testInvalidModuleUninstall() : void
    {
        self::assertFalse($this->moduleManager->uninstall('Invalid'));
    }
}
