<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Number;

use phpOMS\Math\Number\Natural;

/**
 * @testdox phpOMS\tests\Math\Number\NaturalTest: Natural number operations
 *
 * @internal
 */
class NaturalTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox A number can be checked to be natural
     * @covers phpOMS\Math\Number\Natural
     * @group framework
     */
    public function testIsNatural() : void
    {
        self::assertTrue(Natural::isNatural(1235));
        self::assertTrue(Natural::isNatural(0));
        self::assertFalse(Natural::isNatural(-1235));
        self::assertFalse(Natural::isNatural('123'));
        self::assertFalse(Natural::isNatural(1.23));
    }
}
