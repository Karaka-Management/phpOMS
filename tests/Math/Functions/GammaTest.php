<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Functions;

use phpOMS\Math\Functions\Functions;
use phpOMS\Math\Functions\Gamma;

/**
 * @testdox phpOMS\tests\Math\Functions\GammaTest: Gamma function
 *
 * @internal
 */
class GammaTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @testdox The gamma function can be calculated for integers
     * @covers phpOMS\Math\Functions\Gamma
     * @group framework
     */
    public function testFactorial() : void
    {
        self::assertEquals(Functions::fact(4), Gamma::getGammaInteger(5));
    }

    /**
     * @testdox The gamma function can be approximated with the spouge formula
     * @covers phpOMS\Math\Functions\Gamma
     * @group framework
     */
    public function testApproximationSpouge() : void
    {
        $spouge = [
            2.67893853, 1.35411794, 1.00000000, 0.89297951, 0.90274529, 1.00000000, 1.19063935, 1.50457549, 2.00000000, 2.77815848,
        ];

        for ($i = 1; $i <= 10; ++$i) {
            if (!(\abs($spouge[$i - 1] - Gamma::spougeApproximation($i / 3)) < 0.01)) {
                self::assertTrue(false);
            }
        }

        self::assertTrue(true);
    }

    /**
     * @testdox The gamma function can be approximated with the stirling formula
     * @covers phpOMS\Math\Functions\Gamma
     * @group framework
     */
    public function testApproximationStirling() : void
    {
        $stirling = [
            2.15697602, 1.20285073, 0.92213701, 0.83974270, 0.85919025, 0.95950218, 1.14910642, 1.45849038, 1.94540320, 2.70976382,
        ];

        for ($i = 1; $i <= 10; ++$i) {
            if (!(\abs($stirling[$i - 1] - Gamma::stirlingApproximation($i / 3)) < 0.01)) {
                self::assertTrue(false);
            }
        }

        self::assertTrue(true);
    }

    /**
     * @testdox The gamma function can be approximated with the lanzos formula
     * @covers phpOMS\Math\Functions\Gamma
     * @group framework
     */
    public function testApproximationLanzos() : void
    {
        $gsl = [
            2.67893853, 1.35411794, 1.00000000, 0.89297951, 0.90274529, 1.00000000, 1.19063935, 1.50457549, 2.00000000, 2.77815848,
        ];

        for ($i = 1; $i <= 10; ++$i) {
            if (!(\abs($gsl[$i - 1] - Gamma::lanczosApproximationReal($i / 3)) < 0.01)) {
                self::assertTrue(false);
            }
        }

        self::assertTrue(true);
    }

    public function testLogGamma() : void
    {
        self::markTestIncomplete();
    }
}
