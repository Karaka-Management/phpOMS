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
 * @link       https://orange-management.org
 */
 declare(strict_types=1);

namespace phpOMS\tests\Math\Number;

use phpOMS\Math\Number\NumberType;

/**
 * @internal
 */
class NumberTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEnums() : void
    {
        self::assertCount(9, NumberType::getConstants());
        self::assertEquals(NumberType::getConstants(), \array_unique(NumberType::getConstants()));

        self::assertEquals(0, NumberType::N_INTEGER);
        self::assertEquals(1, NumberType::N_NATURAL);
        self::assertEquals(2, NumberType::N_EVEN);
        self::assertEquals(4, NumberType::N_UNEVEN);
        self::assertEquals(8, NumberType::N_PRIME);
        self::assertEquals(16, NumberType::N_REAL);
        self::assertEquals(32, NumberType::N_RATIONAL);
        self::assertEquals(64, NumberType::N_IRRATIONAL);
        self::assertEquals(128, NumberType::N_COMPLEX);
    }
}
