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

namespace phpOMS\tests\Stdlib\Base;

/**
 * @testdox phpOMS\tests\Stdlib\Base\EnumTest: Enum type
 *
 * @internal
 */
final class EnumTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A valid enum name can be validated
     * @covers phpOMS\Stdlib\Base\Enum<extended>
     * @group framework
     */
    public function testValidateEnumName() : void
    {
        self::assertTrue(EnumDemo::isValidName('ENUM1'));
    }

    /**
     * @testdox A invalid enum name doesn't validate
     * @covers phpOMS\Stdlib\Base\Enum<extended>
     * @group framework
     */
    public function testInvalidEnumNameValidation() : void
    {
        self::assertFalse(EnumDemo::isValidName('enum1'));
    }

    /**
     * @testdox All enum name/value pairs can be returned
     * @covers phpOMS\Stdlib\Base\Enum<extended>
     * @group framework
     */
    public function testOutputValues() : void
    {
        self::assertEquals(['ENUM1' => 1, 'ENUM2' => ';l'], EnumDemo::getConstants());
    }

    /**
     * @testdox A valid enum value can be checked for existence
     * @covers phpOMS\Stdlib\Base\Enum<extended>
     * @group framework
     */
    public function testValidateEnumValue() : void
    {
        self::assertTrue(EnumDemo::isValidValue(1));
        self::assertTrue(EnumDemo::isValidValue(';l'));
    }

    /**
     * @testdox A invalid enum value doesn't validate
     * @covers phpOMS\Stdlib\Base\Enum<extended>
     * @group framework
     */
    public function testInvalidEnumValueValidation() : void
    {
        self::assertFalse(EnumDemo::isValidValue('e3'));
    }

    /**
     * @testdox A random enum value can be returned
     * @covers phpOMS\Stdlib\Base\Enum<extended>
     * @group framework
     */
    public function testRandomValue() : void
    {
        self::assertTrue(EnumDemo::isValidValue(EnumDemo::getRandom()));
    }

    /**
     * @testdox A valid enum name returns the enum value
     * @covers phpOMS\Stdlib\Base\Enum<extended>
     * @group framework
     */
    public function testValueOutput() : void
    {
        self::assertEquals(EnumDemo::ENUM2, EnumDemo::getByName('ENUM2'));
        self::assertEquals(EnumDemo::ENUM2, EnumDemo::getByName('ENUM2'));
    }

    /**
     * @testdox The amount of enum values can be returned
     * @covers phpOMS\Stdlib\Base\Enum<extended>
     * @group framework
     */
    public function testCount() : void
    {
        self::assertEquals(2, EnumDemo::count());
    }

    /**
     * @testdox A valid enum value returns the enum name
     * @covers phpOMS\Stdlib\Base\Enum<extended>
     * @group framework
     */
    public function testNameOutput() : void
    {
        self::assertEquals('ENUM1', EnumDemo::getName('1'));
        self::assertEquals('ENUM2', EnumDemo::getName(';l'));
    }

    /**
     * @testdox Binary flags validate if they are set
     * @covers phpOMS\Stdlib\Base\Enum<extended>
     * @group framework
     */
    public function testFlags() : void
    {
        self::assertTrue(EnumDemo::hasFlag(13, 4));
        self::assertTrue(EnumDemo::hasFlag(13, 1));
        self::assertTrue(EnumDemo::hasFlag(13, 8));
    }

    /**
     * @testdox Binary flags don't validate if they are not set
     * @covers phpOMS\Stdlib\Base\Enum<extended>
     * @group framework
     */
    public function testInvalidFlags() : void
    {
        self::assertFalse(EnumDemo::hasFlag(13, 2));
        self::assertFalse(EnumDemo::hasFlag(13, 16));
    }

    /**
     * @testdox A invalid enum name returns null
     * @covers phpOMS\Stdlib\Base\Enum<extended>
     * @group framework
     */
    public function testInvalidConstantException() : void
    {
        self::assertNull(EnumDemo::getByName('ENUM3'));
    }
}
