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

namespace phpOMS\tests;

use phpOMS\ApplicationAbstract;

/**
 * @testdox phpOMS\tests\ApplicationAbstractTest: Application abstraction
 *
 * @internal
 */
class ApplicationAbstractTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox Application values can be set and returned
     * @covers phpOMS\ApplicationAbstract
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
     * @covers phpOMS\ApplicationAbstract
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
