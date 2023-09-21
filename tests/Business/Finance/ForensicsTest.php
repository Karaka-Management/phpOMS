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
 * @testdox phpOMS\tests\Business\Finance\ForensicsTest: Forensics formulas
 *
 * @internal
 */
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

        self::assertEqualsWithDelta(0.3181, $analysis[1], 0.01);
        self::assertEqualsWithDelta(0.2055, $analysis[2], 0.01);
        self::assertEqualsWithDelta(0.1251, $analysis[3], 0.01);
        self::assertEqualsWithDelta(0.929, $analysis[4], 0.01);
        self::assertEqualsWithDelta(0.661, $analysis[5], 0.01);
        self::assertEqualsWithDelta(0.617, $analysis[6], 0.01);
        self::assertEqualsWithDelta(0.500, $analysis[7], 0.01);
        self::assertEqualsWithDelta(0.447, $analysis[8], 0.01);
        self::assertEqualsWithDelta(0.357, $analysis[9], 0.01);
    }

    public function testExpectedBenfordDistribution() : void
    {
        $dist = Forensics::expectedBenfordDistribution();

        self::assertEqualsWithDelta(0.301, $dist[1], 0.01);
        self::assertEqualsWithDelta(0.1761, $dist[2], 0.01);
        self::assertEqualsWithDelta(0.1249, $dist[3], 0.01);
        self::assertEqualsWithDelta(0.969, $dist[4], 0.01);
        self::assertEqualsWithDelta(0.792, $dist[5], 0.01);
        self::assertEqualsWithDelta(0.669, $dist[6], 0.01);
        self::assertEqualsWithDelta(0.580, $dist[7], 0.01);
        self::assertEqualsWithDelta(0.512, $dist[8], 0.01);
        self::assertEqualsWithDelta(0.458, $dist[9], 0.01);
    }
}
