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
use phpOMS\Config\OptionsTrait;
use phpOMS\Config\SettingsInterface;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\WebRouter;
use phpOMS\System\File\Local\Directory;

/**
 * @testdox phpOMS\tests\Application\ApplicationManagerTest: Application manager
 *
 * @internal
 */
class ApplicationManagerTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationManager $appManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $app                          = new class() extends ApplicationAbstract {
            protected string $appName = 'Api';
        };

        $app->appName     = 'Api';
        $app->dbPool      = $GLOBALS['dbpool'];
        $app->router      = new WebRouter();
        $app->dispatcher  = new Dispatcher($app);
        $app->appSettings = new class() implements SettingsInterface {
            use OptionsTrait;

            public function get(
                mixed $ids = null,
                string | array $names = null,
                int $app = null,
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

        $this->appManager = new ApplicationManager($app);
    }

    /**
     * @covers phpOMS\Application\ApplicationManager
     * @group framework
     */
    public function testInstall() : void
    {
        self::assertTrue($this->appManager->install(__DIR__ . '/Testapp', __DIR__ . '/Apps/Testapp'));
        self::assertTrue(\is_dir(__DIR__ . '/Apps/Testapp'));
        self::assertTrue(\is_file(__DIR__ . '/Apps/Testapp/css/styles.css'));
        Directory::delete(__DIR__ . '/Apps/Testapp');
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
        self::assertFalse($this->appManager->install(__DIR__, __DIR__ . '/newapp', __DIR__ . '/Apps/newapp'));
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
