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

namespace phpOMS\tests\Dispatcher;

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Localization\Localization;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Module\ModuleAbstract;
use phpOMS\Router\WebRouter;
use phpOMS\Uri\HttpUri;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Dispatcher\Dispatcher::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Dispatcher\DispatcherTest: Dispatcher for executing request endpoints')]
final class DispatcherTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationAbstract $app;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->app = new class() extends ApplicationAbstract {
            protected string $appName = 'Api';
        };

        $this->app->router     = new WebRouter();
        $this->app->dispatcher = new Dispatcher($this->app);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A route can be added and dispatched')]
    public function testControllerInputOutput() : void
    {
        $this->app->dispatcher->controllers['test'] = new class() extends ModuleAbstract {
            public string $name = 'test';

            public function testFunction() { return $this->name; }
        };

        $localization = new Localization();

        self::assertTrue(
            !empty(
                $this->app->dispatcher->dispatch(
                    'test:testFunction',
                    new HttpRequest(new HttpUri(''), $localization),
                    new HttpResponse($localization)
                )
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The dispatcher can dispatch a function/closure')]
    public function testClosure() : void
    {
        $localization = new Localization();

        self::assertTrue(
            !empty(
                $this->app->dispatcher->dispatch(
                    function($req, $resp, $data = null) : bool { return true; },
                    new HttpRequest(new HttpUri(''), $localization),
                    new HttpResponse($localization)
                )
            )
        );

        self::assertTrue(
            !empty(
                $this->app->dispatcher->dispatch(
                    function($req) : bool { return true; }
                )
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The dispatcher can dispatch a method as string representation of a controller')]
    public function testPathMethod() : void
    {
        $localization = new Localization();

        self::assertTrue(
            !empty(
                $this->app->dispatcher->dispatch(
                    'phpOMS\tests\Dispatcher\TestController:testFunction',
                    new HttpRequest(new HttpUri(''), $localization),
                    new HttpResponse($localization)
                )
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The dispatcher can dispatch a method as array representation of a controller')]
    public function testPathMethodInArray() : void
    {
        $localization = new Localization();

        self::assertTrue(
            !empty(
                $this->app->dispatcher->dispatch(
                    ['dest' => 'phpOMS\tests\Dispatcher\TestController:testFunction'],
                    new HttpRequest(new HttpUri(''), $localization),
                    new HttpResponse($localization)
                )
            )
        );

        self::assertTrue(
            !empty(
                $this->app->dispatcher->dispatch(
                    ['dest' => 'phpOMS\tests\Dispatcher\TestController:testFunctionNoPara']
                )
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The dispatcher can dispatch a static method as string representation')]
    public function testPathStatic() : void
    {
        $localization = new Localization();

        self::assertTrue(
            !empty(
                $this->app->dispatcher->dispatch(
                    'phpOMS\tests\Dispatcher\TestController::testFunctionStatic',
                    new HttpRequest(new HttpUri(''), $localization),
                    new HttpResponse($localization)
                )
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The dispatcher can dispatch multiple destinations after another')]
    public function testArray() : void
    {
        $localization = new Localization();

        self::assertTrue(
            !empty(
                $this->app->dispatcher->dispatch(
                    [
                        function($app, $req, $resp, $data = null) : bool { return true; },
                        'phpOMS\tests\Dispatcher\TestController:testFunction',
                        'phpOMS\tests\Dispatcher\TestController::testFunctionStatic',
                    ],
                    new HttpRequest(new HttpUri(''), $localization),
                    new HttpResponse($localization)
                )
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The dispatcher can pass additional data to the destination')]
    public function testArrayWithData() : void
    {
        $localization = new Localization();

        self::assertEquals([2],
            $this->app->dispatcher->dispatch(
                [
                    'dest' => function($app, $req, $resp, $data = null) { return $data; },
                    'data' => 2,
                ],
                new HttpRequest(new HttpUri(''), $localization),
                new HttpResponse($localization)
            )
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid controller path throws a PathException')]
    public function testInvalidControllerPath() : void
    {
        $this->expectException(\Error::class);

        $this->app->dispatcher->dispatch('phpOMS\tests\Dispatcher\TestControllers::testFunctionStatic');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid function path throws a Exception')]
    public function testInvalidControllerFunction() : void
    {
        $this->expectException(\Error::class);

        $this->app->dispatcher->dispatch('phpOMS\tests\Dispatcher\TestController::testFunctionStaticINVALID');
    }
}
