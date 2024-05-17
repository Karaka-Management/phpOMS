<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
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
use phpOMS\Utils\TestUtils;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Module\ModuleManager::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Module\InstallerAbstract::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Module\StatusAbstract::class)]
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Module\UninstallerAbstract::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Module\ModuleManagerTest: Manager for the module system')]
final class ModuleManagerTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationAbstract $app;

    protected ModuleManager $moduleManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->app = new class() extends ApplicationAbstract {
            protected string $appName = 'Api';
        };

        $this->app->appName     = 'Api';
        $this->app->dbPool      = $GLOBALS['dbpool'];
        $this->app->router      = new WebRouter();
        $this->app->dispatcher  = new Dispatcher($this->app);
        $this->app->appSettings = new CoreSettings();
        $this->moduleManager    = new ModuleManager($this->app, __DIR__ . '/../../../Modules/');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Unknown modules return a null module')]
    public function testUnknownModuleGet() : void
    {
        self::assertInstanceOf('\phpOMS\Module\NullModule', $this->moduleManager->get('doesNotExist2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Unknown modules cannot get activated, deactivated')]
    public function testUnknwonModuleStatusChange() : void
    {
        self::assertFalse($this->moduleManager->activate('randomErrorTest1'));
        self::assertFalse($this->moduleManager->deactivate('randomErrorTest1'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Active modules can be returned')]
    public function testAllActiveModules() : void
    {
        $active = $this->moduleManager->getActiveModules();

        self::assertNotEmpty($active);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Modules can be checked to be active')]
    public function testActiveModule() : void
    {
        $active = $this->moduleManager->getActiveModules();

        /** @var string $last */
        $last = \end($active);

        self::assertTrue($this->moduleManager->isActive($last['name']['internal']));
        self::assertFalse($this->moduleManager->isActive('Invalid'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Modules can be checked to be running')]
    public function testRunningModule() : void
    {
        $module = $this->moduleManager->get('TestModule');
        self::assertTrue($this->moduleManager->isRunning('TestModule'));
        self::assertFalse($this->moduleManager->isRunning('Invalid'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All available modules can be returned')]
    public function testAllModules() : void
    {
        $all = $this->moduleManager->getAllModules();

        self::assertNotEmpty($all);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A module can be installed and its status can be changed')]
    public function testStatus() : void
    {
        $this->moduleManager->install('TestModule');

        $module       = new Module();
        $module->id   = 'TestModule';
        $module->name = 'TestModule';
        $module->path = 'TestModule';
        ModuleMapper::create()->execute($module);

        self::assertTrue($this->moduleManager->deactivate('TestModule'));
        self::assertFalse($this->moduleManager->isActive('TestModule'));

        self::assertTrue($this->moduleManager->activate('TestModule'));

        // this is normally done in the ApiController
        $module->status = ModuleStatus::ACTIVE;
        ModuleMapper::update()->execute($module);

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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing module cannot be re-initialized')]
    public function testInvalidModuleReInit() : void
    {
        $this->moduleManager->reInit('Invalid');
        self::assertFalse($this->moduleManager->isActive('Invalid'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A module can be re-initialized')]
    public function testReInit() : void
    {
        $this->moduleManager->reInit('TestModule');
        self::assertTrue($this->moduleManager->isActive('TestModule'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A module is automatically loaded for its URIs')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Getting language files for an invalid module returns an empty array')]
    public function testGetLanguageForInvalidRequest() : void
    {
        $request = new HttpRequest(new HttpUri('http://127.0.0.1/en/error/invalid'));
        $request->createRequestHashs(0);

        TestUtils::setMember($request, 'hash', ['asdf']);

        self::assertEquals([], $this->moduleManager->getLanguageFiles($request));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Installed modules can be returned')]
    public function testInstalledModules() : void
    {
        $installed = $this->moduleManager->getInstalledModules();

        self::assertNotEmpty($installed);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A module can be checked if it is installed')]
    public function testIsInstalled() : void
    {
        self::assertTrue($this->moduleManager->isInstalled('TestModule'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("Installing an already installed module doesn't perform anything")]
    public function testInstallingAlreadyInstalledModule() : void
    {
        self::assertTrue($this->moduleManager->install('TestModule'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The valid module can be returned')]
    public function testAdminModule() : void
    {
        self::assertInstanceOf('\phpOMS\Module\ModuleAbstract', $this->moduleManager->get('Admin'));
        self::assertInstanceOf('\Modules\Admin\Controller\ApiController', $this->moduleManager->get('Admin'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A module can be uninstalled')]
    public function testUninstall() : void
    {
        $this->moduleManager->uninstall('TestModule');

        self::assertFalse($this->moduleManager->uninstall('TestModule'));

        $module = ModuleMapper::get()->where('id', 'TestModule')->execute();
        ModuleMapper::delete()->execute($module);

        self::assertFalse($this->moduleManager->isActive('TestModule'));
        self::assertFalse($this->moduleManager->isRunning('TestModule'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A empty or invalid module path returns an empty array on module getter functions.')]
    public function testInvalidModulePath() : void
    {
        $moduleManager = new ModuleManager($this->app, __DIR__ . '/Testmodule/');

        self::assertEquals([], $moduleManager->getAllModules());
        self::assertEquals([], $moduleManager->getInstalledModules());
        self::assertEquals([], $moduleManager->getActiveModules(false));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid module name cannot be installed')]
    public function testInvalidModuleInstall() : void
    {
        self::assertFalse($this->moduleManager->install('Invalid'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid module name cannot be uninstalled')]
    public function testInvalidModuleUninstall() : void
    {
        self::assertFalse($this->moduleManager->uninstall('Invalid'));
    }
}
