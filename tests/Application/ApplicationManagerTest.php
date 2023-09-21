<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Application;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Application\ApplicationManager;
use phpOMS\Config\OptionsTrait;
use phpOMS\Config\SettingsInterface;
use phpOMS\DataStorage\Database\Schema\Builder as SchemaBuilder;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\WebRouter;
use phpOMS\System\File\Local\Directory;

/**
 * @testdox phpOMS\tests\Application\ApplicationManagerTest: Application manager
 *
 * @internal
 */
final class ApplicationManagerTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationManager $appManager;

    public static function setUpBeforeClass() : void
    {
        // Setup basic structure needed for applications
        $definitions = \json_decode(\file_get_contents(__DIR__ . '/db.json'), true);
        foreach ($definitions as $definition) {
            SchemaBuilder::createFromSchema($definition, $GLOBALS['dbpool']->get())->execute();
        }
    }

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
                int $unit = null,
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
     * @testdox An application can be installed and uninstalled
     * @covers phpOMS\Application\ApplicationManager
     * @covers phpOMS\Application\InstallerAbstract
     * @covers phpOMS\Application\StatusAbstract
     * @covers phpOMS\Application\UninstallerAbstract
     * @group framework
     */
    public function testInstallUninstall() : void
    {
        self::assertTrue($this->appManager->install(__DIR__ . '/Testapp', __DIR__ . '/Apps/Testapp'));
        self::assertTrue(\is_dir(__DIR__ . '/Apps/Testapp'));
        self::assertTrue(\is_file(__DIR__ . '/Apps/Testapp/css/styles.css'));

        $apps = $this->appManager->getInstalledApplications(false, __DIR__ . '/Apps');
        self::assertTrue(isset($apps['Testapp']));

        $providing = $this->appManager->getProvidingForModule('Navigation');

        self::assertTrue(isset($providing['Testapp']));
        self::assertTrue(\in_array('Navigation', $providing['Testapp']));

        self::assertTrue($this->appManager->uninstall(__DIR__ . '/Apps/Testapp'));
        self::assertFalse(\is_dir(__DIR__ . '/Apps/Testapp'));
    }

    /**
     * @testdox An application can be re-initialized
     * @testdox A module can be re-initialized
     * @covers phpOMS\Application\ApplicationManager
     * @covers phpOMS\Application\InstallerAbstract
     * @covers phpOMS\Application\StatusAbstract
     * @group framework
     */
    public function testReInit() : void
    {
        Directory::delete(__DIR__ . '/Apps/Testapp');

        self::assertTrue($this->appManager->install(__DIR__ . '/Testapp', __DIR__ . '/Apps/Testapp'));

        $this->appManager->reInit(__DIR__ . '/Apps/Testapp');
        self::assertEquals(
            $r1 = include __DIR__ . '/Testapp/Admin/Install/Application/Routes.php',
            $r2 = include __DIR__ . '/Apps/Testapp/Routes.php'
        );

        self::assertEquals(
            $h1 = include __DIR__ . '/Testapp/Admin/Install/Application/Hooks.php',
            $h2 = include __DIR__ . '/Apps/Testapp/Hooks.php'
        );

        $this->appManager->uninstall(__DIR__ . '/Apps/Testapp');

        Directory::delete(__DIR__ . '/Apps/Testapp');
    }

    /**
     * @testdox A invalid application path results in no installation
     * @covers phpOMS\Application\ApplicationManager
     * @group framework
     */
    public function testInvalidSourceDestinationInstallPath() : void
    {
        self::assertFalse($this->appManager->install(__DIR__ . '/invalid', __DIR__));
        self::assertFalse($this->appManager->install(__DIR__, __DIR__));
    }

    /**
     * @testdox A missing installation file results in no installation
     * @covers phpOMS\Application\ApplicationManager
     * @group framework
     */
    public function testMissingInstallerPath() : void
    {
        self::assertFalse($this->appManager->install(__DIR__ . '/MissingInstaller', __DIR__ . '/Apps/MissingInstaller'));
    }

    /**
     * @testdox A missing info file results in no installation
     * @covers phpOMS\Application\ApplicationManager
     * @group framework
     */
    public function testMissingApplicationInfoFile() : void
    {
        self::assertFalse($this->appManager->install(__DIR__ . '/MissingInfo', __DIR__ . '/Apps/MissingInfo'));
    }

    /**
     * @testdox A invalid application path results in no uninstallation
     * @covers phpOMS\Application\ApplicationManager
     * @group framework
     */
    public function testInvalidSourceUninstallPath() : void
    {
        self::assertFalse($this->appManager->uninstall(__DIR__ . '/invalid'));
        self::assertFalse($this->appManager->uninstall(__DIR__));
    }

    /**
     * @testdox A missing uninstallation file results in no uninstallation
     * @covers phpOMS\Application\ApplicationManager
     * @group framework
     */
    public function testMissingUninstallerPath() : void
    {
        self::assertFalse($this->appManager->uninstall(__DIR__ . '/Apps/MissingInstaller'));
    }
}
