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


class EnumArrayTest extends \PHPUnit\Framework\TestCase
{
    public function testGetSet() : void
    {
        self::assertEquals(1, EnumArrayDemo::get('ENUM1'));
        self::assertEquals('abc', EnumArrayDemo::get('ENUM2'));

        self::assertTrue(EnumArrayDemo::isValidName('ENUM1'));
        self::assertFalse(EnumArrayDemo::isValidName('enum1'));

        self::assertEquals(['ENUM1' => 1, 'ENUM2' => 'abc'], EnumArrayDemo::getConstants(), true);

        self::assertTrue(EnumArrayDemo::isValidValue(1));
        self::assertTrue(EnumArrayDemo::isValidValue('abc'));
        self::assertFalse(EnumArrayDemo::isValidValue('e3'));
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testInvalidConstantException() : void
    {
        EnumArrayDemo::get('enum2');
    }
}
