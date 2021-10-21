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

namespace phpOMS\tests\Router;

use phpOMS\Account\Account;
use phpOMS\Account\PermissionAbstract;
use phpOMS\Account\PermissionType;
use phpOMS\Router\SocketRouter;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Router\SocketRouterTest: Router for socket requests
 *
 * @internal
 */
final class SocketRouterTest extends \PHPUnit\Framework\TestCase
{
    protected SocketRouter $router;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->router = new SocketRouter();
    }

    /**
     * @testdox The route result for an empty request is empty
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testDefault() : void
    {
        self::assertEmpty($this->router->route('some_test route'));
    }

    /**
     * @testdox A none-existing routing file cannot be imported
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testInvalidRoutingFile() : void
    {
        self::assertFalse($this->router->importFromFile(__DIR__ . '/invalidFile.php'));
    }

    /**
     * @testdox A existing routing file can be imported
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testLoadingRoutesFromFile() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/socketRouterTestFile.php'));
    }

    /**
     * @testdox A matching route returns the destinations
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testRouteMatching() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/socketRouterTestFile.php'));

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route('backend_admin -settings=general -t 123')
        );
    }

    /**
     * @testdox The routes can be removed from the router
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testRouteClearing() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/socketRouterTestFile.php'));
        $this->router->clear();

        self::assertEquals(
            [],
            $this->router->route('backend_admin -settings=general -t 123')
        );
    }

    /**
     * @testdox Routes can be added dynamically
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testDynamicRouteAdding() : void
    {
        $this->router->add('^.*backends_admin -settings=general.*$', 'Controller:test');
        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route('backends_admin -settings=general -t 123')
        );
    }

    /**
     * @testdox Routes only match if the permissions match
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testWithValidPermissions() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/socketRouterTestFilePermission.php'));

        $perm = new class(
            null,
            null,
            'TEST',
            'TEST',
            1,
            null,
            null,
            PermissionType::READ
        ) extends PermissionAbstract {};

        $account = new Account();
        $account->addPermission($perm);

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route('backend_admin -settings=general -t 123',
                null,
                null,
                $account
            )
        );
    }

    /**
     * @testdox Routes don't match if the permissions don't match
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testWithInvalidPermissions() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/socketRouterTestFilePermission.php'));

        $perm2 = new class(
            null,
            null,
            'TEST',
            'TEST',
            1,
            null,
            null,
            PermissionType::CREATE
        ) extends PermissionAbstract {};

        $perm3 = new class(
            null,
            null,
            'InvalidModule',
            'InvalidModule',
            1,
            null,
            null,
            PermissionType::READ
        ) extends PermissionAbstract {};

        $perm4 = new class(
            null,
            null,
            'TEST',
            'TEST',
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
            $this->router->route('backend_admin -settings=general -t 123',
                null,
                null,
                $account2
            )
        );
    }

    /**
     * @testdox A data validation pattern validates matches correctly
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testDataValidation() : void
    {
        $this->router->add(
            '^.*backends_admin -settings=general.*$',
            'Controller:test',
            ['test_pattern' => '/^[a-z]*$/']
        );

        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route('backends_admin -settings=general -t 123', null, null, null, ['test_pattern' => 'abcdef'])
        );
    }

    /**
     * @testdox A data validation pattern invalidates missmatches
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testInvalidDataValidation() : void
    {
        $this->router->add(
            '^.*backends_admin -settings=general.*$',
            'Controller:test',
            ['test_pattern' => '/^[a-z]*$/']
        );

        self::assertNotEquals(
            [['dest' => 'Controller:test']],
            $this->router->route('backends_admin -settings=general -t 123', null, null, null, ['test_pattern' => '123'])
        );
    }

    /**
     * @testdox A uri can be used for data population
     * @covers phpOMS\Router\SocketRouter
     * @group framework
     */
    public function testDataFromPattern() : void
    {
        $this->router->add(
            '^.*-settings=general.*$',
            'Controller:test',
            [],
            '/^.*?(settings)=([a-z]*).*?$/'
        );

        self::assertEquals(
            [[
                'dest' => 'Controller:test',
                'data' => [
                    'backends_admin -settings=general -t 123',
                    'settings',
                    'general',
                ],
            ]],
            $this->router->route('backends_admin -settings=general -t 123')
        );
    }
}
