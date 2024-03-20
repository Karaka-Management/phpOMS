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
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Number\Numbers::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Number\NumbersTest: General number utilities')]
final class NumbersTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A number can be checked to be perfect')]
    public function testPerfect() : void
    {
        self::assertTrue(Numbers::isPerfect(496));
        self::assertTrue(Numbers::isPerfect(8128));
        self::assertFalse(Numbers::isPerfect(7));
        self::assertFalse(Numbers::isPerfect(100));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A number can be checked to be self-describing')]
    public function testSelfdescribing() : void
    {
        self::assertFalse(Numbers::isSelfdescribing(2029));
        self::assertTrue(Numbers::isSelfdescribing(21200));
        self::assertTrue(Numbers::isSelfdescribing(3211000));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A number can be checked to be squared')]
    public function testSquare() : void
    {
        self::assertTrue(Numbers::isSquare(81));
        self::assertTrue(Numbers::isSquare(6561));
        self::assertFalse(Numbers::isSquare(5545348));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The amount of trailing zeros can be counted')]
    public function testZeroCounting() : void
    {
        self::assertEquals(3, Numbers::countTrailingZeros(1000));
        self::assertEquals(5, Numbers::countTrailingZeros(12300000));
    }
}
