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

namespace phpOMS\tests\Dispatcher;

use phpOMS\ApplicationAbstract;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Localization\Localization;
use phpOMS\Message\Http\Request;
use phpOMS\Message\Http\Response;
use phpOMS\Router\WebRouter;
use phpOMS\Uri\Http;

require_once __DIR__ . '/../Autoloader.php';

/**
 * @testdox phpOMS\tests\Dispatcher\DispatcherTest: Dispatcher for executing request endpoints
 *
 * @internal
 */
class DispatcherTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationAbstract $app;

    protected function setUp() : void
    {
        $this->app             = new class() extends ApplicationAbstract { protected string $appName = 'Api'; };
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

    /**
     * @testdox The disptacher can dispatch a function/closure
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
                    new Request(new Http(''), $localization),
                    new Response($localization)
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
     * @testdox The disptacher can dispatch a method as string representation of a controller
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
                    new Request(new Http(''), $localization),
                    new Response($localization)
                )
            )
        );
    }

    /**
     * @testdox The disptacher can dispatch a method as array representation of a controller
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
                    new Request(new Http(''), $localization),
                    new Response($localization)
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
     * @testdox The disptacher can dispatch a static method as string representation
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
                    new Request(new Http(''), $localization),
                    new Response($localization)
                )
            )
        );
    }

    /**
     * @testdox The disptacher can dispatch multiple destinations after another
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
                        function($req, $resp, $data = null) { return true; },
                        'phpOMS\tests\Dispatcher\TestController:testFunction',
                        'phpOMS\tests\Dispatcher\TestController::testFunctionStatic',
                    ],
                    new Request(new Http(''), $localization),
                    new Response($localization)
                )
            )
        );
    }

    /**
     * @testdox A invalid destination type throws UnexpectedValueException
     * @covers phpOMS\Dispatcher\Dispatcher
     * @group framework
     */
    public function testInvalidDestination() : void
    {
        self::expectException(\UnexpectedValueException::class);

        $this->app->dispatcher->dispatch(true);
    }

    /**
     * @testdox A invalid controller path thorws a PathException
     * @covers phpOMS\Dispatcher\Dispatcher
     * @group framework
     */
    public function testInvalidControllerPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        $this->app->dispatcher->dispatch('phpOMS\tests\Dispatcher\TestControllers::testFunctionStatic');
    }

    /**
     * @testdox A invalid function path thorws a Exception
     * @covers phpOMS\Dispatcher\Dispatcher
     * @group framework
     */
    public function testInvalidControllerFunction() : void
    {
        self::expectException(\Exception::class);

        $this->app->dispatcher->dispatch('phpOMS\tests\Dispatcher\TestController::testFunctionStaticINVALID');
    }

    /**
     * @testdox A malformed dispatch path thorws UnexpectedValueException
     * @covers phpOMS\Dispatcher\Dispatcher
     * @group framework
     */
    public function testInvalidControllerString() : void
    {
        self::expectException(\UnexpectedValueException::class);

        $this->app->dispatcher->dispatch('phpOMS\tests\Dispatcher\TestController::testFunctionStatic:failure');
    }
}
