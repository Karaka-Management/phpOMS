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

namespace phpOMS\tests\Business\Finance;

use phpOMS\Business\Finance\Forensics;

/**
 * @internal
 */
#[\PHPUnit\Framework\Attributes\TestDox('phpOMS\tests\Business\Finance\ForensicsTest: Forensics formulas')]
final class ForensicsTest extends \PHPUnit\Framework\TestCase
{
    public function testBenfordAnalysis() : void
    {
        $surface = [];
        $fp      = \fopen(__DIR__ . '/lakes.txt', 'r');
        while (($line = \fgets($fp)) !== false) {
            $surface[] = (int) $line;
        }

        $analysis = Forensics::benfordAnalysis($surface);

        self::assertEqualsWithDelta(0.250, $analysis[1], 0.01);
        self::assertEqualsWithDelta(0.249, $analysis[2], 0.01);
        self::assertEqualsWithDelta(0.164, $analysis[3], 0.01);
        self::assertEqualsWithDelta(0.102, $analysis[4], 0.01);
        self::assertEqualsWithDelta(0.073, $analysis[5], 0.01);
        self::assertEqualsWithDelta(0.059, $analysis[6], 0.01);
        self::assertEqualsWithDelta(0.044, $analysis[7], 0.01);
        self::assertEqualsWithDelta(0.032, $analysis[8], 0.01);
        self::assertEqualsWithDelta(0.027, $analysis[9], 0.01);
    }

    public function testExpectedBenfordDistribution() : void
    {
        $dist = Forensics::expectedBenfordDistribution();

        self::assertEqualsWithDelta(0.301, $dist[1], 0.01);
        self::assertEqualsWithDelta(0.1761, $dist[2], 0.01);
        self::assertEqualsWithDelta(0.1249, $dist[3], 0.01);
        self::assertEqualsWithDelta(0.0969, $dist[4], 0.01);
        self::assertEqualsWithDelta(0.0792, $dist[5], 0.01);
        self::assertEqualsWithDelta(0.0669, $dist[6], 0.01);
        self::assertEqualsWithDelta(0.0580, $dist[7], 0.01);
        self::assertEqualsWithDelta(0.0512, $dist[8], 0.01);
        self::assertEqualsWithDelta(0.0458, $dist[9], 0.01);
    }
}
