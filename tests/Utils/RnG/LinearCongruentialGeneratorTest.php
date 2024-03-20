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

namespace phpOMS\tests\Utils\RnG;

use phpOMS\Utils\RnG\LinearCongruentialGenerator;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Utils\RnG\LinearCongruentialGenerator::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Utils\RnG\LinearCongruentialGeneratorTest: Random number generator')]
final class LinearCongruentialGeneratorTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The bsd random number generator starts with the correct sequence')]
    public function testBsdRng() : void
    {
        self::assertEquals(12345, LinearCongruentialGenerator::bsd());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The same bsd seed generates the same random number')]
    public function testBsdRngEqual() : void
    {
        self::assertEquals(LinearCongruentialGenerator::bsd(1), LinearCongruentialGenerator::bsd(1));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different bsd seeds generate different random numbers')]
    public function testBsdRngNotEqual() : void
    {
        self::assertNotEquals(LinearCongruentialGenerator::bsd(0), LinearCongruentialGenerator::bsd(1));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The msvcrt random number generator starts with the correct sequence')]
    public function testMsRng() : void
    {
        self::assertEquals(38, LinearCongruentialGenerator::msvcrt());
        self::assertEquals(7719, LinearCongruentialGenerator::msvcrt());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The same msvcrt seed generates the same random number')]
    public function testMsRngEqual() : void
    {
        self::assertEquals(LinearCongruentialGenerator::msvcrt(1), LinearCongruentialGenerator::msvcrt(1));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Different msvcrt seeds generate different random numbers')]
    public function testMsRngNotEqual() : void
    {
        self::assertNotEquals(LinearCongruentialGenerator::msvcrt(0), LinearCongruentialGenerator::msvcrt(1));
    }
}
