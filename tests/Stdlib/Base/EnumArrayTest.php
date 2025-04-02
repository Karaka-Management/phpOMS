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
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Stdlib\Base\EnumArray::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Stdlib\Base\EnumArrayTest: Enum array type')]
final class EnumArrayTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A valid enum name returns the enum value')]
    public function testValueOutput() : void
    {
        self::assertEquals(1, EnumArrayDemo::get('ENUM1'));
        self::assertEquals('abc', EnumArrayDemo::get('ENUM2'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A valid enum name can be validated')]
    public function testValidateEnumName() : void
    {
        self::assertTrue(EnumArrayDemo::isValidName('ENUM1'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A invalid enum name doesn't validate")]
    public function testInvalidEnumNameValidation() : void
    {
        self::assertFalse(EnumArrayDemo::isValidName('enum1'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('All enum name/value pairs can be returned')]
    public function testOutputValues() : void
    {
        self::assertEquals(['ENUM1' => 1, 'ENUM2' => 'abc'], EnumArrayDemo::getConstants());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A valid enum value can be checked for existence')]
    public function testValidateEnumValue() : void
    {
        self::assertTrue(EnumArrayDemo::isValidValue(1));
        self::assertTrue(EnumArrayDemo::isValidValue('abc'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox("A invalid enum value doesn't validate")]
    public function testInvalidEnumValueValidation() : void
    {
        self::assertFalse(EnumArrayDemo::isValidValue('e3'));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A invalid enum name throws a OutOfBoundsException')]
    public function testInvalidConstantException() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        EnumArrayDemo::get('enum2');
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The amount of enum values can be returned')]
    public function testCount() : void
    {
        self::assertEquals(2, EnumArrayDemo::count());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A random enum value can be returned')]
    public function testRandomValue() : void
    {
        self::assertTrue(EnumArrayDemo::isValidValue(EnumArrayDemo::getRandom()));
    }
}
