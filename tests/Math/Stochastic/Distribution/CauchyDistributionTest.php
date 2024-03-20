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

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\CauchyDistribution;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Stochastic\Distribution\CauchyDistribution::class)]
final class CauchyDistributionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMedianMode() : void
    {
        self::assertEquals(3.2, CauchyDistribution::getMedian(3.2));
        self::assertEquals(3.2, CauchyDistribution::getMode(3.2));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testPdf() : void
    {
        $x     = 1;
        $x0    = 0.5;
        $gamma = 2;

        self::assertEqualsWithDelta(0.14979, CauchyDistribution::getPdf($x, $x0, $gamma), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testCdf() : void
    {
        $x     = 1;
        $x0    = 0.5;
        $gamma = 2;

        self::assertEqualsWithDelta(0.57798, CauchyDistribution::getCdf($x, $x0, $gamma), 0.01);
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testEntropy() : void
    {
        $gamma = 1.5;

        self::assertEqualsWithDelta(\log(4 * \M_PI * $gamma), CauchyDistribution::getEntropy($gamma), 0.01);
    }
}
