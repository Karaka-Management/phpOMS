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
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Module;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\Application\ApplicationAbstract;
use phpOMS\Log\FileLogger;
use phpOMS\Module\NullModule;
use phpOMS\Utils\TestUtils;

/**
 * @testdox phpOMS\tests\Module\NullModuleTest: Basic module functionality
 *
 * @internal
 */
final class NullModuleTest extends \PHPUnit\Framework\TestCase
{
    protected NullModule $module;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $app = new class() extends ApplicationAbstract
        {
        };

        $this->module = new NullModule($app);
    }

    /**
     * @group framework
     */
    public function testModule() : void
    {
        self::assertInstanceOf('\phpOMS\Module\ModuleAbstract', $this->module);
    }

    /**
     * @testdox A invalid module method call will create an error log
     * @covers phpOMS\Module\NullModule
     * @group framework
     */
    public function testInvalidModuleMethodCalls() : void
    {
        $this->module->invalidMethodCall();

        $path = TestUtils::getMember(FileLogger::getInstance(), 'path');
        self::assertStringContainsString('Expected module/controller but got NullModule.', \file_get_contents($path));
    }
}
