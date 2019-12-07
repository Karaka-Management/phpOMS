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

namespace phpOMS\tests\Module;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Message\Http\Request;
use phpOMS\Message\Http\Response;
use phpOMS\Module\ModuleAbstract;
use phpOMS\Uri\Http;

/**
 * @testdox phpOMS\tests\Module\ModuleAbstractTest: Abstract module
 *
 * @internal
 */
class ModuleAbstractTest extends \PHPUnit\Framework\TestCase
{
    protected $module = null;

    protected function setUp() : void
    {
        $this->module = new class(null) extends ModuleAbstract {
            const MODULE_VERSION           = '1.2.3';
            const MODULE_NAME              = 'Test';
            const MODULE_ID                = 2;
            protected static array $dependencies = [1, 2];

            public function fillJson(Request $request, Response $response, string $status, string $title, string $message, array $data) : void
            {
                $this->fillJsonResponse($request, $response, $status, $title, $message, $data);
            }

            public function fillJsonRaw(Request $request, Response $response, array $data) : void
            {
                $this->fillJsonRawResponse($request, $response, $data);
            }
        };
    }

    /**
     * @testdox The constant values of the abstract module are overwritten by the extension
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testConstants() : void
    {
        self::assertEquals(2, $this->module::MODULE_ID);
        self::assertEquals('1.2.3', $this->module::MODULE_VERSION);
    }

    /**
     * @testdox The name of the module can be returned
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testName() : void
    {
        self::assertEquals('Test', $this->module->getName());
    }

    /**
     * @testdox The dependencies of the module can be returned
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testDependencies() : void
    {
        self::assertEquals([1, 2], $this->module->getDependencies());
    }

    /**
     * @testdox The providings of the module can be returned
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testProviding() : void
    {
        self::assertEquals([], $this->module->getProviding());
    }

    /**
     * @testdox A module can receive information and functionality from another module
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testReceiving() : void
    {
        $this->module->addReceiving('Test2');
        self::assertTrue(\in_array('Test2', $this->module->getReceiving()));
    }

    /**
     * @testdox A invalid language or theme returns in an empty localization/language dataset
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testInvalidLocalization() : void
    {
        self::assertEquals([], $this->module::getLocalization('invalid', 'invalid'));
    }

    /**
     * @testdox The module can automatically generate a json response based on provided data for the frontend
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testFillJson() : void
    {
        $request  = new Request(new Http(''));
        $response = new Response();

        $this->module->fillJson($request, $response, 'OK', 'Test Title', 'Test Message!', [1, 'test string', 'bool' => true]);

        self::assertEquals(
            [
                'status'   => 'OK',
                'title'    => 'Test Title',
                'message'  => 'Test Message!',
                'response' => [1, 'test string', 'bool' => true],
            ],
            $response->get('')
        );
    }

    /**
     * @testdox The module can automatically generate a json response based on provided data
     * @covers phpOMS\Module\ModuleAbstract<extended>
     * @group framework
     */
    public function testFillJsonRaw() : void
    {
        $request  = new Request(new Http(''));
        $response = new Response();

        $this->module->fillJsonRaw($request, $response, [1, 'test string', 'bool' => true]);

        self::assertEquals(
            [1, 'test string', 'bool' => true],
            $response->get('')
        );
    }
}
