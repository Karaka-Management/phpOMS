<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
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
 * @testdox phpOMS\tests\Dispatcher\DispatcherTest: Dispatcher for executing request endpoints
 *
 * @internal
 */
final class DispatcherTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationAbstract $app;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->app                    = new class() extends ApplicationAbstract {
            protected string $appName = 'Api';
        };

        $this->app->router     = new WebRouter();
        $this->app->dispatcher = new Dispatcher($this->app);
    }

    /**
     * @testdox The dispatcher has the expected member variables
     * @covers phpOMS\Dispatcher\Dispatcher
     * @group framework
     */
    public function testAttributes() : void
    {
        self::assertObjectHasAttribute('controllers', $this->app->dispatcher);
    }

    public function testControllerInputOutput() : void
    {
        $this->app->dispatcher->set(new class() extends ModuleAbstract {
 public string $name = 'test';

 public function testFunction() { return $this->name; }
 }, 'test');

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

    /**
     * @testdox The dispatcher can dispatch a function/closure
     * @covers phpOMS\Dispatcher\Dispatcher
     * @group framework
     */
    public function testClosure() : void
    {
        $localization = new Localization();

        self::assertTrue(
            !empty(
                $this->app->dispatcher->dispatch(
                    function($req, $resp, $data = null) { return true; },
                    new HttpRequest(new HttpUri(''), $localization),
                    new HttpResponse($localization)
                )
            )
        );

        self::assertTrue(
            !empty(
                $this->app->dispatcher->dispatch(
                    function($req) { return true; }
                )
            )
        );
    }

    /**
     * @testdox The dispatcher can dispatch a method as string representation of a controller
     * @covers phpOMS\Dispatcher\Dispatcher
     * @group framework
     */
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

    /**
     * @testdox The dispatcher can dispatch a method as array representation of a controller
     * @covers phpOMS\Dispatcher\Dispatcher
     * @group framework
     */
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

    /**
     * @testdox The dispatcher can dispatch a static method as string representation
     * @covers phpOMS\Dispatcher\Dispatcher
     * @group framework
     */
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

    /**
     * @testdox The dispatcher can dispatch multiple destinations after another
     * @covers phpOMS\Dispatcher\Dispatcher
     * @group framework
     */
    public function testArray() : void
    {
        $localization = new Localization();

        self::assertTrue(
            !empty(
                $this->app->dispatcher->dispatch(
                    [
                        function($app, $req, $resp, $data = null) { return true; },
                        'phpOMS\tests\Dispatcher\TestController:testFunction',
                        'phpOMS\tests\Dispatcher\TestController::testFunctionStatic',
                    ],
                    new HttpRequest(new HttpUri(''), $localization),
                    new HttpResponse($localization)
                )
            )
        );
    }

    /**
     * @covers phpOMS\Dispatcher\Dispatcher
     * @group framework
     */
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

    /**
     * @testdox A invalid controller path throws a PathException
     * @covers phpOMS\Dispatcher\Dispatcher
     * @group framework
     */
    public function testInvalidControllerPath() : void
    {
        $this->expectException(\phpOMS\System\File\PathException::class);

        $this->app->dispatcher->dispatch('phpOMS\tests\Dispatcher\TestControllers::testFunctionStatic');
    }

    /**
     * @testdox A invalid function path throws a Exception
     * @covers phpOMS\Dispatcher\Dispatcher
     * @group framework
     */
    public function testInvalidControllerFunction() : void
    {
        $this->expectException(\Exception::class);

        $this->app->dispatcher->dispatch('phpOMS\tests\Dispatcher\TestController::testFunctionStaticINVALID');
    }

    /**
     * @testdox A malformed dispatch path throws UnexpectedValueException
     * @covers phpOMS\Dispatcher\Dispatcher
     * @group framework
     */
    public function testInvalidControllerString() : void
    {
        $this->expectException(\UnexpectedValueException::class);

        $this->app->dispatcher->dispatch('phpOMS\tests\Dispatcher\TestController::testFunctionStatic:failure');
    }
}
