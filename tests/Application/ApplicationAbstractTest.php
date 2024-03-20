<?php
/**
 * Jingga
 *
 * PHP Version 8.1
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
 * @testdox phpOMS\tests\Application\ApplicationAbstractTest: Application abstraction
 *
 * @internal
 */
final class ApplicationAbstractTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Application values can be set and returned
     * @covers \phpOMS\Application\ApplicationAbstract<extended>
     * @group framework
     */
    public function testInputOutput() : void
    {
        $obj = new class() extends ApplicationAbstract {};

        $obj->appName = 'Test';
        self::assertEquals('Test', $obj->appName);
    }

    /**
     * @testdox Application values cannot be overwritten
     * @covers \phpOMS\Application\ApplicationAbstract<extended>
     * @group framework
     */
    public function testInvalidInputOutput() : void
    {
        $obj = new class() extends ApplicationAbstract {};

        $obj->appName = 'Test';
        $obj->appName = 'ABC';
        self::assertEquals('Test', $obj->appName);
    }
}
