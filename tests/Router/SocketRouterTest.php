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

namespace phpOMS\tests\Router;

use phpOMS\Account\Account;
use phpOMS\Account\NullAccount;
use phpOMS\Account\PermissionAbstract;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;
use phpOMS\Router\SocketRouter;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Router\SocketRouter::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Router\SocketRouterTest: Router for socket requests')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The route result for an empty request is empty')]
    public function testDefault() : void
    {
        self::assertEmpty($this->router->route('some_test route'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A none-existing routing file cannot be imported')]
    public function testInvalidRoutingFile() : void
    {
        self::assertFalse($this->router->importFromFile(__DIR__ . '/invalidFile.php'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A existing routing file can be imported')]
    public function testLoadingRoutesFromFile() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/socketRouterTestFile.php'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A matching route returns the destinations')]
    public function testRouteMatching() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/socketRouterTestFile.php'));

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route('backend_admin -settings=general -t 123')
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The routes can be removed from the router')]
    public function testRouteClearing() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/socketRouterTestFile.php'));
        $this->router->clear();

        self::assertEquals(
            [],
            $this->router->route('backend_admin -settings=general -t 123')
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Routes can be added dynamically')]
    public function testDynamicRouteAdding() : void
    {
        $this->router->add('^.*backends_admin -settings=general( \-.*$|$)', 'Controller:test');
        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route('backends_admin -settings=general -t 123')
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Routes only match if the permissions match')]
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

        $account = new NullAccount(1);
        $account->addPermission($perm);

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route(
                'backend_admin -settings=general -t 123',
                null,
                RouteVerb::GET,
                null,
                null,
                $account
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("Routes don't match if the permissions don't match")]
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
            $this->router->route(
                'backend_admin -settings=general -t 123',
                null,
                RouteVerb::GET,
                null,
                null,
                $account2
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A data validation pattern validates matches correctly')]
    public function testDataValidation() : void
    {
        $this->router->add(
            '^.*backends_admin -settings=general( \-.*$|$)',
            'Controller:test',
            validation: ['test_pattern' => '/^[a-z]*$/']
        );

        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route('backends_admin -settings=general -t 123', null, RouteVerb::GET, null, null, null, ['test_pattern' => 'abcdef'])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A data validation pattern invalidates missmatches')]
    public function testInvalidDataValidation() : void
    {
        $this->router->add(
            '^.*backends_admin -settings=general( \-.*$|$)',
            'Controller:test',
            validation: ['test_pattern' => '/^[a-z]*$/']
        );

        self::assertNotEquals(
            [['dest' => 'Controller:test']],
            $this->router->route('backends_admin -settings=general -t 123', null, RouteVerb::GET, null, null, null, ['test_pattern' => '123'])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A uri can be used for data population')]
    public function testDataFromPattern() : void
    {
        $this->router->add(
            '^.*-settings=general( \-.*$|$)',
            'Controller:test',
            dataPattern: '/^.*?(settings)=([a-z]*).*?$/'
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
