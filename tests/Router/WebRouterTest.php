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
use phpOMS\Autoloader;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Router\RouteStatus;
use phpOMS\Router\RouteVerb;
use phpOMS\Router\WebRouter;
use phpOMS\Uri\HttpUri;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Router\WebRouter::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Router\WebRouterTest: Router for web requests')]
final class WebRouterTest extends \PHPUnit\Framework\TestCase
{
    protected WebRouter $router;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->router = new WebRouter();
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The route result for an empty request is empty')]
    public function testDefault() : void
    {
        self::assertEmpty(
            $this->router->route(
                (new HttpRequest())->uri->getRoute()
            )
        );
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
        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouterTestFile.php'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A matching route returns the destinations')]
    public function testRouteMatching() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouterTestFile.php'));

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backend/admin/settings/general?test')
                ))->uri->getRoute()
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The routes can be removed from the router')]
    public function testRouteClearing() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouterTestFile.php'));
        $this->router->clear();

        self::assertEquals(
            [],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backend/admin/settings/general?test')
                ))->uri->getRoute()
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("Invalid routing verbs don't match even if the route matches")]
    public function testRouteMissMatchingForInvalidVerbs() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouterTestFile.php'));

        self::assertNotEquals(
            [['dest' => '\Modules\Admin\Controller:viewSettingsGeneral']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backend/admin/settings/general?test')
                ))->uri->getRoute(), null, RouteVerb::PUT)
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Routes can be added dynamically')]
    public function testDynamicRouteAdding() : void
    {
        $this->router->add('^.*/backends/admin/settings/general(\?.*$|$)', 'Controller:test', RouteVerb::GET | RouteVerb::SET);
        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backends/admin/settings/general?test')
                ))->uri->getRoute(), null, RouteVerb::ANY)
        );

        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backends/admin/settings/general?test')
                ))->uri->getRoute(), null, RouteVerb::SET)
        );

        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backends/admin/settings/general?test')))->uri->getRoute(), null, RouteVerb::GET)
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Routes which require a CSRF token can only match with a CSRF token')]
    public function testWithCSRF() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouteTestCsrf.php'));

        self::assertEquals(
            [['dest' => '\Modules\Admin\Controller:viewCsrf']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backend/admin/settings/csrf/something?test')
                ))->uri->getRoute(),
                'csrf_string'
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("Routes which require a CSRF token don't match without a CSRF token")]
    public function testWithoutCSRF() : void
    {
        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouteTestCsrf.php'));

        self::assertEquals(
            ['dest' => RouteStatus::INVALID_CSRF],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backend/admin/settings/csrf/something?test')
                ))->uri->getRoute()
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Routes only match if the permissions match')]
    public function testWithValidPermissions() : void
    {
        if (!Autoloader::exists('\Modules\Admin\Controller\Controller')) {
            self::markTestSkipped();
        }

        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouterTestFilePermission.php'));

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
                (new HttpRequest(new HttpUri('http://test.com/backend/admin/settings/general?test')))->uri->getRoute(),
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
        if (!Autoloader::exists('\Modules\Admin\Controller\Controller')) {
            self::markTestSkipped();
        }

        self::assertTrue($this->router->importFromFile(__DIR__ . '/webRouterTestFilePermission.php'));

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
                (new HttpRequest(new HttpUri('http://test.com/backend/admin/settings/general?test')))->uri->getRoute(),
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
            '^.*/backends/admin/settings/general(\?.*$|$)',
            'Controller:test',
            RouteVerb::GET | RouteVerb::SET,
            false,
            ['test_pattern' => '/^[a-z]*$/']
        );

        self::assertEquals(
            [['dest' => 'Controller:test']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backends/admin/settings/general?test')
                ))->uri->getRoute(), null, RouteVerb::ANY, null, null, null, ['test_pattern' => 'abcdef'])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A data validation pattern invalidates miss-matches')]
    public function testInvalidDataValidation() : void
    {
        $this->router->add(
            '^.*/backends/admin/settings/general(\?.*$|$)',
            'Controller:test',
            RouteVerb::GET | RouteVerb::SET,
            false,
            ['test_pattern' => '/^[a-z]*$/']
        );

        self::assertNotEquals(
            [['dest' => 'Controller:test']],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backends/admin/settings/general?test')
                ))->uri->getRoute(), null, RouteVerb::ANY, null, null, null, ['test_pattern' => '123'])
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A uri can be used for data population')]
    public function testDataFromPattern() : void
    {
        $this->router->add(
            '^.*/backends/admin(\?.*$|$)',
            'Controller:test',
            RouteVerb::GET | RouteVerb::SET,
            false,
            [],
            '/^.*?(something)=(?<name>\d*).*?$/'
        );

        self::assertEquals(
            [[
                'dest' => 'Controller:test',
                'data' => [
                    '/backends/admin?something=123&sd=asdf',
                    'something',
                    'name' => '123',
                    '123',
                ],
            ]],
            $this->router->route(
                (new HttpRequest(
                    new HttpUri('http://test.com/backends/admin?something=123&sd=asdf')
                ))->uri->getRoute(), null, RouteVerb::ANY)
        );
    }
}
