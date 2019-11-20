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
 * @testdox phpOMS\tests\Router\WebRouterTest: Router for web requests
 *
 * @internal
 */
class WebRouterTest extends \PHPUnit\Framework\TestCase
{
    protected WebRouter $router;

    protected function setUp() : void
    {
        $this->router = new WebRouter();
    }

    /**
     * @testdox The route result for an empty request is empty
     * @covers phpOMS\Router\WebRouter
     */
    public function testDefault() : void
    {
        self::assertEmpty(
            $this->router->route(
                (new Request(new Http('')))->getUri()->getRoute()
            )
        );
    }

    /**
     * @testdox A none-existing routing file cannot be imported
     * @covers phpOMS\Router\WebRouter
     */
    public function testInvalidRoutingFile() : void
    {
        self::assertFalse($this->router->importFromFile(__Dir__ . '/invalidFile.php'));
    }

    /**
     * @testdox A existing routing file can be imported
     * @covers phpOMS\Router\WebRouter
     */
    public function testLoadingRoutesFromFile() : void
    {
        self::assertTrue($this->router->importFromFile(__Dir__ . '/webRouterTestFile.php'));
    }

    /**
     * @testdox A matching route returns the destinations
     * @covers phpOMS\Router\WebRouter
     */
    public function testRouteMatching() : void
    {
        self::assertTrue($this->router->importFromFile(__Dir__ . '/webRouterTestFile.php'));

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route(
                (new Request(
                    new Http('http://test.com/backend/admin/settings/general/something?test')
                ))->getUri()->getRoute()
            )
        );
    }

    /**
     * @testdox Invalid routing verbs don't match even if the route matches
     * @covers phpOMS\Router\WebRouter
     */
    public function testRouteMissMatchingForInvalidVerbs() : void
    {
        self::assertTrue($this->router->importFromFile(__Dir__ . '/webRouterTestFile.php'));

        self::assertNotEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route(
                (new Request(
                    new Http('http://test.com/backend/admin/settings/general/something?test')
                ))->getUri()->getRoute(), null, RouteVerb::PUT)
        );
    }

    /**
     * @testdox Routes can be added dynamically
     * @covers phpOMS\Router\WebRouter
     */
    public function testDynamicRouteAdding() : void
    {
        self::assertNotEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route(
                (new Request(
                    new Http('http://test.com/backends/admin/settings/general/something?test')
                ))->getUri()->getRoute()
            )
        );

        $this->router->add('^.*/backends/admin/settings/general.*$', 'Controller:test', RouteVerb::GET | RouteVerb::SET);
        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route(
                (new Request(
                    new Http('http://test.com/backends/admin/settings/general/something?test')
                ))->getUri()->getRoute(), null, RouteVerb::ANY)
        );

        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route(
                (new Request(
                    new Http('http://test.com/backends/admin/settings/general/something?test')
                ))->getUri()->getRoute(), null, RouteVerb::SET)
        );

        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route(
                (new Request(
                    new Http('http://test.com/backends/admin/settings/general/something?test')))->getUri()->getRoute(), null, RouteVerb::GET)
        );
    }

    /**
     * @testdox Routes which require a CSRF token can only match with a CSRF token
     * @covers phpOMS\Router\WebRouter
     */
    public function testWithCSRF() : void
    {
        self::assertTrue($this->router->importFromFile(__Dir__ . '/webRouteTestCsrf.php'));

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewCsrf']],
            $this->router->route(
                (new Request(
                    new Http('http://test.com/backend/admin/settings/csrf/something?test')
                ))->getUri()->getRoute(),
                'csrf_string'
            )
        );
    }

    /**
     * @testdox Routes which require a CSRF token don't match without a CSRF token
     * @covers phpOMS\Router\WebRouter
     */
    public function testWithoutCSRF() : void
    {
        self::assertTrue($this->router->importFromFile(__Dir__ . '/webRouteTestCsrf.php'));

        self::assertEquals(
            [],
            $this->router->route(
                (new Request(
                    new Http('http://test.com/backend/admin/settings/csrf/something?test')
                ))->getUri()->getRoute()
            )
        );
    }

    /**
     * @testdox Routes only match if the permissions match
     * @covers phpOMS\Router\WebRouter
     */
    public function testWithValidPermissions() : void
    {
        self::assertTrue($this->router->importFromFile(__Dir__ . '/webRouterTestFilePermission.php'));

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
            $this->router->route(
                (new Request(new Http('http://test.com/backend/admin/settings/general/something?test')))->getUri()->getRoute(),
                null,
                RouteVerb::GET,
                null,
                null,
                $account
            )
        );
    }

    /**
     * @testdox Routes don't match if the permissions don't match
     * @covers phpOMS\Router\WebRouter
     */
    public function testWithInvalidPermissions() : void
    {
        self::assertTrue($this->router->importFromFile(__Dir__ . '/webRouterTestFilePermission.php'));

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
            $this->router->route(
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
