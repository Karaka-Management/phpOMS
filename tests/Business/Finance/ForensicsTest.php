<?php
/**
 * Karaka
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
        \ksort($analysis);

        self::assertEqualsWithDelta(31.81, $analysis[1], 0.01);
        self::assertEqualsWithDelta(20.55, $analysis[2], 0.01);
        self::assertEqualsWithDelta(12.51, $analysis[3], 0.01);
        self::assertEqualsWithDelta(9.29, $analysis[4], 0.01);
        self::assertEqualsWithDelta(6.61, $analysis[5], 0.01);
        self::assertEqualsWithDelta(6.17, $analysis[6], 0.01);
        self::assertEqualsWithDelta(5.00, $analysis[7], 0.01);
        self::assertEqualsWithDelta(4.47, $analysis[8], 0.01);
        self::assertEqualsWithDelta(3.57, $analysis[9], 0.01);
    }

    public function testExpectedBenfordDistribution() : void
    {
        $dist = Forensics::expectedBenfordDistribution();

        self::assertEqualsWithDelta(30.1, $dist[1], 0.01);
        self::assertEqualsWithDelta(17.61, $dist[2], 0.01);
        self::assertEqualsWithDelta(12.49, $dist[3], 0.01);
        self::assertEqualsWithDelta(9.69, $dist[4], 0.01);
        self::assertEqualsWithDelta(7.92, $dist[5], 0.01);
        self::assertEqualsWithDelta(6.69, $dist[6], 0.01);
        self::assertEqualsWithDelta(5.80, $dist[7], 0.01);
        self::assertEqualsWithDelta(5.12, $dist[8], 0.01);
        self::assertEqualsWithDelta(4.58, $dist[9], 0.01);
    }
}
