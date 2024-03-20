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

namespace phpOMS\tests\Math\Stochastic\Distribution;

use phpOMS\Math\Stochastic\Distribution\LogDistribution;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\CoversClass(\phpOMS\Math\Stochastic\Distribution\LogDistribution::class)]
final class LogDistributionTest extends \PHPUnit\Framework\TestCase
{
    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testPmf() : void
    {
        $p = 0.3;
        $k = 4;

        self::assertEquals(
            -1 / \log(1 - $p) * $p ** $k / $k,
            LogDistribution::getPmf($p, $k)
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testCdf() : void
    {
        $p = 6 / 9;
        $k = 2;

        self::assertEqualsWithDelta(
            0.8091,
            LogDistribution::getCdf($p, $k), 0.001
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMean() : void
    {
        $p = 0.3;

        self::assertEquals(-1 / \log(1 - $p) * $p / (1 - $p), LogDistribution::getMean($p));
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMode() : void
    {
        self::assertEquals(1, LogDistribution::getMode());
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testVariance() : void
    {
        $p = 0.3;

        self::assertEquals(
            -($p ** 2 + $p * \log(1 - $p)) / ((1 - $p) ** 2 * (\log(1 - $p)) ** 2),
            LogDistribution::getVariance($p)
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testStandardDeviation() : void
    {
        $p = 0.3;

        self::assertEquals(
            \sqrt(-($p ** 2 + $p * \log(1 - $p)) / ((1 - $p) ** 2 * (\log(1 - $p)) ** 2)),
            LogDistribution::getStandardDeviation($p)
        );
    }

    #[\PHPUnit\Framework\Attributes\Group('framework')]
    public function testMgf() : void
    {
        $p = 0.3;
        $t = 0.8;

        self::assertEquals(
            \log(1 - $p * \exp($t)) / \log(1 - $p),
            LogDistribution::getMgf($p, $t)
        );
    }
}
