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

namespace phpOMS\tests\Router;

use Modules\Admin\Controller\BackendController;
use Modules\Admin\Models\PermissionState;
use phpOMS\Account\Account;
use phpOMS\Account\PermissionAbstract;
use phpOMS\Account\PermissionType;
use phpOMS\Message\Http\Request;
use phpOMS\Router\WebRouter;
use phpOMS\Router\RouteVerb;
use phpOMS\Uri\Http;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
class WebRouterTest extends \PHPUnit\Framework\TestCase
{
    public function testAttributes() : void
    {
        $router = new WebRouter();
        self::assertInstanceOf('\phpOMS\Router\WebRouter', $router);
        self::assertObjectHasAttribute('routes', $router);
    }

    public function testDefault() : void
    {
        $router = new WebRouter();
        self::assertEmpty(
            $router->route(
                (new Request(new Http('')))->getUri()->getRoute()
            )
        );
    }

    public function testInvalidRoutingFile() : void
    {
        $router = new WebRouter();
        self::assertFalse($router->importFromFile(__Dir__ . '/invalidFile.php'));
    }

    public function testLoadingRoutesFromFile() : void
    {
        $router = new WebRouter();
        self::assertTrue($router->importFromFile(__Dir__ . '/webRouterTestFile.php'));
    }

    public function testRouteMatching() : void
    {
        $router = new WebRouter();
        self::assertTrue($router->importFromFile(__Dir__ . '/webRouterTestFile.php'));

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $router->route(
                (new Request(
                    new Http('http://test.com/backend/admin/settings/general/something?test')
                ))->getUri()->getRoute()
            )
        );

        self::assertNotEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $router->route(
                (new Request(
                    new Http('http://test.com/backend/admin/settings/general/something?test')
                ))->getUri()->getRoute(), null, RouteVerb::PUT)
        );
    }

    public function testRouteMissMatchingForInvalidVerbs() : void
    {
        $router = new WebRouter();
        self::assertTrue($router->importFromFile(__Dir__ . '/webRouterTestFile.php'));

        self::assertNotEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $router->route(
                (new Request(
                    new Http('http://test.com/backend/admin/settings/general/something?test')
                ))->getUri()->getRoute(), null, RouteVerb::PUT)
        );
    }

    public function testDynamicRouteAdding() : void
    {
        $router = new WebRouter();

        self::assertNotEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $router->route(
                (new Request(
                    new Http('http://test.com/backends/admin/settings/general/something?test')
                ))->getUri()->getRoute()
            )
        );

        $router->add('^.*/backends/admin/settings/general.*$', 'Controller:test', RouteVerb::GET | RouteVerb::SET);
        self::assertEquals(
            [['dest' => 'Controller:test']],
            $router->route(
                (new Request(
                    new Http('http://test.com/backends/admin/settings/general/something?test')
                ))->getUri()->getRoute(), null, RouteVerb::ANY)
        );

        self::assertEquals(
            [['dest' => 'Controller:test']],
            $router->route(
                (new Request(
                    new Http('http://test.com/backends/admin/settings/general/something?test')
                ))->getUri()->getRoute(), null, RouteVerb::SET)
        );

        self::assertEquals(
            [['dest' => 'Controller:test']],
            $router->route(
                (new Request(
                    new Http('http://test.com/backends/admin/settings/general/something?test')))->getUri()->getRoute(), null, RouteVerb::GET)
        );
    }

    public function testWithCSRF() : void
    {
        $router = new WebRouter();
        self::assertTrue($router->importFromFile(__Dir__ . '/webRouteTestCsrf.php'));

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewCsrf']],
            $router->route(
                (new Request(
                    new Http('http://test.com/backend/admin/settings/csrf/something?test')
                ))->getUri()->getRoute(),
                'csrf_string'
            )
        );

        self::assertEquals(
            [],
            $router->route(
                (new Request(
                    new Http('http://test.com/backend/admin/settings/csrf/something?test')
                ))->getUri()->getRoute()
            )
        );
    }

    public function testWithValidPermissions() : void
    {
        $router = new WebRouter();
        self::assertTrue($router->importFromFile(__Dir__ . '/webRouterTestFilePermission.php'));

        $perm = new class(
            null,
            null,
            BackendController::MODULE_NAME,
            0,
            PermissionState::SETTINGS,
            null,
            null,
            PermissionType::READ
        ) extends PermissionAbstract {};

        $account = new Account();
        $account->addPermission($perm);

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $router->route(
                (new Request(new Http('http://test.com/backend/admin/settings/general/something?test')))->getUri()->getRoute(),
                null,
                RouteVerb::GET,
                null,
                null,
                $account
            )
        );
    }

    public function testWithInvalidPermissions() : void
    {
        $router = new WebRouter();
        self::assertTrue($router->importFromFile(__Dir__ . '/webRouterTestFilePermission.php'));

        $perm2 = new class(
            null,
            null,
            BackendController::MODULE_NAME,
            0,
            PermissionState::SETTINGS,
            null,
            null,
            PermissionType::CREATE
        ) extends PermissionAbstract {};

        $perm3 = new class(
            null,
            null,
            'InvalidModule',
            0,
            PermissionState::SETTINGS,
            null,
            null,
            PermissionType::READ
        ) extends PermissionAbstract {};

        $perm4 = new class(
            null,
            null,
            BackendController::MODULE_NAME,
            0,
            99,
            null,
            null,
            PermissionType::READ
        ) extends PermissionAbstract {};

        $account2 = new Account();
        $account2->addPermission($perm2);
        $account2->addPermission($perm3);
        $account2->addPermission($perm4);

        self::assertNotEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $router->route(
                (new Request(new Http('http://test.com/backend/admin/settings/general/something?test')))->getUri()->getRoute(),
                null,
                RouteVerb::GET,
                null,
                null,
                $account2
            )
        );
    }
}
