<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Dispatcher;

use phpOMS\ApplicationAbstract;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Localization\Localization;
use phpOMS\Message\Http\Request;
use phpOMS\Message\Http\Response;
use phpOMS\Router\Router;
use phpOMS\Uri\Http;

require_once __DIR__ . '/../Autoloader.php';

class DispatcherTest extends \PHPUnit\Framework\TestCase
{
    protected $app = null;

    protected function setUp() : void
    {
        $this->app             = new class extends ApplicationAbstract { protected $appName = 'Api'; };
        $this->app->router     = new Router();
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

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidDestination() : void
    {
        $this->app->dispatcher->dispatch(true);
    }

    /**
     * @expectedException \phpOMS\System\File\PathException
     */
    public function testInvalidControllerPath() : void
    {
        $this->app->dispatcher->dispatch('phpOMS\tests\Dispatcher\TestControllers::testFunctionStatic');
    }

    /**
     * @expectedException \Exception
     */
    public function testInvalidControllerFunction() : void
    {
        $this->app->dispatcher->dispatch('phpOMS\tests\Dispatcher\TestController::testFunctionStaticINVALID');
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testInvalidControllerString() : void
    {
        $this->app->dispatcher->dispatch('phpOMS\tests\Dispatcher\TestController::testFunctionStatic:failure');
    }
}
