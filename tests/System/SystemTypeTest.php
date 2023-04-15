<?php
/**
 * Karaka
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

namespace phpOMS\tests\System;

require_once __DIR__ . '/../Autoloader.php';

use phpOMS\System\SystemType;

/**
 * @testdox phpOMS\tests\System\SystemTypeTest: System type
 * @internal
 */
final class SystemTypeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The system type enum has the correct amount of values
     * @group framework
     * @coversNothing
     */
    public function testEnumCount() : void
    {
        self::assertCount(4, SystemType::getConstants());
    }

    /**
     * @testdox The system type enum has only unique values
     * @group framework
     * @coversNothing
     */
    public function testUnique() : void
    {
        self::assertEquals(SystemType::getConstants(), \array_unique(SystemType::getConstants()));
    }

    /**
     * @testdox The system type enum has the correct values
     * @group framework
     * @coversNothing
     */
    public function testEnums() : void
    {
        self::assertEquals(1, SystemType::UNKNOWN);
        self::assertEquals(2, SystemType::WIN);
        self::assertEquals(3, SystemType::LINUX);
        self::assertEquals(4, SystemType::OSX);
    }
}
