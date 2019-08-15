<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Math\Number;

use phpOMS\Math\Number\Numbers;

/**
 * @internal
 */
class NumbersTest extends \PHPUnit\Framework\TestCase
{
    public function testPerfect() : void
    {
        self::assertTrue(Numbers::isPerfect(496));
        self::assertTrue(Numbers::isPerfect(8128));
        self::assertFalse(Numbers::isPerfect(7));
        self::assertFalse(Numbers::isPerfect(100));
    }

    public function testSelfdescribing() : void
    {
        self::assertFalse(Numbers::isSelfdescribing(2029));
        self::assertTrue(Numbers::isSelfdescribing(21200));
        self::assertTrue(Numbers::isSelfdescribing(3211000));
    }

    public function testSquare() : void
    {
        self::assertTrue(Numbers::isSquare(81));
        self::assertTrue(Numbers::isSquare(6561));
        self::assertFalse(Numbers::isSquare(5545348));
    }

    public function testZeroCounting() : void
    {
        self::assertEquals(3, Numbers::countTrailingZeros(1000));
        self::assertEquals(5, Numbers::countTrailingZeros(12300000));
    }
}
