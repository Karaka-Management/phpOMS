<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
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
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Module\NullModule::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Module\NullModuleTest: Basic module functionality')]
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

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The null module is an instance of the module abstract')]
    public function testModule() : void
    {
        self::assertInstanceOf('\phpOMS\Module\ModuleAbstract', $this->module);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid module method call will create an error log')]
    public function testInvalidModuleMethodCalls() : void
    {
        $this->module->invalidMethodCall();

        $path = TestUtils::getMember(FileLogger::getInstance(), 'path');
        self::assertStringContainsString('Expected module/controller but got NullModule.', \file_get_contents($path));
    }
}
