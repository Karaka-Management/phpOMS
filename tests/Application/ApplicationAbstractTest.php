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

namespace phpOMS\tests\Application;

use phpOMS\Application\ApplicationAbstract;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Application\ApplicationAbstract::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Application\ApplicationAbstractTest: Application abstraction')]
final class ApplicationAbstractTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Application values can be set and returned')]
    public function testInputOutput() : void
    {
        $obj = new class() extends ApplicationAbstract {};

        $obj->appName = 'Test';
        self::assertEquals('Test', $obj->appName);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Application values cannot be overwritten')]
    public function testInvalidInputOutput() : void
    {
        $obj = new class() extends ApplicationAbstract {};

        $obj->appName = 'Test';
        $obj->appName = 'ABC';
        self::assertEquals('Test', $obj->appName);
    }
}
