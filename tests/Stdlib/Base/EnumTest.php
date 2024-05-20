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

namespace phpOMS\tests\Stdlib\Base;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Stdlib\Base\Enum::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Stdlib\Base\EnumTest: Enum type')]
final class EnumTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A valid enum name can be validated')]
    public function testValidateEnumName() : void
    {
        self::assertTrue(EnumDemo::isValidName('ENUM1'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A invalid enum name doesn't validate")]
    public function testInvalidEnumNameValidation() : void
    {
        self::assertFalse(EnumDemo::isValidName('enum1'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All enum name/value pairs can be returned')]
    public function testOutputValues() : void
    {
        self::assertEquals(['ENUM1' => 1, 'ENUM2' => ';l'], EnumDemo::getConstants());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A valid enum value can be checked for existence')]
    public function testValidateEnumValue() : void
    {
        self::assertTrue(EnumDemo::isValidValue(1));
        self::assertTrue(EnumDemo::isValidValue(';l'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A invalid enum value doesn't validate")]
    public function testInvalidEnumValueValidation() : void
    {
        self::assertFalse(EnumDemo::isValidValue('e3'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A random enum value can be returned')]
    public function testRandomValue() : void
    {
        self::assertTrue(EnumDemo::isValidValue(EnumDemo::getRandom()));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A valid enum name returns the enum value')]
    public function testValueOutput() : void
    {
        self::assertEquals(EnumDemo::ENUM2, EnumDemo::getByName('ENUM2'));
        self::assertEquals(EnumDemo::ENUM2, EnumDemo::getByName('ENUM2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The amount of enum values can be returned')]
    public function testCount() : void
    {
        self::assertEquals(2, EnumDemo::count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A valid enum value returns the enum name')]
    public function testNameOutput() : void
    {
        self::assertEquals('ENUM1', EnumDemo::getName('1'));
        self::assertEquals('ENUM2', EnumDemo::getName(';l'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('Binary flags validate if they are set')]
    public function testFlags() : void
    {
        self::assertTrue(EnumDemo::hasFlag(13, 4));
        self::assertTrue(EnumDemo::hasFlag(13, 1));
        self::assertTrue(EnumDemo::hasFlag(13, 8));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("Binary flags don't validate if they are not set")]
    public function testInvalidFlags() : void
    {
        self::assertFalse(EnumDemo::hasFlag(13, 2));
        self::assertFalse(EnumDemo::hasFlag(13, 16));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid enum name returns null')]
    public function testInvalidConstantException() : void
    {
        self::assertNull(EnumDemo::getByName('ENUM3'));
    }
}
