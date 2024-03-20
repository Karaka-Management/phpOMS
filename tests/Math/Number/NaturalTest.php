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

namespace phpOMS\tests\Math\Number;

use phpOMS\Math\Number\Natural;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Number\Natural::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Number\NaturalTest: Natural number operations')]
final class NaturalTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('A number can be checked to be natural')]
    public function testIsNatural() : void
    {
        self::assertTrue(Natural::isNatural(1235));
        self::assertTrue(Natural::isNatural(0));
        self::assertFalse(Natural::isNatural(-1235));
        self::assertFalse(Natural::isNatural('123'));
        self::assertFalse(Natural::isNatural(1.23));
    }
}
