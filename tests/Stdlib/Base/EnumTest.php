<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    tests
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */

namespace phpOMS\tests\Stdlib\Base;


class EnumTest extends \PHPUnit\Framework\TestCase
{
    public function testGetSet() : void
    {
        self::assertTrue(EnumDemo::isValidName('ENUM1'));
        self::assertFalse(EnumDemo::isValidName('enum1'));

        self::assertEquals(['ENUM1' => 1, 'ENUM2' => ';l'], EnumDemo::getConstants(), true);

        self::assertTrue(EnumDemo::isValidValue(1));
        self::assertTrue(EnumDemo::isValidValue(';l'));
        self::assertFalse(EnumDemo::isValidValue('e3'));
        self::assertTrue(EnumDemo::isValidValue(EnumDemo::getRandom()));
        self::assertEquals(EnumDemo::ENUM2, EnumDemo::getByName('ENUM2'));
        self::assertEquals(EnumDemo::ENUM2, EnumDemo::getByName('ENUM2'));
        self::assertEquals(2, EnumDemo::count());
        self::assertEquals('ENUM1', EnumDemo::getName('1'));
        self::assertEquals('ENUM2', EnumDemo::getName(';l'));
    }

    public function testEmailException() : void
    {
        self::expectedException(\Exception::class);

        EnumDemo::getByName('ENUM3');
    }
}
