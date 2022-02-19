<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Stdlib\Base;

/**
 * @testdox phpOMS\tests\Stdlib\Base\EnumArrayTest: Enum array type
 *
 * @internal
 */
final class EnumArrayTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A valid enum name returns the enum value
     * @covers phpOMS\Stdlib\Base\EnumArray<extended>
     * @group framework
     */
    public function testValueOutput() : void
    {
        self::assertEquals(1, EnumArrayDemo::get('ENUM1'));
        self::assertEquals('abc', EnumArrayDemo::get('ENUM2'));
    }

    /**
     * @testdox A valid enum name can be validated
     * @covers phpOMS\Stdlib\Base\EnumArray<extended>
     * @group framework
     */
    public function testValidateEnumName() : void
    {
        self::assertTrue(EnumArrayDemo::isValidName('ENUM1'));
    }

    /**
     * @testdox A invalid enum name doesn't validate
     * @covers phpOMS\Stdlib\Base\EnumArray<extended>
     * @group framework
     */
    public function testInvalidEnumNameValidation() : void
    {
        self::assertFalse(EnumArrayDemo::isValidName('enum1'));
    }

    /**
     * @testdox All enum name/value pairs can be returned
     * @covers phpOMS\Stdlib\Base\EnumArray<extended>
     * @group framework
     */
    public function testOutputValues() : void
    {
        self::assertEquals(['ENUM1' => 1, 'ENUM2' => 'abc'], EnumArrayDemo::getConstants());
    }

    /**
     * @testdox A valid enum value can be checked for existence
     * @covers phpOMS\Stdlib\Base\EnumArray<extended>
     * @group framework
     */
    public function testValidateEnumValue() : void
    {
        self::assertTrue(EnumArrayDemo::isValidValue(1));
        self::assertTrue(EnumArrayDemo::isValidValue('abc'));
    }

    /**
     * @testdox A invalid enum value doesn't validate
     * @covers phpOMS\Stdlib\Base\EnumArray<extended>
     * @group framework
     */
    public function testInvalidEnumValueValidation() : void
    {
        self::assertFalse(EnumArrayDemo::isValidValue('e3'));
    }

    /**
     * @testdox A invalid enum name throws a OutOfBoundsException
     * @covers phpOMS\Stdlib\Base\EnumArray<extended>
     * @group framework
     */
    public function testInvalidConstantException() : void
    {
        $this->expectException(\OutOfBoundsException::class);

        EnumArrayDemo::get('enum2');
    }

    /**
     * @testdox The amount of enum values can be returned
     * @covers phpOMS\Stdlib\Base\EnumArray<extended>
     * @group framework
     */
    public function testCount() : void
    {
        self::assertEquals(2, EnumArrayDemo::count());
    }

    /**
     * @testdox A random enum value can be returned
     * @covers phpOMS\Stdlib\Base\EnumArray<extended>
     * @group framework
     */
    public function testRandomValue() : void
    {
        self::assertTrue(EnumArrayDemo::isValidValue(EnumArrayDemo::getRandom()));
    }
}
