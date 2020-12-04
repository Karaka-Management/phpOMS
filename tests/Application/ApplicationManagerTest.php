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

namespace phpOMS\tests\Application;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Application\ApplicationManager;
use phpOMS\Config\SettingsInterface;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\WebRouter;

/**
 * @testdox phpOMS\tests\Application\ApplicationManagerTest: Application manager
 *
 * @internal
 */
class ApplicationManagerTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationManager $appManager;

    protected function setUp() : void
    {
        $app                          = new class() extends ApplicationAbstract {
            protected string $appName = 'Api';
        };

        $app->appName     = 'Api';
        $app->dbPool      = $GLOBALS['dbpool'];
        $app->router      = new WebRouter();
        $app->dispatcher  = new Dispatcher($app);
        $app->appSettings = new class implements SettingsInterface {
            public function get(
                mixed $ids = null,
                string|array $names = null,
                string $module = null,
                int $group = null,
                int $account = null
            ) : mixed {
                return '';
            }

            public function set(array $options, bool $store = false) : void {}

            public function save(array $options = []) : void {}

            public function create(array $options = []) : void {}
        };

        $app->moduleManager = new ModuleManager($app, __DIR__ . '/../../../Modules/');

        $this->appManager = new ApplicationManager($app->moduleManager);
    }

    /**
     * @covers phpOMS\Application\ApplicationManager
     * @group framework
     */
    public function testInstall() : void
    {
        self::markTestIncomplete();
    }

    /**
     * @covers phpOMS\Application\ApplicationManager
     * @group framework
     */
    public function testInvalidSourceDestinationInstallPath() : void
    {
        self::assertFalse($this->appManager->install(__DIR__ . '/invalid', __DIR__));
        self::assertFalse($this->appManager->install(__DIR__, __DIR__));
    }

    /**
     * @covers phpOMS\Application\ApplicationManager
     * @group framework
     */
    public function testMissingApplicationInfoFile() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        self::assertFalse($this->appManager->install(__DIR__, __DIR__ . '/newapp'));
    }

    /**
     * @covers phpOMS\Application\ApplicationManager
     * @group framework
     */
    public function testInstallFromModules() : void
    {
        self::markTestIncomplete();
    }
}
