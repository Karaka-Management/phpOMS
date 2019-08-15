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

namespace phpOMS\tests\Stdlib\Base;

/**
 * @internal
 */
class EnumArrayTest extends \PHPUnit\Framework\TestCase
{
    public function testGetSet() : void
    {
        self::assertEquals(1, EnumArrayDemo::get('ENUM1'));
        self::assertEquals('abc', EnumArrayDemo::get('ENUM2'));

        self::assertTrue(EnumArrayDemo::isValidName('ENUM1'));
        self::assertFalse(EnumArrayDemo::isValidName('enum1'));

        self::assertEquals(['ENUM1' => 1, 'ENUM2' => 'abc'], EnumArrayDemo::getConstants());

        self::assertTrue(EnumArrayDemo::isValidValue(1));
        self::assertTrue(EnumArrayDemo::isValidValue('abc'));
        self::assertFalse(EnumArrayDemo::isValidValue('e3'));
    }

    public function testInvalidConstantException() : void
    {
        self::expectException(\OutOfBoundsException::class);

        EnumArrayDemo::get('enum2');
    }
}
