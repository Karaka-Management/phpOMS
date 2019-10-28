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
 * @internal
 */
class DispatcherTest extends \PHPUnit\Framework\TestCase
{
    protected $app = null;

    protected function setUp() : void
    {
        $this->app             = new class() extends ApplicationAbstract { protected string $appName = 'Api'; };
        $this->app->router     = new WebRouter();
        $this->app->dispatcher = new Dispatcher($this->app);
    }

    public function testAttributes() : void
    {
        self::assertObjectHasAttribute('controllers', $this->app->dispatcher);
    }

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

    public function testInvalidDestination() : void
    {
        self::expectException(\UnexpectedValueException::class);

        $this->app->dispatcher->dispatch(true);
    }

    public function testInvalidControllerPath() : void
    {
        self::expectException(\phpOMS\System\File\PathException::class);

        $this->app->dispatcher->dispatch('phpOMS\tests\Dispatcher\TestControllers::testFunctionStatic');
    }

    public function testInvalidControllerFunction() : void
    {
        self::expectException(\Exception::class);

        $this->app->dispatcher->dispatch('phpOMS\tests\Dispatcher\TestController::testFunctionStaticINVALID');
    }

    public function testInvalidControllerString() : void
    {
        self::expectException(\UnexpectedValueException::class);

        $this->app->dispatcher->dispatch('phpOMS\tests\Dispatcher\TestController::testFunctionStatic:failure');
    }
}
