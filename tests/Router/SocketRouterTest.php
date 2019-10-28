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
use phpOMS\Router\SocketRouter;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
class SocketRouterTest extends \PHPUnit\Framework\TestCase
{
    public function testAttributes() : void
    {
        $router = new SocketRouter();
        self::assertInstanceOf('\phpOMS\Router\SocketRouter', $router);
        self::assertObjectHasAttribute('routes', $router);
    }

    public function testDefault() : void
    {
        $router = new SocketRouter();
        self::assertEmpty($router->route('some_test route'));
    }

    public function testInvalidRoutingFile() : void
    {
        $router = new SocketRouter();
        self::assertFalse($router->importFromFile(__Dir__ . '/invalidFile.php'));
    }

    public function testLoadingRoutesFromFile() : void
    {
        $router = new SocketRouter();
        self::assertTrue($router->importFromFile(__Dir__ . '/socketRouterTestFile.php'));
    }

    public function testRouteMatching() : void
    {
        $router = new SocketRouter();
        self::assertTrue($router->importFromFile(__Dir__ . '/socketRouterTestFile.php'));

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $router->route('backend_admin -settings=general -t 123')
        );
    }

    public function testDynamicRouteAdding() : void
    {
        $router = new SocketRouter();
        self::assertNotEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $router->route('backends_admin -settings=general -t 123')
        );

        $router->add('^.*backends_admin -settings=general.*$', 'Controller:test');
        self::assertEquals(
            [['dest' => 'Controller:test']],
            $router->route('backends_admin -settings=general -t 123')
        );
    }

    public function testWithValidPermissions() : void
    {
        $router = new SocketRouter();
        self::assertTrue($router->importFromFile(__Dir__ . '/socketRouterTestFilePermission.php'));

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
            $router->route('backend_admin -settings=general -t 123',
                null,
                null,
                $account
            )
        );
    }

    public function testWithInvalidPermissions() : void
    {
        $router = new SocketRouter();
        self::assertTrue($router->importFromFile(__Dir__ . '/socketRouterTestFilePermission.php'));

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
            $router->route('backend_admin -settings=general -t 123',
                null,
                null,
                $account2
            )
        );
    }
}
