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

namespace phpOMS\tests\Math\Number;

use phpOMS\Math\Number\Numbers;

/**
 * @testdox phpOMS\tests\Math\Number\NumbersTest: General number utilities
 *
 * @internal
 */
final class NumbersTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A number can be checked to be perfect
     * @covers \phpOMS\Math\Number\Numbers
     * @group framework
     */
    public function testPerfect() : void
    {
        self::assertTrue(Numbers::isPerfect(496));
        self::assertTrue(Numbers::isPerfect(8128));
        self::assertFalse(Numbers::isPerfect(7));
        self::assertFalse(Numbers::isPerfect(100));
    }

    /**
     * @testdox A number can be checked to be self-describing
     * @covers \phpOMS\Math\Number\Numbers
     * @group framework
     */
    public function testSelfdescribing() : void
    {
        self::assertFalse(Numbers::isSelfdescribing(2029));
        self::assertTrue(Numbers::isSelfdescribing(21200));
        self::assertTrue(Numbers::isSelfdescribing(3211000));
    }

    /**
     * @testdox A number can be checked to be squared
     * @covers \phpOMS\Math\Number\Numbers
     * @group framework
     */
    public function testSquare() : void
    {
        self::assertTrue(Numbers::isSquare(81));
        self::assertTrue(Numbers::isSquare(6561));
        self::assertFalse(Numbers::isSquare(5545348));
    }

    /**
     * @testdox The amount of trailing zeros can be counted
     * @covers \phpOMS\Math\Number\Numbers
     * @group framework
     */
    public function testZeroCounting() : void
    {
        self::assertEquals(3, Numbers::countTrailingZeros(1000));
        self::assertEquals(5, Numbers::countTrailingZeros(12300000));
    }
}
