<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\Math\Functions;

use phpOMS\Math\Functions\Functions;
use phpOMS\Math\Functions\Gamma;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Functions\Gamma::class)]
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Math\Functions\GammaTest: Gamma function')]
final class GammaTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The gamma function can be approximated')]
    public function testGamma() : void
    {
        self::assertEqualsWithDelta(2.0, Gamma::gamma(3.0), 0.001);
        self::assertEqualsWithDelta(11.631728, Gamma::gamma(4.5), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The gamma function can be calculated for integers')]
    public function testFactorial() : void
    {
        self::assertEquals(Functions::fact(4), Gamma::getGammaInteger(5));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The gamma function can be approximated with the spouge formula')]
    public function testApproximationSpouge() : void
    {
        $spouge = [
            2.67893853, 1.35411794, 1.00000000, 0.89297951, 0.90274529, 1.00000000, 1.19063935, 1.50457549, 2.00000000, 2.77815848,
        ];

        for ($i = 1; $i <= 10; ++$i) {
            if (\abs($spouge[$i - 1] - Gamma::spougeApproximation($i / 3)) >= 0.01) {
                self::assertTrue(false);
            }
        }

        self::assertTrue(true);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The gamma function can be approximated with the stirling formula')]
    public function testApproximationStirling() : void
    {
        $stirling = [
            2.15697602, 1.20285073, 0.92213701, 0.83974270, 0.85919025, 0.95950218, 1.14910642, 1.45849038, 1.94540320, 2.70976382,
        ];

        for ($i = 1; $i <= 10; ++$i) {
            if (\abs($stirling[$i - 1] - Gamma::stirlingApproximation($i / 3)) >= 0.01) {
                self::assertTrue(false);
            }
        }

        self::assertTrue(true);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The gamma function can be approximated with the lanzos formula')]
    public function testApproximationLanzos() : void
    {
        $gsl = [
            2.67893853, 1.35411794, 1.00000000, 0.89297951, 0.90274529, 1.00000000, 1.19063935, 1.50457549, 2.00000000, 2.77815848,
        ];

        for ($i = 1; $i <= 10; ++$i) {
            if (\abs($gsl[$i - 1] - Gamma::lanczosApproximationReal($i / 3)) >= 0.01) {
                self::assertTrue(false);
            }
        }

        self::assertTrue(true);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The log gamma function can be approximated')]
    public function testLogGamma() : void
    {
        $gsl = [
            0.0, 0.0, 0.693147, 1.791759, 3.178053, 4.787491, 6.57925, 8.52516, 10.604602, 12.801827,
        ];

        for ($i = 1; $i <= 10; ++$i) {
            if (\abs($gsl[$i - 1] - Gamma::logGamma($i)) >= 0.01) {
                self::assertTrue(false);
            }
        }

        self::assertTrue(true);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The first incomplete gamma function can be approximated')]
    public function testFirstIncompleteGamma() : void
    {
        self::assertEqualsWithDelta(0.0, Gamma::incompleteGammaFirst(3.0, 0.0), 0.001);
        self::assertEqualsWithDelta(1.523793, Gamma::incompleteGammaFirst(3.0, 4.0), 0.001);
        self::assertEqualsWithDelta(2.116608, Gamma::incompleteGammaFirst(4.0, 3.0), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The second incomplete gamma function can be approximated')]
    public function testSecondIncompleteGamma() : void
    {
        self::assertEqualsWithDelta(2.0, Gamma::incompleteGammaSecond(3.0, 0.0), 0.001);
        self::assertEqualsWithDelta(0.476206, Gamma::incompleteGammaSecond(3.0, 4.0), 0.001);
        self::assertEqualsWithDelta(3.883391, Gamma::incompleteGammaSecond(4.0, 3.0), 0.001);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    #[\PHPUnit\Framework\Attributes\TestDox('The regularized incomplete gamma function can be approximated')]
    public function testRegularizedGamma() : void
    {
        self::assertEqualsWithDelta(0.0, Gamma::regularizedGamma(3.0, 0.0), 0.001);
        self::assertEqualsWithDelta(0.761896, Gamma::regularizedGamma(3.0, 4.0), 0.001);
        self::assertEqualsWithDelta(0.352768, Gamma::regularizedGamma(4.0, 3.0), 0.001);
    }
}
