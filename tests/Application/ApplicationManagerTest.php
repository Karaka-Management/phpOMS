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

namespace phpOMS\tests\Application;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Application\ApplicationManager;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\Router\WebRouter;
use phpOMS\Dispatcher\Dispatcher;
use Model\CoreSettings;
use Modules\CMS\Models\Application;
use phpOMS\Module\ModuleManager;

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

        $app->appName       = 'Api';
        $app->dbPool        = $GLOBALS['dbpool'];
        $app->router        = new WebRouter();
        $app->dispatcher    = new Dispatcher($app);
        $app->appSettings   = new CoreSettings($app->dbPool->get('admin'));
        $app->moduleManager = new ModuleManager($app, __DIR__ . '/../../../Modules');

        $this->appManager = new ApplicationManager($app->moduleManager);
    }

    public function testInstall() : void
    {
        self::markTestIncomplete();
    }

    public function testInvalidSourceDestinationInstallPath() : void
    {
        self::assertFalse($this->appManager->install(__DIR__ . '/invalid', __DIR__));
        self::assertFalse($this->appManager->install(__DIR__, __DIR__));
    }

    public function testMissingApplicationInfoFile() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        self::assertFalse($this->appManager->install(__DIR__, __DIR__ . '/newapp'));
    }

    public function testInstallFromModules() : void
    {
        self::markTestIncomplete();
    }
}
